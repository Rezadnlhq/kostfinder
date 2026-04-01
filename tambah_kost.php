<?php 
include 'config.php';

// Proteksi Keamanan: Hanya Admin yang boleh mengakses halaman ini
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    header("Location: dashboard.php"); 
    exit; 
}

if(isset($_POST['simpan'])){
    // 1. TANGKAP DATA AKUN PEMILIK
    $nama_pemilik = mysqli_real_escape_string($conn, $_POST['nama_pemilik']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); 
    
    // 2. TANGKAP DATA PROPERTI KOST
    $nama_kos = mysqli_real_escape_string($conn, $_POST['nama_kos']); 
    $tipe = mysqli_real_escape_string($conn, $_POST['tipe']);
    $harga = (int)$_POST['harga']; // Paksa jadi integer
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']); 
    $fasilitas = mysqli_real_escape_string($conn, $_POST['fasilitas']); 
    $lat = mysqli_real_escape_string($conn, $_POST['latitude']); 
    $lng = mysqli_real_escape_string($conn, $_POST['longitude']);

    // CEK VALIDASI: Pastikan username belum dipakai
    $cek_user = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if(mysqli_num_rows($cek_user) > 0){
        echo "<script>alert('Gagal! Username tersebut sudah dipakai, silakan gunakan username lain.');</script>";
    } else {
        // EKSEKUSI 1: Buat Akun Pemilik di Database
        $sql_user = "INSERT INTO users (nama_lengkap, email, no_hp, username, password, role) 
                     VALUES ('$nama_pemilik', '$email', '$no_hp', '$username', '$password', 'pemilik')";
        
        if(mysqli_query($conn, $sql_user)){
            // Ambil ID User (Pemilik) yang baru saja berhasil dibuat
            $id_pemilik_baru = mysqli_insert_id($conn);
            
            // Fungsi Upload Foto dengan Filter Ekstensi (Keamanan File Upload)
            function uploadBaru($input_name) {
                if(isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0){
                    $ext = strtolower(pathinfo($_FILES[$input_name]['name'], PATHINFO_EXTENSION));
                    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp']; // Hanya format gambar ini yang diizinkan
                    
                    if(in_array($ext, $allowed_ext)) {
                        $new_name = uniqid() . '_' . $input_name . '.' . $ext;
                        move_uploaded_file($_FILES[$input_name]['tmp_name'], 'img/' . $new_name);
                        return $new_name;
                    } else {
                        return 'default.jpg'; // Jika yang diunggah bukan gambar yang diizinkan
                    }
                }
                return 'default.jpg';
            }

            $f1 = uploadBaru('foto_utama'); 
            $f2 = uploadBaru('foto_kamar'); 
            $f3 = uploadBaru('foto_km');

            // EKSEKUSI 2: Masukkan Data Kost yang terhubung dengan ID Pemilik Baru
            $sql_kos = "INSERT INTO kos (id_pemilik, nama_kos, alamat, tipe_kos, harga, fasilitas, latitude, longitude, foto, foto_kamar, foto_km) 
                        VALUES ('$id_pemilik_baru', '$nama_kos', '$alamat', '$tipe', '$harga', '$fasilitas', '$lat', '$lng', '$f1', '$f2', '$f3')";
            
            if(mysqli_query($conn, $sql_kos)){
                echo "<script>alert('SUKSES! Akun Pemilik dan Data Kost Berhasil Ditambahkan.'); window.location='dashboard.php';</script>";
            } else {
                echo "<script>alert('Akun berhasil dibuat, namun gagal menyimpan data kost. Error: " . mysqli_error($conn) . "');</script>";
            }
        } else {
            echo "<script>alert('Gagal membuat akun pemilik. Error: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kost & Akun | KostFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .section-title { font-size: 1.1rem; font-weight: 700; color: #0d6efd; border-bottom: 2px solid #e9ecef; padding-bottom: 8px; margin-bottom: 20px; margin-top: 30px; }
    </style>
</head>
<body class="bg-light py-5">
<div class="container">
    <div class="col-lg-9 mx-auto card border-0 p-4 p-md-5 rounded-4 shadow-sm">
        <h3 class="fw-bold text-primary mb-1">Input Data Kost Baru</h3>
        <p class="text-muted small mb-4">Sistem akan otomatis membuatkan akun login untuk pemilik kost ini.</p>
        
        <form method="POST" enctype="multipart/form-data">
            
            <div class="section-title">1. Buat Akun Pemilik Kost</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Nama Lengkap Pemilik</label>
                    <input type="text" name="nama_pemilik" class="form-control bg-light" placeholder="Bapak/Ibu..." required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Nomor WhatsApp</label>
                    <input type="number" name="no_hp" class="form-control bg-light" placeholder="081234..." required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Alamat Email</label>
                    <input type="email" name="email" class="form-control bg-light" placeholder="email@domain.com" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Username Login</label>
                    <input type="text" name="username" class="form-control bg-light" placeholder="Tanpa spasi" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Password (Kata Sandi)</label>
                    <input type="text" name="password" class="form-control bg-light" value="123456" required>
                    <small class="text-muted" style="font-size: 0.75rem;">Pemilik bisa mengubahnya nanti.</small>
                </div>
            </div>

            <div class="section-title">2. Detail Properti Kost</div>
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold small">Nama Kos</label>
                    <input type="text" name="nama_kos" class="form-control" placeholder="Contoh: Kost Exclusive Melati" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Tipe Kos</label>
                    <select name="tipe" class="form-select">
                        <option value="Putra">Putra</option>
                        <option value="Putri">Putri</option>
                        <option value="Campur">Campur</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold small">Harga Sewa / Bulan (Rp)</label>
                    <input type="number" name="harga" class="form-control" placeholder="800000" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="2" required></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Fasilitas <span class="text-muted fw-normal">(Pisahkan dengan koma)</span></label>
                    <textarea name="fasilitas" class="form-control" rows="2" placeholder="AC, Wifi, Kasur, Lemari..."></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Latitude (Google Maps)</label>
                    <input type="text" name="latitude" class="form-control" placeholder="-6.123456">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Longitude (Google Maps)</label>
                    <input type="text" name="longitude" class="form-control" placeholder="106.123456">
                </div>
            </div>

            <div class="section-title">3. Galeri Foto Properti</div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Foto Tampak Depan</label>
                    <input type="file" name="foto_utama" class="form-control form-control-sm" accept="image/jpeg, image/png, image/webp">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Foto Dalam Kamar</label>
                    <input type="file" name="foto_kamar" class="form-control form-control-sm" accept="image/jpeg, image/png, image/webp">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Foto Kamar Mandi</label>
                    <input type="file" name="foto_km" class="form-control form-control-sm" accept="image/jpeg, image/png, image/webp">
                </div>
            </div>

            <div class="mt-5 pt-3 border-top d-flex justify-content-end">
                <a href="dashboard.php" class="btn btn-light px-4 py-2 rounded-pill me-2 border fw-semibold">Batal</a>
                <button type="submit" name="simpan" class="btn btn-primary px-5 py-2 rounded-pill fw-bold">Simpan & Buat Akun</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>