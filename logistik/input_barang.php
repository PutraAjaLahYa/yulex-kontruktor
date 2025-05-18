<?php
session_start();

include '../sidebar.php';

// Koneksi database
$conn = new mysqli("localhost", "root", "", "yulex");
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Proses simpan
$success = $error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $nama = $_POST['nama_barang'];
  $jumlah = $_POST['jumlah_barang'];
  $satuan = $_POST['satuan_barang'];
  $tanggal = date('Y-m-d');
  $foto_nota = "";

  if (!empty($_FILES['foto_nota']['name'])) {
    $nama_file = time() . "_" . basename($_FILES['foto_nota']['name']);
    $target_dir = "../uploads/nota/";
    $target_file = $target_dir . $nama_file;

    if (move_uploaded_file($_FILES['foto_nota']['tmp_name'], $target_file)) {
      $foto_nota = $nama_file;
    } else {
      $error = "Gagal upload gambar nota.";
    }
  }

  if ($nama != "" && $jumlah != "") {
    $stmt = $conn->prepare("INSERT INTO barang (nama_barang, jumlah_barang, satuan_barang, tanggal_masuk, foto_nota) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $nama, $jumlah, $satuan, $tanggal, $foto_nota);
    if ($stmt->execute()) {
      $success = "Data barang berhasil disimpan!";
    } else {
      $error = "Gagal simpan data: " . $stmt->error;
    }
    $stmt->close();
  } else {
    $error = "Nama dan jumlah barang wajib diisi.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Input Barang - Yulex Konstruktor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #2c3e50, #3498db);
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
  background-color: 0. 0. 0. 0.6;
  padding: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

    .form-label {
      font-weight: bold;
      color:white;
    }
    .form-control {
      border-radius: 5px;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }
    .btn-primary {
      background-color: #3498db;
      border: none;
      border-radius: 5px;
      padding: 10px 20px;
      font-size: 16px;
    }
    .btn-primary:hover {
      background-color: #2980b9;
    }
    .alert {
      margin-top: 20px;
      border-radius: 5px;
      padding: 10px;
    }
    .alert-success {
      background-color: #d4edda;
      color: #155724;
    }
    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
    }
    .mb-4 {
      color: white;
      text-align: center;
      font-size: 24px;
    }
    @media (max-width: 768px) {
      .container {
        width: 95%;
        margin-top: 50px;
      }
    }

  </style>
</head>
<body>

<div class="container">
  <h2 class="mb-4">Form Input Barang</h2>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="nama_barang" class="form-label">Nama Barang *</label>
      <input type="text" class="form-control" name="nama_barang" required>
    </div>

    <div class="mb-3">
      <label for="jumlah_barang" class="form-label">Jumlah Barang *</label>
      <input type="number" class="form-control" name="jumlah_barang" required>
    </div>

    <div class="mb-3">
      <label for="satuan_barang" class="form-label">Satuan Barang (opsional)</label>
      <input type="text" class="form-control" name="satuan_barang">
    </div>

    <div class="mb-3">
      <label for="foto_nota" class="form-label">Foto Nota (opsional)</label>
      <input type="file" class="form-control" name="foto_nota" accept="image/*">
    </div>

    <button type="submit" class="btn btn-primary">Simpan Barang</button>
  </form>
</div>

</body>
</html>
