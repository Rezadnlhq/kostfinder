<?php
include 'config.php';

// Pastikan sesi berjalan
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Proteksi: Hanya Admin/Pemilik yang bisa menolak booking
if(!isset($_SESSION['role']) || $_SESSION['role'] == 'mahasiswa') {
    exit('Akses Ditolak');
}

// Amankan input ID dari URL (Paksa jadi tipe data Integer/Angka)
$id = (int)$_GET['id'];

mysqli_query($conn, "UPDATE booking SET status='Dibatalkan' WHERE id_booking='$id'");
header("Location: dashboard.php");
?>