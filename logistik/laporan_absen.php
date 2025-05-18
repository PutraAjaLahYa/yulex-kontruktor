<?php
session_start();

include '../sidebar.php';
include 'generate_weeks.php';
$conn = new mysqli("localhost", "root", "", "yulex");

// Otomatis pakai bulan dan tahun hari ini
$today = date('Y-m-d');
$bulan = date('n');
$tahun = date('Y');

// Ambil semua minggu dalam bulan ini
$weeks = getWeeksInMonth($bulan, $tahun);

// Cari minggu yang sedang berjalan
$mingguIndex = 0;
foreach ($weeks as $i => $week) {
    if ($today >= $week['start'] && $today <= $week['end']) {
        $mingguIndex = $i;
        break;
    }
}
$week = $weeks[$mingguIndex];
$startDate = $week['start'];
$endDate = $week['end'];

// Format tanggal dan judul
$bulanNama = date('F', strtotime($startDate));
$bulanID = [
  'January'=>'Januari','February'=>'Februari','March'=>'Maret','April'=>'April',
  'May'=>'Mei','June'=>'Juni','July'=>'Juli','August'=>'Agustus',
  'September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Desember'
];
$judul = "Laporan Absensi Mingguan";
$subjudul = "Minggu ke-" . ($mingguIndex+1) . " Bulan " . $bulanID[$bulanNama] .
            " (" . date('j', strtotime($startDate)) . " – " . date('j F Y', strtotime($endDate)) . ")";

// Hari dalam bahasa Indonesia
$hariMap = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];

// Ambil daftar tanggal
$tanggalList = [];
$period = new DatePeriod(
    new DateTime($startDate),
    new DateInterval('P1D'),
    (new DateTime($endDate))->modify('+1 day')
);
foreach ($period as $date) {
    $tanggalList[] = [
        'tanggal' => $date->format('Y-m-d'),
        'hari' => $hariMap[$date->format('l')]
    ];
}

// Ambil karyawan
$karyawanList = [];
$result = $conn->query("SELECT id, nama FROM karyawan ORDER BY nama ASC");
while ($row = $result->fetch_assoc()) {
    $karyawanList[] = $row;
}

// Ambil data absen
$absenData = [];
$q = $conn->query("SELECT id_karyawan, tanggal, status FROM absen WHERE tanggal BETWEEN '$startDate' AND '$endDate'");
while ($row = $q->fetch_assoc()) {
    $absenData[$row['id_karyawan']][$row['tanggal']] = $row['status'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= $judul ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
 @media print {
  body { -webkit-print-color-adjust: exact; }
}

body {
  background: linear-gradient(135deg, #2c3e50, #3498db);
  font-family: 'Segoe UI', sans-serif;
  padding: 40px 20px;
  color: #2c3e50;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: flex-start; /* biar agak ke atas tapi tetap tengah horizontal */
}

.table-container {
  max-width: 1000px; /* lebih lebar untuk desktop */
  width: 100%;
  border: 1px solid #bdc3c7;
  border-radius: 10px;
  background-color: white;
  padding: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  box-sizing: border-box;
  /* Hilangkan position absolute */
}

.header {
  text-align: center;
  margin-bottom: 30px;
}

.header h1 {
  font-size: 26px;
  margin-bottom: 5px;
  color: black;
}

.header h4 {
  font-weight: normal;
  color: black;
}

/* Container untuk tabel agar bisa scroll horizontal di layar kecil */
.table-responsive {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch; /* smooth scroll di iOS */
}

/* Style tabel */
table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
  min-width: 600px; /* minimal lebar agar tabel tidak hancur */
}

th, td {
  border: 1px solid #2c3e50;
  text-align: center;
  padding: 8px;
}

th {
  background-color: #34495e;
  color: white;
}

.hadir {
  background-color: #2ecc71;
  color: white;
  font-weight: bold;
}

.alfa {
  background-color: #e74c3c;
  color: white;
  font-weight: bold;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  body {
    padding: 20px 10px;
    align-items: center; /* Tengah secara vertikal dan horizontal */
  }

  .table-container {
    padding: 15px;
    max-width: 100%;
  }

  table {
    font-size: 12px;
    min-width: 500px; /* Bisa disesuaikan, jangan terlalu kecil */
  }

  th, td {
    padding: 6px 4px;
  }

  .header h1 {
    font-size: 22px;
  }

  .header h4 {
    font-size: 16px;
  }
}

@media (max-width: 480px) {
  table {
    min-width: 400px; /* Supaya masih scroll */
  }

  .header h1 {
    font-size: 18px;
  }

  .header h4 {
    font-size: 14px;
  }
}

  </style>
</head>
<body>

  
  <div class="table-container">
    <div class="header">
      <h1><?= $judul ?></h1>
      <h4><?= $subjudul ?></h4>
    </div>
    <div class="table-responsive">
    <table>
      <thead>
        <tr>
          <th>Nama Karyawan</th>
          <?php foreach ($tanggalList as $tgl): ?>
            <th><?= $tgl['hari'] ?><br><?= date('j/m', strtotime($tgl['tanggal'])) ?></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($karyawanList as $karyawan): ?>
          <tr>
            <td style="text-align:left"><?= htmlspecialchars($karyawan['nama']) ?></td>
            <?php foreach ($tanggalList as $tgl): 
              $status = $absenData[$karyawan['id']][$tgl['tanggal']] ?? '';
              $class = '';
              if ($status === 'Hadir') $class = 'hadir';
              else if ($status === 'Alfa') $class = 'alfa';
            ?>
              <td class="<?= $class ?>"><?= $status ?></td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  </div>

</body>
</html>
