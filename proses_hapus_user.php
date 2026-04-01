<?php
include 'config.php';

// Pastikan sesi berjalan
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Ekstra Proteksi: Hanya admin yang boleh menghapus User
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    header("Location: dashboard.php"); 
    exit; 
}

if(isset($_GET['id'])) {
    // Amankan input ID dari URL
    $id_user = (int)$_GET['id'];
    mysqli_query($conn, "DELETE FROM users WHERE id_user='$id_user'");
}

header("Location: dashboard.php");
?>