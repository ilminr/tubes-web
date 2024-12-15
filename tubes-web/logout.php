<?php
session_start(); // Memulai session

// Menghapus semua session
session_unset();
session_destroy();

// Redirect ke halaman login atau halaman utama
header("Location: index.php");
exit;
?>
