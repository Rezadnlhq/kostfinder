<?php
$conn = mysqli_connect("localhost", "root", "", "db_kostfinder");
if (!$conn) die("Koneksi Gagal: " . mysqli_connect_error());

// Cek apakah session sudah berjalan sebelum memulainya
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>