<?php
session_start();

include '../sidebar.php';
$conn = new mysqli("localhost", "root", "", "yulex");

// Simpan absen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_karyawan = $_POST['id_karyawan'];
  $nama_karyawan = $_POST['nama_karyawan'];
  $status = $_POST['status'];
  $tanggal = date('Y-m-d');

  // Cegah double absen
  $check = $conn->query("SELECT * FROM absen WHERE id_karyawan='$id_karyawan' AND tanggal='$tanggal'");
  if ($check->num_rows == 0) {
    $stmt = $conn->prepare("INSERT INTO absen (id_karyawan, nama_karyawan, status, tanggal) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $id_karyawan, $nama_karyawan, $status, $tanggal);
    $stmt->execute();
    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Absen Karyawan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #2c3e50, #3498db);
      font-family: 'Segoe UI', sans-serif;
    }
    .container {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      max-width: 600px;
      width: 90%;
      border: 1px solid #bdc3c7;
      border-radius: 10px;
      background-color: white;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .absen-box {
      background: #fff;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 10px;
      box-shadow: 0 0 6px rgba(0, 0, 0, 0.1);
    }
    .absen-header {
      font-weight: bold;
      font-size: 20px;
      margin-bottom: 20px;
      margin-top: 35px;
      text-align: center;
      color: black;
    }
    .btn-hadir {
      background-color: #2ecc71;
      color: white;
    }
    .btn-alfa {
      background-color: #e74c3c;
      color: white;
    }
    .btn-hadir:hover, .btn-alfa:hover {
      opacity: 0.8;
    }
    .btn {
      border-radius: 5px;
      padding: 8px 12px;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="absen-header">Absensi Karyawan Hari Ini (<?= date('l, d F Y') ?>)</div>
    <?php
    $result = $conn->query("SELECT * FROM karyawan ORDER BY nama ASC");
    $tanggal_hari_ini = date('Y-m-d');

    while ($row = $result->fetch_assoc()) {
      $id_karyawan = $row['id'];
      $cek_absen = $conn->query("SELECT status FROM absen WHERE id_karyawan='$id_karyawan' AND tanggal='$tanggal_hari_ini'");
    ?>
      <div class="absen-box d-flex justify-content-between align-items-center">
        <div><strong><?= htmlspecialchars($row['nama']) ?></strong> (Block: <?= htmlspecialchars($row['block']) ?>)</div>
        <div>
          <?php if ($cek_absen->num_rows > 0): 
              $status = $cek_absen->fetch_assoc()['status'];
              $badge_class = ($status == 'Hadir') ? 'success' : 'danger';
          ?>
            <span class="badge bg-<?= $badge_class ?>">Sudah Absen: <?= $status ?></span>
          <?php else: ?>
            <form method="post" class="d-flex gap-2">
              <input type="hidden" name="id_karyawan" value="<?= $row['id'] ?>">
              <input type="hidden" name="nama_karyawan" value="<?= htmlspecialchars($row['nama']) ?>">
              <button type="submit" name="status" value="Hadir" class="btn btn-hadir btn-sm">✅ Hadir</button>
              <button type="submit" name="status" value="Alfa" class="btn btn-alfa btn-sm">❌ Alfa</button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    <?php } ?>
  </div>
</body>
</html>
