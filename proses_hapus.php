<?php
include 'config.php';

// Pastikan sesi berjalan
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Proteksi: Hanya user yang login yang bisa menghapus
if(!isset($_SESSION['role'])) {
    exit('Akses Ditolak');
}

// Amankan ID (Paksa menjadi integer)
$id = (int)$_GET['id'];

mysqli_query($conn, "DELETE FROM booking WHERE id_booking='$id'");
header("Location: dashboard.php");
?>