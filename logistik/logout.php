<?php
session_start();

// Hapus semua data session
$_SESSION = [];

// Hancurkan session
session_destroy();

// Redirect ke halaman login setelah logout
header('Location: ../login.php');
exit;
