<?php
include 'config.php';

// Pastikan sesi berjalan
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Proteksi: Hanya Admin/Pemilik yang bisa terima booking
if(!isset($_SESSION['role']) || $_SESSION['role'] == 'mahasiswa') {
    exit('Akses Ditolak');
}

// Amankan Input dari URL
$id = (int)$_GET['id']; 
// Filter nomor HP agar hanya angka yang masuk
$hp = preg_replace('/[^0-9]/', '', $_GET['hp']); 
$kos = htmlspecialchars($_GET['kos']);

// Update status di database
mysqli_query($conn, "UPDATE booking SET status='Disetujui' WHERE id_booking='$id'");

// Format nomor HP untuk WhatsApp
if(substr($hp, 0, 1) == '0') {
    $hp = '62' . substr($hp, 1);
}

// Redirect ke WhatsApp
$pesan = urlencode("Halo! Saya pengelola *$kos*. Booking Anda telah disetujui. Mari diskusikan lebih lanjut.");
header("Location: https://wa.me/$hp?text=$pesan");
?>