<?php 
session_start(); // Wajib ditambahkan untuk mendeteksi session login
include 'config.php'; 

function getImg($file) {
    return ($file != '' && $file != 'default.jpg' && file_exists('img/'.$file)) ? 'img/'.$file : 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=600';
}

// Logika untuk fitur Filter Pencarian
$where = [];
if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
    $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);
    $where[] = "(nama_kos LIKE '%$keyword%' OR alamat LIKE '%$keyword%')";
}
if (isset($_GET['tipe']) && $_GET['tipe'] != '') {
    $tipe = mysqli_real_escape_string($conn, $_GET['tipe']);
    $where[] = "tipe_kos = '$tipe'";
}
if (isset($_GET['max_harga']) && $_GET['max_harga'] != '') {
    $max_harga = mysqli_real_escape_string($conn, $_GET['max_harga']);
    $where[] = "harga <= '$max_harga'";
}

$whereClause = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";
$query_string = "SELECT * FROM kos $whereClause ORDER BY id_kos DESC";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KostFinder | Cari Kos Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top p-3" id="mainNav">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="index.php"><i class="bi bi-houses-fill me-2"></i>KostFinder</a>
        
        <button class="navbar-toggler border-0 shadow-none p-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <div class="animated-icon">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="d-flex flex-column flex-lg-row align-items-lg-center ms-auto mt-3 mt-lg-0 gap-3">
                
                <?php if(isset($_SESSION['role'])): ?>
                    <?php if($_SESSION['role'] == 'mahasiswa'): ?>
                        <a href="keluhan.php" class="text-white text-decoration-none fw-bold me-lg-2"><i class="bi bi-chat-dots"></i> Kirim Keluhan</a>
                    <?php else: ?>
                        <a href="dashboard.php" class="text-white text-decoration-none fw-bold position-relative me-lg-2">
                            <i class="bi bi-bell"></i> Keluhan Masuk
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="badge-notif" style="display:none;">0</span>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if(isset($_SESSION['nama'])): ?>
                    <a href="dashboard.php" class="btn btn-light btn-sm px-4 rounded-pill fw-bold text-center">Dashboard Saya</a>
                    <a href="logout.php" class="btn btn-outline-light btn-sm px-4 rounded-pill text-center">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-light px-4 rounded-pill fw-bold shadow-sm text-center">Masuk / Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<section class="hero text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Temukan Kos Impianmu</h1>
        <p class="lead fw-light">Cek fasilitas lengkap, lihat rute via Maps, dan Booking langsung.</p>
    </div>
</section>

<div class="container mb-5">
    <div class="filter-box reveal-on-scroll">
        <form class="row g-3 align-items-center" method="GET" action="index.php">
            <div class="col-md-5">
                <input type="text" name="keyword" class="form-control" placeholder="Cari lokasi (ex: Majalengka)..." value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
            </div>
            <div class="col-md-3">
                <select name="tipe" class="form-select">
                    <option value="">Semua Tipe Kos</option>
                    <option value="Putra" <?= (isset($_GET['tipe']) && $_GET['tipe'] == 'Putra') ? 'selected' : '' ?>>Putra</option>
                    <option value="Putri" <?= (isset($_GET['tipe']) && $_GET['tipe'] == 'Putri') ? 'selected' : '' ?>>Putri</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="max_harga" class="form-control" placeholder="Harga Max" value="<?= isset($_GET['max_harga']) ? htmlspecialchars($_GET['max_harga']) : '' ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 shadow-sm"><i class="bi bi-search me-1"></i> Filter</button>
            </div>
        </form>
    </div>

    <h3 class="fw-bold mt-5 mb-4 reveal-on-scroll">Rekomendasi Kos Terbaru</h3>

    <div class="row g-4">
        <?php
        $q = mysqli_query($conn, $query_string);
        
        if(mysqli_num_rows($q) > 0):
            while($d = mysqli_fetch_assoc($q)): 
        ?>
        <div class="col-12 col-md-6 col-lg-4 reveal-on-scroll">
            <div class="card card-kos h-100">
                <div class="card-img-wrapper"><img src="<?= getImg($d['foto']) ?>" class="card-img-top" alt="Foto Kos"></div>
                <div class="card-body p-4 d-flex flex-column">
                    <div class="mb-2"><span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill"><?= htmlspecialchars($d['tipe_kos']) ?></span></div>
                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($d['nama_kos']) ?></h5>
                    <p class="text-muted small mb-4"><i class="bi bi-geo-alt-fill text-danger me-1"></i> <?= htmlspecialchars(substr($d['alamat'], 0, 40)) ?>...</p>
                    <div class="mt-auto border-top pt-3 d-flex justify-content-between align-items-center">
                        <div><span class="text-primary fw-bold fs-5">Rp <?= number_format($d['harga'], 0, ',', '.') ?></span></div>
                        <a href="detail_kost.php?id=<?= $d['id_kos'] ?>" class="btn btn-primary rounded-pill px-4">Detail</a>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            endwhile; 
        else: 
        ?>
        <div class="col-12 text-center py-5">
            <i class="bi bi-search display-1 text-muted opacity-50 mb-3 d-block"></i>
            <h5 class="text-muted">Kos yang Anda cari belum tersedia.</h5>
            <p class="text-muted small">Coba sesuaikan ulang filter pencarian Anda.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>

<?php if(isset($_SESSION['role']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'pemilik')): ?>
<script>
    // Fungsi ini akan berjalan setiap 3 detik (3000 ms) di background
    setInterval(function() {
        fetch('api_notif_keluhan.php')
            .then(response => response.json())
            .then(data => {
                let badge = document.getElementById('badge-notif');
                if (data.jumlah_baru > 0) {
                    badge.style.display = 'inline-block'; // Tampilkan badge
                    badge.innerText = data.jumlah_baru;   // Ubah angka sesuai keluhan baru
                } else {
                    badge.style.display = 'none'; // Sembunyikan jika tidak ada keluhan baru
                }
            })
            .catch(error => console.error('Error fetching notif:', error));
    }, 3000); 
</script>
<?php endif; ?>

</body>
</html>