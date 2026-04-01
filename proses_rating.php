<?php
include 'config.php';

// Proteksi Akses: Hanya mahasiswa yang bisa memberi rating
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'mahasiswa') { 
    echo "<script>alert('Harap login sebagai mahasiswa untuk memberi ulasan.'); window.location='index.php';</script>";
    exit; 
}

if(isset($_POST['kirim_rating'])){
    // Amankan input (Gunakan casting tipe data int karena ID dan Bintang adalah angka)
    $id_kos = (int)$_POST['id_kos'];
    $id_user = (int)$_SESSION['id_user'];
    $bintang = (int)$_POST['bintang'];
    
    // Amankan input teks dari injeksi
    $ulasan = mysqli_real_escape_string($conn, $_POST['ulasan']);

    $sql = "INSERT INTO rating (id_kos, id_user, bintang, ulasan) VALUES ('$id_kos', '$id_user', '$bintang', '$ulasan')";
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Terima kasih atas ulasan Anda!'); window.location='detail_kost.php?id=$id_kos';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>