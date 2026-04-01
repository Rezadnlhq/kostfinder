<?php
include 'config.php';

// Pastikan sesi berjalan
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Proteksi Keamanan: Hanya Admin yang boleh menghapus Kost
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    header("Location: dashboard.php"); 
    exit; 
}

if(isset($_GET['id'])) {
    // Amankan input ID dari URL
    $id_kost = (int)$_GET['id'];
    
    // Eksekusi Hapus Kost
    $sql = "DELETE FROM kos WHERE id_kos='$id_kost'";
    
    if(mysqli_query($conn, $sql)) {
        echo "<script>alert('Kost Berhasil Dihapus!'); window.location='dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus kost.'); window.location='dashboard.php';</script>";
    }
} else {
    header("Location: dashboard.php");
}
?>