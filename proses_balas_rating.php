<?php
include 'config.php';

// Proteksi Akses: Hanya Admin dan Pemilik yang bisa membalas
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['role']) || $_SESSION['role'] == 'mahasiswa') { 
    echo "<script>alert('Akses Ditolak!'); window.location='index.php';</script>";
    exit; 
}

if(isset($_POST['kirim_balasan'])){
    // Amankan input form dari serangan SQL Injection
    $id_rating = (int)$_POST['id_rating']; 
    $balasan = mysqli_real_escape_string($conn, $_POST['balasan']);

    $sql = "UPDATE rating SET balasan_pemilik='$balasan' WHERE id_rating='$id_rating'";
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Balasan berhasil dikirim!'); window.location='dashboard.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn); 
    }
}
?>