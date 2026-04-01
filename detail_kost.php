<?php include 'config.php'; 
if(!isset($_GET['id'])) header("Location: index.php");
$id = $_GET['id'];
$q = mysqli_query($conn, "SELECT * FROM kos WHERE id_kos = '$id'");
$d = mysqli_fetch_assoc($q);

// Hitung Statistik Rating
$q_rating = mysqli_query($conn, "SELECT AVG(bintang) as rata, COUNT(*) as total FROM rating WHERE id_kos = '$id'");
$res_rating = mysqli_fetch_assoc($q_rating);
$rata_rating = round($res_rating['rata'], 1);
$total_ulasan = $res_rating['total'];

function getImgUrl($file){
    return ($file != '' && $file != 'default.jpg' && file_exists('img/'.$file)) ? 'img/'.$file : 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=600';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $d['nama_kos'] ?> | KostFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .star-rating { color: #ffc107; font-size: 1.2rem; }
        .progress { height: 8px; border-radius: 10px; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary p-3 shadow-sm position-static">
    <div class="container"><a class="navbar-brand fw-bold fs-4" href="index.php"><i class="bi bi-arrow-left me-2"></i>Kembali</a></div>
</nav>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 p-4 p-md-5">
                <div class="mb-4 text-center">
                    <img src="<?= getImgUrl($d['foto']) ?>" class="main-img mb-3 shadow-sm" alt="Foto Depan">
                    <div class="row g-3">
                        <div class="col-6"><img src="<?= getImgUrl($d['foto_kamar']) ?>" class="gallery-img shadow-sm" alt="Kamar"></div>
                        <div class="col-6"><img src="<?= getImgUrl($d['foto_km']) ?>" class="gallery-img shadow-sm" alt="KM"></div>
                    </div>
                </div>

                <h2 class="fw-bold mb-1"><?= $d['nama_kos'] ?></h2>
                <div class="mb-3">
                    <span class="star-rating"><i class="bi bi-star-fill"></i> <?= $rata_rating ?: '0' ?></span>
                    <span class="text-muted small ms-1">(<?= $total_ulasan ?> Ulasan)</span>
                </div>
                <p class="text-muted"><i class="bi bi-geo-alt-fill text-danger me-2"></i> <?= $d['alamat'] ?></p>

                <hr class="my-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-stars text-warning me-2"></i>Fasilitas Tersedia</h5>
                <div>
                    <?php 
                    $fasilitas = explode(',', $d['fasilitas']);
                    foreach($fasilitas as $f): if(trim($f) != ""): ?>
                        <span class="fasilitas-badge"><i class="bi bi-check-circle-fill text-success me-1"></i> <?= trim($f) ?></span>
                    <?php endif; endforeach; ?>
                </div>

                <hr class="my-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-map text-info me-2"></i>Lokasi Kos</h5>
                <div class="map-container shadow-sm mb-3">
                    <?php if(!empty($d['latitude']) && !empty($d['longitude'])): ?>
                        <iframe src="https://maps.google.com/maps?q=<?= $d['latitude'] ?>,<?= $d['longitude'] ?>&hl=id&z=15&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    <?php else: ?>
                        <div class="d-flex h-100 align-items-center justify-content-center text-muted">Koordinat belum diatur pemilik.</div>
                    <?php endif; ?>
                </div>
                <?php if(!empty($d['latitude']) && !empty($d['longitude'])): ?>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $d['latitude'] ?>,<?= $d['longitude'] ?>" target="_blank" class="btn btn-success w-100 rounded-pill py-2 fw-bold">
                        <i class="bi bi-geo-alt-fill me-2"></i>Buka Petunjuk Arah via Google Maps
                    </a>
                <?php endif; ?>

                <hr class="my-5">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <h1 class="display-3 fw-bold text-primary"><?= $rata_rating ?: '0' ?></h1>
                        <div class="star-rating mb-2">
                            <?php for($i=1; $i<=5; $i++) echo ($i <= $rata_rating) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>'; ?>
                        </div>
                        <p class="text-muted">Rata-rata Rating</p>
                    </div>
                    <div class="col-md-8">
                        <?php 
                        for($i=5; $i>=1; $i--){
                            $count_q = mysqli_query($conn, "SELECT COUNT(*) as jml FROM rating WHERE id_kos='$id' AND bintang='$i'");
                            $count = mysqli_fetch_assoc($count_q)['jml'];
                            $persen = ($total_ulasan > 0) ? ($count / $total_ulasan) * 100 : 0;
                        ?>
                        <div class="d-flex align-items-center mb-1">
                            <span class="small me-2" style="width: 20px;"><?= $i ?></span>
                            <i class="bi bi-star-fill text-warning me-2" style="font-size: 0.8rem;"></i>
                            <div class="progress flex-grow-1">
                                <div class="progress-bar bg-primary" style="width: <?= $persen ?>%"></div>
                            </div>
                            <span class="small ms-2 text-muted"><?= $count ?></span>
                        </div>
                        <?php } ?>
                    </div>
                </div>

                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'mahasiswa'): ?>
                <div class="card bg-light border-0 rounded-4 p-4 mt-4">
                    <h6 class="fw-bold mb-3">Berikan Ulasan / Keluhan</h6>
                    <form action="proses_rating.php" method="POST">
                        <input type="hidden" name="id_kos" value="<?= $id ?>">
                        <div class="mb-3">
                            <label class="form-label small">Beri Bintang</label>
                            <select name="bintang" class="form-select border-0 shadow-sm" required>
                                <option value="5">⭐⭐⭐⭐⭐ (Sangat Baik)</option>
                                <option value="4">⭐⭐⭐⭐ (Baik)</option>
                                <option value="3">⭐⭐⭐ (Cukup)</option>
                                <option value="2">⭐⭐ (Kurang)</option>
                                <option value="1">⭐ (Sangat Kurang)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <textarea name="ulasan" class="form-control border-0 shadow-sm" rows="3" placeholder="Ceritakan pengalaman Anda atau sampaikan keluhan..." required></textarea>
                        </div>
                        <button type="submit" name="kirim_rating" class="btn btn-primary btn-sm rounded-pill px-4">Kirim Ulasan</button>
                    </form>
                </div>
                <?php endif; ?>

                <div class="mt-5">
                    <h6 class="fw-bold mb-4">Ulasan Pengguna</h6>
                    <?php 
                    $q_list = mysqli_query($conn, "SELECT r.*, u.nama_lengkap FROM rating r JOIN users u ON r.id_user=u.id_user WHERE r.id_kos='$id' ORDER BY r.tgl_rating DESC");
                    while($row = mysqli_fetch_assoc($q_list)):
                    ?>
                    <div class="border-bottom pb-4 mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold"><?= $row['nama_lengkap'] ?></span>
                            <span class="small text-muted"><?= date('d/m/Y', strtotime($row['tgl_rating'])) ?></span>
                        </div>
                        <div class="star-rating mb-2" style="font-size: 0.9rem;">
                            <?php for($i=1; $i<=5; $i++) echo ($i <= $row['bintang']) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>'; ?>
                        </div>
                        <p class="small text-secondary mb-2"><?= $row['ulasan'] ?></p>
                        
                        <?php if(!empty($row['balasan_pemilik'])): ?>
                        <div class="bg-light p-3 rounded-3 mt-2 ms-3 border-start border-primary border-4">
                            <small class="fw-bold text-primary d-block mb-1">Tanggapan Pengelola:</small>
                            <p class="small mb-0 fst-italic">"<?= $row['balasan_pemilik'] ?>"</p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 20px;">
                <h3 class="fw-bold text-primary mb-0">Rp <?= number_format($d['harga']) ?></h3>
                <p class="text-muted">per bulan</p>
                <hr>
                
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'mahasiswa'): ?>
                    <p class="small text-muted mb-3">Lengkapi data diri dan ajukan pemesanan kamar.</p>
                    <button type="button" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalBooking">
                        Ajukan Booking Sekarang
                    </button>
                <?php elseif(isset($_SESSION['role'])): ?>
                    <button class="btn btn-secondary w-100 py-3 rounded-pill" disabled>Hanya Mahasiswa yang bisa Booking</button>
                <?php else: ?>
                    <div class="alert alert-info small text-center rounded-4">Silakan login sebagai Mahasiswa untuk melakukan Booking.</div>
                    <a href="login.php" class="btn btn-outline-primary w-100 py-2 rounded-pill fw-bold">Login Disini</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'mahasiswa'): 
    $id_user_login = $_SESSION['id_user'];
    $q_user = mysqli_query($conn, "SELECT * FROM users WHERE id_user='$id_user_login'");
    $d_user = mysqli_fetch_assoc($q_user);
?>
<div class="modal fade" id="modalBooking" tabindex="-1">
    <div class="modal-dialog">
        <form action="proses_booking.php" method="POST" class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="modal-title fw-bold text-primary">Lengkapi Data Pemesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="id_kos" value="<?= $id ?>">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="<?= $d_user['nama_lengkap'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nomor WhatsApp (HP)</label>
                    <input type="number" name="no_hp" class="form-control" value="<?= $d_user['no_hp'] ?>" placeholder="Contoh: 081234567890" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Pesan / Pertanyaan Tambahan</label>
                    <textarea name="catatan" class="form-control" rows="3" placeholder="Tanyakan ketersediaan kamar atau hal lainnya kepada pemilik..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="submit" name="booking" class="btn btn-primary w-100 py-2 rounded-pill fw-bold">Kirim Permintaan Booking</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>