<?php

function getWeeksInMonth($month, $year) {
    $weeks = [];

    // Awal dan akhir bulan
    $start = new DateTime("$year-$month-01");
    $end = new DateTime($start->format('Y-m-t'));

    $current = clone $start;

    while ($current <= $end) {
        // Tentukan awal minggu
        $weekStart = clone $current;

        // Jika bukan hari Senin, mundur ke Senin sebelumnya
        if ($current->format('N') != 1) {
            $weekStart->modify('last monday');
        }

        // Tentukan akhir minggu (Minggu)
        $weekEnd = clone $weekStart;
        $weekEnd->modify('+6 days');

        // Batas akhir bulan
        if ($weekEnd > $end) {
            $weekEnd = clone $end;
        }

        // Simpan range minggu (hanya yang masih dalam bulan)
        $weeks[] = [
            'start' => $weekStart->format('Y-m-d'),
            'end'   => $weekEnd->format('Y-m-d')
        ];

        // Pindah ke hari setelah akhir minggu
        $current = clone $weekEnd;
        $current->modify('+1 day');
    }

    return $weeks;
}
?>
