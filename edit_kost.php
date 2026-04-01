<?php 
include 'config.php';

// Cek Sesi & Proteksi
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['role']) || $_SESSION['role'] == 'mahasiswa') { header("Location: index.php"); exit; }

// Amankan ID di URL dengan tipe data (int)
$id = (int)$_GET['id'];
$q = mysqli_query($conn, "SELECT * FROM kos WHERE id_kos = '$id'");
$d = mysqli_fetch_assoc($q);

// Jika kost tidak ditemukan
if(!$d) {
    echo "<script>alert('Data properti tidak ditemukan!'); window.location='dashboard.php';</script>";
    exit;
}

// LOGIKA PROTEKSI: Pastikan Pemilik hanya bisa edit kost miliknya sendiri
if($_SESSION['role'] == 'pemilik' && $d['id_pemilik'] != $_SESSION['id_user']) {
    echo "<script>alert('AKSES DITOLAK! Anda tidak memiliki hak untuk mengedit kost ini.'); window.location='dashboard.php';</script>";
    exit;
}

if(isset($_POST['update'])){
    // Amankan input form
    $nama = mysqli_real_escape_string($conn, $_POST['nama']); 
    $harga = (int)$_POST['harga']; 
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $fasilitas = mysqli_real_escape_string($conn, $_POST['fasilitas']); 
    $lat = mysqli_real_escape_string($conn, $_POST['latitude']); 
    $lng = mysqli_real_escape_string($conn, $_POST['longitude']);

    // Fungsi Upload Foto dengan Validasi Format (Keamanan)
    function uploadFoto($input_name, $old_foto) {
        if(isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0){
            $ext = strtolower(pathinfo($_FILES[$input_name]['name'], PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'webp']; // Whitelist ekstensi
            
            if(in_array($ext, $allowed_ext)) {
                $new_name = uniqid() . '_' . $input_name . '.' . $ext;
                move_uploaded_file($_FILES[$input_name]['tmp_name'], 'img/' . $new_name);
                return $new_name;
            } else {
                echo "<script>alert('Format foto $input_name tidak valid! Hanya JPG, PNG, WEBP.');</script>";
                return $old_foto;
            }
        }
        return $old_foto;
    }

    $f1 = uploadFoto('foto_utama', $d['foto']);
    $f2 = uploadFoto('foto_kamar', $d['foto_kamar']);
    $f3 = uploadFoto('foto_km', $d['foto_km']);

    $sql = "UPDATE kos SET nama_kos='$nama', harga='$harga', alamat='$alamat', fasilitas='$fasilitas', latitude='$lat', longitude='$lng', foto='$f1', foto_kamar='$f2', foto_km='$f3' WHERE id_kos='$id'";
    
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Data, Lokasi & Foto berhasil diperbarui!'); window.location='dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui: " . mysqli_error($conn) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Properti | KostFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light py-5">
<div class="container">
    <div class="col-md-8 mx-auto card border-0 p-4 p-md-5 rounded-4 shadow-sm">
        <h3 class="fw-bold mb-4 text-primary">Edit Properti & Foto</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Kos</label>
                    <input type="text" name="nama" class="form-control" value="<?= $d['nama_kos'] ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Harga/Bln (Rp)</label>
                    <input type="number" name="harga" class="form-control" value="<?= $d['harga'] ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="2" required><?= $d['alamat'] ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Fasilitas <small class="text-muted">(Koma: AC, Kasur)</small></label>
                    <textarea name="fasilitas" class="form-control" rows="2"><?= $d['fasilitas'] ?></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Latitude</label>
                    <input type="text" name="latitude" class="form-control" value="<?= $d['latitude'] ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Longitude</label>
                    <input type="text" name="longitude" class="form-control" value="<?= $d['longitude'] ?>">
                </div>
                
                <div class="col-12 mt-4"><h5 class="fw-bold border-bottom pb-2">Galeri Foto</h5></div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Foto Depan</label>
                    <input type="file" name="foto_utama" class="form-control form-control-sm" accept="image/jpeg, image/png, image/webp">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Dalam Kamar</label>
                    <input type="file" name="foto_kamar" class="form-control form-control-sm" accept="image/jpeg, image/png, image/webp">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Kamar Mandi</label>
                    <input type="file" name="foto_km" class="form-control form-control-sm" accept="image/jpeg, image/png, image/webp">
                </div>
            </div>
            <div class="mt-4 pt-3 border-top">
                <button name="update" class="btn btn-primary px-5 py-2 rounded-pill fw-bold">Simpan Perubahan</button>
                <a href="dashboard.php" class="btn btn-light px-4 py-2 rounded-pill ms-2 fw-semibold border">Batal</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>