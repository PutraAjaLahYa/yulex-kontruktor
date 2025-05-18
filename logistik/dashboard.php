<?php
include '../sidebar.php';
// Koneksi database
$conn = new mysqli("localhost", "root", "", "yulex");
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

function formatTanggalIndo($tanggal) {
  $hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
  $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli',
            'Agustus','September','Oktober','November','Desember'];

  $date = date_create($tanggal);
  $hariIndex = date_format($date, 'w');
  $tgl = date_format($date, 'j');
  $blnIndex = date_format($date, 'n') - 1;
  $thn = date_format($date, 'Y');

  return $hari[$hariIndex] . ", " . $tgl . " " . $bulan[$blnIndex] . " " . $thn;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Gudang - Yulex Konstruktor</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
body {
  font-family: 'Segoe UI', sans-serif;
  background: linear-gradient(135deg, #2c3e50, #3498db);
  margin: 0;
  padding: 0;
}

.container {
  max-width: 1000px;
  width: 90%;
  margin: 40px auto;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  box-sizing: border-box;
  background-color: transparent; /* jika butuh transparan */
}


img.nota-preview {
  max-width: 120px;
  height: auto;
  display: block;
  margin: 0 auto;
  border-radius: 8px;
  box-shadow: 0 0 8px rgba(0,0,0,0.2);
}

.table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  color: white;
}

.table th, .table td {
  padding: 10px;
  border: 1px solid #ccc;
  vertical-align: middle;
  text-align: center;
}

.mb-4 {
  color: white;
  text-align: center;
  font-size: 24px;
  margin-bottom: 20px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  body {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh; /* ini penting agar tinggi full layar */
    padding: 20px; /* biar nggak terlalu mepet */
  }

  .container {
    transform: none;
    position: static;
    width: 100%;
    margin: 0;
    margin-top: 50px;
  }


  .mb-4 {
    font-size: 20px;
  }

  .table th, .table td {
    font-size: 14px;
    padding: 8px;
  }

  img.nota-preview {
    max-width: 100px;
  }
}

@media (max-width: 480px) {
  .mb-4 {
    font-size: 18px;
  }

  .table th, .table td {
    font-size: 12px;
    padding: 6px;
  }

  img.nota-preview {
    max-width: 80px;
  }

  .container {
    padding: 10px;
  }
}
  </style>
</head>
<body>

<div class="container">
  <h2 class="mb-4">Data Barang Gudang</h2>

  <div class="table-responsive">
    <table class="table table-striped table-hover table-bordered">
      <thead class="table-dark">
        <tr>
          <th>No</th>
          <th>Nama Barang</th>
          <th>Jumlah</th>
          <th>Satuan</th>
          <th>Tanggal Masuk</th>
          <th>Foto Nota</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1;
        $query = "SELECT * FROM barang ORDER BY tanggal_masuk DESC";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>{$no}</td>";
          echo "<td>{$row['nama_barang']}</td>";
          echo "<td>{$row['jumlah_barang']}</td>";
          echo "<td>" . (!empty($row['satuan_barang']) ? $row['satuan_barang'] : '-') . "</td>";
          echo "<td>" . formatTanggalIndo($row['tanggal_masuk']) . "</td>";
          echo "<td>";
          if (!empty($row['foto_nota'])) {
            echo "<img src='../uploads/nota/{$row['foto_nota']}' class='nota-preview'>";
          } else {
            echo "Tidak ada";
          }
          echo "</td>";
          echo "</tr>";
          $no++;
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
