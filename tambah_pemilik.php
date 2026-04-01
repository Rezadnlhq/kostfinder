<?php 
include 'config.php';

// Pastikan sesi berjalan
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Hanya Admin yang bisa menambah pemilik
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    header("Location: dashboard.php"); 
    exit; 
}

if(isset($_POST['simpan_pemilik'])){
    // Amankan input form
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Validasi username kembar
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if(mysqli_num_rows($cek) > 0){
        echo "<script>alert('Gagal! Username tersebut sudah dipakai.');</script>";
    } else {
        $sql = "INSERT INTO users (nama_lengkap, email, no_hp, username, password, role) 
                VALUES ('$nama', '$email', '$hp', '$username', '$password', 'pemilik')";
        if(mysqli_query($conn, $sql)){
            echo "<script>alert('Akun Pemilik Kos Berhasil Dibuat!'); window.location='dashboard.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pemilik Kos | KostFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light py-5">
<div class="container">
    <div class="col-md-5 mx-auto card border-0 p-5 rounded-4 shadow-sm">
        <h3 class="fw-bold mb-1 text-primary">Daftar Pemilik Kos Baru</h3>
        <p class="text-muted small mb-4">Buatkan akun akses untuk mitra pemilik kost.</p>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap Pemilik</label>
                <input type="text" name="nama_lengkap" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Alamat Email</label>
                <input type="email" name="email" class="form-control" placeholder="email@domain.com" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nomor WhatsApp</label>
                <input type="number" name="no_hp" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Username Login</label>
                <input type="text" name="username" class="form-control" placeholder="Tanpa spasi" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Kata Sandi (Password)</label>
                <input type="text" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
            </div>
            
            <button name="simpan_pemilik" class="btn btn-primary w-100 py-2 rounded-pill fw-bold">Simpan Akun Pemilik</button>
            <a href="dashboard.php" class="btn btn-light w-100 mt-2 py-2 rounded-pill border fw-semibold">Kembali</a>
        </form>
    </div>
</div>
</body>
</html>