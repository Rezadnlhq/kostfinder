<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

// Pastikan hanya admin & pemilik yang bisa mengakses API ini
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'pemilik')) {
    echo json_encode(['jumlah_baru' => 0]);
    exit;
}

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role'];
$jumlah = 0;

// Query untuk menghitung jumlah keluhan yang statusnya masih 'Belum Dibaca' (contoh status: 0)
if ($role == 'admin') {
    // Admin melihat semua keluhan masuk
    $query = mysqli_query($conn, "SELECT COUNT(*) as total FROM keluhan WHERE status_baca = 0");
} else if ($role == 'pemilik') {
    // Pemilik hanya melihat keluhan yang ditujukan ke kos miliknya
    $query = mysqli_query($conn, "SELECT COUNT(k.id_keluhan) as total FROM keluhan k JOIN kos ks ON k.id_kos = ks.id_kos WHERE ks.id_pemilik = '$id_user' AND k.status_baca = 0");
}

if ($query) {
    $data = mysqli_fetch_assoc($query);
    $jumlah = $data['total'];
}

echo json_encode(['jumlah_baru' => $jumlah]);
?>