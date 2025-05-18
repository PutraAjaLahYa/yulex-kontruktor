<?php
// koneksi ke database (ganti sesuai config kamu)
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "yulex"; // ganti dengan nama database kamu

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nama = $_POST["nama"];
  $username = $_POST["username"];
  $password = $_POST["password"];
  $jabatan = $_POST["jabatan"];

  if (strlen($password) < 6) {
    $pesan = "Password minimal 6 karakter!";
  } else {
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (nama, username, password, jabatan) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $username, $passwordHash, $jabatan);

    if ($stmt->execute()) {
      $pesan = "Akun berhasil dibuat untuk $username.";
    } else {
      $pesan = "Gagal membuat akun: " . $stmt->error;
    }

    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Buat Akun - Yulex Konstruktor</title>
  <style>
    body {
      background: #f4f4f4;
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .form-box {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
      width: 400px;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    input, select, button {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    button {
      background: #27ae60;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background: #219150;
    }

    .message {
      text-align: center;
      color: #e74c3c;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <form class="form-box" method="POST">
    <h2>Buat Akun Baru</h2>
    <input type="text" name="nama" placeholder="Nama Lengkap" required>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password (min 6 karakter)" required>
    <select name="jabatan" required>
      <option value="">-- Pilih Jabatan --</option>
      <option value="Admin">Admin</option>
      <option value="Tukang">Tukang</option>
      <option value="Gudang">Gudang</option>
      <option value="logistik">Logistik</option>
    </select>
    <button type="submit">Buat Akun</button>
    <?php if ($pesan): ?>
      <div class="message"><?= $pesan ?></div>
    <?php endif; ?>
  </form>
</body>
</html>
