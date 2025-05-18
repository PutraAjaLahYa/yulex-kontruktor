<?php
session_start();

include '../sidebar.php';

$conn = new mysqli("localhost", "root", "", "yulex");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = $_POST['nama'];
  $block = $_POST['block'];
  $tanggal = $_POST['tanggal'];

  $stmt = $conn->prepare("INSERT INTO karyawan (nama, block, tanggal) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $nama, $block, $tanggal);
  if ($stmt->execute()) {
    echo "<script>alert('Karyawan berhasil ditambahkan!'); window.location.href='input_karyawan.php';</script>";
  } else {
    echo "<script>alert('Gagal menambahkan karyawan.');</script>";
  }
  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Input Karyawan - Yulex</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #2c3e50, #3498db);
      font-family: 'Segoe UI', sans-serif;

.container {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  max-width: 600px;
  width: 90%;
  border: 1px solid #bdc3c7;
  border-radius: 10px;
  background-color: 0. 0. 0. 0.6;
  padding: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
    .form-title {
      text-align: center;
      font-weight: bold;
      margin-bottom: 20px;
      color: white;
    }
    .form-label {
      font-weight: bold;
      color: white;
    }
  </style>
</head>
<body>
  <div class="container">
    <h4 class="form-title">Input Data Karyawan</h4>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Block</label>
        <input type="text" name="block" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Simpan Karyawan</button>
    </form>
  </div>
</body>
</html>
