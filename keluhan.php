<?php 
session_start();
include 'config.php';

// Pastikan hanya mahasiswa yang bisa mengakses halaman ini
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'mahasiswa') { 
    header("Location: login.php"); 
    exit; 
}

$id_user = $_SESSION['id_user'];

// Proses jika form disubmit
if(isset($_POST['kirim_keluhan'])) {
    $id_kos = mysqli_real_escape_string($conn, $_POST['id_kos']);
    $isi_keluhan = mysqli_real_escape_string($conn, $_POST['isi_keluhan']);
    
    // Insert ke database
    $query = "INSERT INTO keluhan (id_mahasiswa, id_kos, isi_keluhan, status_baca, tgl_keluhan) 
              VALUES ('$id_user', '$id_kos', '$isi_keluhan', 0, NOW())";
              
    if(mysqli_query($conn, $query)) {
        $status = 'sukses';
    } else {
        $status = 'gagal';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Keluhan | KostFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f4f7f6; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-arrow-left me-2"></i>Kembali ke Beranda</a>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 mt-3">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-chat-dots-fill text-primary display-4"></i>
                        <h3 class="fw-bold mt-2">Kirim Keluhan</h3>
                        <p class="text-muted small">Keluhan Anda akan langsung dikirimkan ke pemilik kos terkait.</p>
                    </div>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Kos</label>
                            <select name="id_kos" class="form-select" required>
                                <option value="">-- Pilih Kos --</option>
                                <?php
                                // Mengambil semua data kost yang tersedia di database
                                $q_kos = mysqli_query($conn, "SELECT id_kos, nama_kos FROM kos ORDER BY nama_kos ASC");
                                while($kos = mysqli_fetch_assoc($q_kos)) {
                                    echo "<option value='{$kos['id_kos']}'>{$kos['nama_kos']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Detail Keluhan</label>
                            <textarea name="isi_keluhan" class="form-control" rows="5" placeholder="Tuliskan keluhan atau masalah yang Anda alami di sini..." required></textarea>
                        </div>

                        <button type="submit" name="kirim_keluhan" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">
                            <i class="bi bi-send-fill me-2"></i>Kirim Keluhan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php if(isset($status)): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if($status == 'sukses'): ?>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Keluhan Anda berhasil dikirimkan.',
                icon: 'success',
                confirmButtonColor: '#0d6efd'
            }).then(() => {
                window.location.href = 'dashboard.php'; // Arahkan ke dashboard agar bisa lihat riwayat
            });
        <?php else: ?>
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat mengirim keluhan.',
                icon: 'error',
                confirmButtonColor: '#dc3545'
            });
        <?php endif; ?>
    });
</script>
<?php endif; ?>

</body>
</html>