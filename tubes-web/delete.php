<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'database.php';

// Cek apakah ID dikirim melalui POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Hapus data dari database
    $query = "DELETE FROM attractions WHERE id = '$id'";
    if ($conn->query($query)) {
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    header("Location: admin_dashboard.php");
    exit;
}
?>
