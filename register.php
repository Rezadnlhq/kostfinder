<?php 
include 'config.php'; 

if(isset($_POST['register'])){
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password_raw = mysqli_real_escape_string($conn, $_POST['password']);
    
    // 1. VALIDASI EMAIL WAJIB GOOGLE (@gmail.com)
    if (!preg_match('/@gmail\.com$/i', $email)) {
        $error = "Pendaftaran gagal! Anda WAJIB menggunakan email Google (@gmail.com).";
    } else {
        // 2. ENKRIPSI PASSWORD (HASHING TINGKAT TINGGI)
        $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);
        
        // 3. CEK USERNAME & EMAIL KEMBAR
        $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$email'");
        if(mysqli_num_rows($cek) > 0){
            $error = "Username atau Email tersebut sudah terdaftar! Silakan gunakan yang lain.";
        } else {
            // 4. SIMPAN KE DATABASE (dengan role mahasiswa otomatis)
            $sql = "INSERT INTO users (nama_lengkap, email, no_hp, username, password, role) 
                    VALUES ('$nama', '$email', '$hp', '$username', '$password_hashed', 'mahasiswa')";
            if(mysqli_query($conn, $sql)){
                echo "<script>alert('Pendaftaran Berhasil! Silakan Login menggunakan Username dan Password Anda.'); window.location='login.php';</script>";
            } else {
                $error = "Terjadi kesalahan sistem: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun | KostFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card card-login border-0 bg-white p-4 p-md-5 mt-4 mb-4 shadow-lg rounded-4">
                    <div class="text-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; font-size: 2rem;">
                            <i class="bi bi-houses-fill"></i>
                        </div>
                        <h3 class="fw-bold text-primary">KostFinder</h3>
                        <p class="text-muted small">Buat akun pencari kos baru</p>
                    </div>

                    <?php if(isset($error)): ?>
                        <div class='alert alert-danger small text-center py-2 fw-semibold'><i class="bi bi-exclamation-circle me-1"></i> <?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control bg-light" placeholder="Nama Anda" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Email Google (Gmail)</label>
                            <input type="email" name="email" class="form-control bg-light border-primary" placeholder="contoh@gmail.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Nomor WhatsApp</label>
                            <input type="number" name="no_hp" class="form-control bg-light" placeholder="081234567..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Username</label>
                            <input type="text" name="username" class="form-control bg-light" placeholder="Tanpa spasi" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Password Login</label>
                            <input type="password" name="password" class="form-control bg-light border-primary" placeholder="Buat kata sandi yang kuat" required>
                        </div>
                        <button name="register" class="btn btn-primary w-100 mb-3 shadow-sm py-2 fw-bold">Daftar Sekarang</button>
                    </form>
                    
                    <div class="text-center mt-2">
                        <p class="small text-muted mb-0">Sudah punya akun? <a href="login.php" class="text-primary fw-bold text-decoration-none">Masuk di sini</a></p>
                    </div>
                    <div class="text-center mt-3">
                        <a href="index.php" class="text-muted small text-decoration-none"><i class="bi bi-arrow-left"></i> Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>