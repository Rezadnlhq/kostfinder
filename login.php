<?php 
include 'config.php'; 
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Redirect jika sudah login
if(isset($_SESSION['role'])){ header("Location: dashboard.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | KostFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card card-login border-0 bg-white p-4 p-md-5 shadow-lg rounded-4">
                    <div class="text-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; font-size: 2rem;">
                            <i class="bi bi-houses-fill"></i>
                        </div>
                        <h3 class="fw-bold text-primary">KostFinder</h3>
                        <p class="text-muted small">Silakan masuk ke akun Anda</p>
                    </div>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Username</label>
                            <input type="text" name="user" class="form-control bg-light" placeholder="Masukkan username" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Password</label>
                            <input type="password" name="pass" class="form-control bg-light" placeholder="Masukkan sandi" required>
                        </div>
                        <button name="login" class="btn btn-primary w-100 mb-3 shadow-sm py-2 fw-bold">Masuk</button>
                    </form>
                    
                    <?php
                    if(isset($_POST['login'])){
                        $u = mysqli_real_escape_string($conn, $_POST['user']); 
                        $p = $_POST['pass'];
                        
                        // Cari user berdasarkan username saja
                        $q = mysqli_query($conn, "SELECT * FROM users WHERE username='$u'");
                        
                        if(mysqli_num_rows($q) > 0){
                            $data = mysqli_fetch_assoc($q);
                            
                            // VALIDASI HASH: Cek password terenkripsi ATAU password lama (plaintext)
                            if(password_verify($p, $data['password']) || $p === $data['password']) {
                                $_SESSION['id_user'] = $data['id_user']; 
                                $_SESSION['nama'] = $data['nama_lengkap']; 
                                $_SESSION['role'] = $data['role'];
                                
                                echo "<script>window.location='dashboard.php';</script>";
                                exit;
                            } else {
                                echo "<div class='alert alert-danger mt-3 small text-center py-2 fw-semibold'><i class='bi bi-x-circle me-1'></i> Password Anda salah!</div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger mt-3 small text-center py-2 fw-semibold'><i class='bi bi-person-x me-1'></i> Username tidak ditemukan!</div>";
                        }
                    }
                    ?>
                    
                    <div class="text-center mt-2">
                        <p class="small text-muted mb-0">Belum punya akun? <a href="register.php" class="text-primary fw-bold text-decoration-none">Daftar sekarang</a></p>
                    </div>
                    <div class="text-center mt-4">
                        <a href="index.php" class="text-muted small text-decoration-none"><i class="bi bi-arrow-left"></i> Kembali ke Web Utama</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>