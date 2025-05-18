<?php
session_start();

// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "yulex"; // ganti dengan nama database kamu

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$username = $_POST['username'];
$password = $_POST['password'];

// Cek apakah username ada
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
  $user = $result->fetch_assoc();

  // Verifikasi password
  if (password_verify($password, $user['password'])) {
    // Simpan data ke session
    $_SESSION['id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['jabatan'] = $user['jabatan'];
    $_SESSION['nama'] = $user['nama'];

    // Jika nama masih kosong → redirect ke halaman pengisian nama
    if (empty($user['nama'])) {
      header("Location: isi_nama.php");
    } else {
      // Redirect ke halaman utama sesuai jabatan
      if ($user['jabatan'] === 'Admin') {
        header("Location: admin/dashboard.php");
      } elseif ($user['jabatan'] === 'logistik') {
        header("Location: logistik/dashboard.php");
      } elseif ($user['jabatan'] === 'Tukang') {
        header("Location: tukang/dashboard.php");
      } else {
        echo "Jabatan tidak dikenali.";
      }
    }
    exit;
  } else {
    echo "<script>alert('Password salah!'); window.history.back();</script>";
  }
} else {
  echo "<script>alert('Username tidak ditemukan!'); window.history.back();</script>";
}
?>
