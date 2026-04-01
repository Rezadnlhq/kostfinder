<?php 
include 'config.php'; 
// Cek apakah session sudah berjalan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['role'])) { header("Location: login.php"); exit; }
$role = $_SESSION['role']; 
$id_user = $_SESSION['id_user'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | KostFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="d-flex">
    <div class="sidebar d-flex flex-column" id="sidebar">
        <div class="sidebar-brand">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px; font-size: 2rem;">
                <i class="bi bi-houses-fill"></i>
            </div>
            <h5 class="fw-bold text-primary mb-0">KostFinder</h5>
            <small class="text-muted fw-bold mt-1 text-uppercase">PANEL <?= $role ?></small>
        </div>
        
        <div class="p-0 mt-3 flex-grow-1">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                
                <?php if($role == 'admin'): ?>
                <button class="nav-link active text-start" data-bs-toggle="pill" data-bs-target="#tab-pemilik" type="button" role="tab">
                    <i class="bi bi-people-fill"></i> Akun Pemilik
                </button>
                <?php endif; ?>

                <?php if($role == 'admin' || $role == 'pemilik'): ?>
                <button class="nav-link text-start <?= ($role == 'pemilik') ? 'active' : '' ?>" data-bs-toggle="pill" data-bs-target="#tab-kost" type="button" role="tab">
                    <i class="bi bi-building"></i> Kelola Kost
                </button>
                <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#tab-booking" type="button" role="tab">
                    <i class="bi bi-journal-check"></i> Pesanan Booking
                </button>
                <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#tab-keluhan" type="button" role="tab">
                    <i class="bi bi-exclamation-octagon"></i> Keluhan Masuk
                </button>
                <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#tab-ulasan" type="button" role="tab">
                    <i class="bi bi-star-fill"></i> Ulasan & Rating
                </button>
                <?php endif; ?>

                <?php if($role == 'mahasiswa'): ?>
                <button class="nav-link active text-start" data-bs-toggle="pill" data-bs-target="#tab-mybooking" type="button" role="tab">
                    <i class="bi bi-clock-history"></i> Riwayat Booking
                </button>
                <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#tab-mykeluhan" type="button" role="tab">
                    <i class="bi bi-chat-dots"></i> Riwayat Keluhan
                </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="p-3 border-top">
            <a href="index.php" class="btn btn-outline-primary w-100 mb-2 rounded-pill"><i class="bi bi-globe me-2"></i>Web Utama</a>
            <a href="logout.php" class="btn btn-danger w-100 rounded-pill"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
        </div>
    </div>

    <div class="main-wrapper w-100">
        
        <div class="top-header shadow-sm">
            <div>
                <button class="btn text-white d-md-none me-2" id="sidebarToggle"><i class="bi bi-list fs-3"></i></button>
                <h4 class="mb-1 fw-bold">Hai, Selamat Datang</h4>
                <p class="mb-0 opacity-75 small">
                    <i class="bi bi-person-circle me-1"></i> <?= $_SESSION['nama'] ?> | 
                    <i class="bi bi-calendar3 me-1"></i> <?= date('l, d F Y') ?>
                </p>
            </div>
            <div class="d-none d-md-block text-end">
                <h5 class="mb-0 fw-bold">KostFinder System</h5>
                <small class="opacity-75">www.kostfinger.com</small>
            </div>
        </div>

        <div class="content-container">
            <div class="tab-content" id="v-pills-tabContent">

                <?php if($role == 'admin'): ?>
                <div class="tab-pane fade show active" id="tab-pemilik" role="tabpanel">
                    <div class="custom-card mb-4">
                        <div class="custom-card-header d-flex justify-content-between align-items-center">
                            <span>Daftar Akun Pemilik Kost</span>
                            <a href="tambah_pemilik.php" class="btn btn-primary btn-sm rounded-pill px-3"><i class="bi bi-plus-lg me-1"></i> Tambah Akun</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light"><tr><th class="ps-4">Nama & Email</th><th>No WhatsApp</th><th>Username</th><th class="text-end pe-4">Aksi</th></tr></thead>
                                    <tbody>
                                        <?php
                                        $q_users = mysqli_query($conn, "SELECT * FROM users WHERE role='pemilik'");
                                        while($u = mysqli_fetch_assoc($q_users)): ?>
                                        <tr>
                                            <td class="ps-4"><span class="fw-bold"><?= $u['nama_lengkap'] ?></span><br><small class="text-muted"><?= $u['email'] ?></small></td>
                                            <td><?= $u['no_hp'] ?></td>
                                            <td><span class="badge bg-secondary rounded-pill"><?= $u['username'] ?></span></td>
                                            <td class="text-end pe-4">
                                                <a href="proses_hapus_user.php?id=<?= $u['id_user'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Hapus akun ini beserta semua propertinya?')"><i class="bi bi-trash"></i> Hapus</a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($role == 'admin' || $role == 'pemilik'): ?>
                <div class="tab-pane fade <?= ($role == 'pemilik') ? 'show active' : '' ?>" id="tab-kost" role="tabpanel">
                    <div class="custom-card mb-4">
                        <div class="custom-card-header d-flex justify-content-between align-items-center">
                            <span>Data Properti Kost</span>
                            <?php if($role == 'admin'): ?><a href="tambah_kost.php" class="btn btn-primary btn-sm rounded-pill px-3"><i class="bi bi-plus-lg me-1"></i> Tambah Kost</a><?php endif; ?>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light"><tr><th class="ps-4">Nama Kos</th><th>Tipe</th><th>Harga/Bulan</th><th class="text-end pe-4">Aksi</th></tr></thead>
                                    <tbody>
                                        <?php
                                        $where_clause = ($role == 'pemilik') ? " WHERE id_pemilik='$id_user'" : "";
                                        $q_kos = mysqli_query($conn, "SELECT * FROM kos" . $where_clause); 
                                        while($dk = mysqli_fetch_assoc($q_kos)): ?>
                                        <tr>
                                            <td class="ps-4 fw-semibold text-primary"><?= $dk['nama_kos'] ?></td>
                                            <td><span class="badge bg-info text-dark rounded-pill"><?= $dk['tipe_kos'] ?></span></td>
                                            <td class="fw-bold">Rp <?= number_format($dk['harga']) ?></td>
                                            <td class="text-end pe-4">
                                                <a href="edit_kost.php?id=<?= $dk['id_kos'] ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">Edit</a>
                                                <?php if($role == 'admin'): ?>
                                                    <a href="proses_hapus_kost.php?id=<?= $dk['id_kos'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3 ms-1" onclick="return confirm('Hapus properti ini?')"><i class="bi bi-trash"></i></a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-booking" role="tabpanel">
                    <div class="custom-card mb-4">
                        <div class="custom-card-header">Permintaan Pesanan (Booking)</div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light"><tr><th class="ps-4">Pemesan</th><th>Properti Kost</th><th>Status</th><th class="text-end pe-4">Aksi</th></tr></thead>
                                    <tbody>
                                        <?php
                                        $q_book = mysqli_query($conn, "SELECT b.*, u.nama_lengkap, u.no_hp, k.nama_kos FROM booking b JOIN users u ON b.id_mahasiswa=u.id_user JOIN kos k ON b.id_kos=k.id_kos " . ($role == 'pemilik' ? "WHERE k.id_pemilik='$id_user'" : "ORDER BY b.id_booking DESC"));
                                        while($b = mysqli_fetch_assoc($q_book)): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <span class="fw-bold d-block"><?= $b['nama_lengkap'] ?></span>
                                                <small class="text-muted"><i class="bi bi-whatsapp text-success me-1"></i><?= $b['no_hp'] ?></small>
                                                <?php if(!empty($b['catatan'])): ?><div class="small bg-light p-2 mt-2 rounded border-start border-primary border-3 fst-italic">"<?= $b['catatan'] ?>"</div><?php endif; ?>
                                            </td>
                                            <td class="fw-semibold"><?= $b['nama_kos'] ?></td>
                                            <td><span class="badge bg-<?= ($b['status']=='Disetujui'?'success':($b['status']=='Dibatalkan'?'danger':'warning text-dark')) ?> rounded-pill px-3"><?= $b['status'] ?></span></td>
                                            <td class="text-end pe-4">
                                                <?php if($b['status'] == 'Pending'): ?>
                                                    <a href="proses_terima.php?id=<?= $b['id_booking'] ?>&hp=<?= $b['no_hp'] ?>&kos=<?= urlencode($b['nama_kos']) ?>" class="btn btn-sm btn-success rounded-pill px-3 mb-1">Terima</a>
                                                    <a href="proses_tolak.php?id=<?= $b['id_booking'] ?>" class="btn btn-sm btn-danger rounded-pill px-3 mb-1">Tolak</a>
                                                <?php else: ?>
                                                    <a href="proses_hapus.php?id=<?= $b['id_booking'] ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="return confirm('Hapus riwayat ini?')"><i class="bi bi-trash"></i></a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-keluhan" role="tabpanel">
                    <div class="custom-card mb-4">
                        <div class="custom-card-header d-flex justify-content-between align-items-center">
                            <span class="fw-bold"><i class="bi bi-exclamation-octagon me-2 text-danger"></i>Daftar Keluhan Masuk</span>
                        </div>
                        <div class="card-body p-4 bg-light">
                            <div class="row g-4">
                                <?php 
                                $q_keluhan = mysqli_query($conn, "SELECT k.*, u.nama_lengkap, ks.nama_kos FROM keluhan k JOIN users u ON k.id_mahasiswa=u.id_user JOIN kos ks ON k.id_kos=ks.id_kos " . ($role == 'pemilik' ? "WHERE ks.id_pemilik='$id_user'" : "ORDER BY k.tgl_keluhan DESC"));
                                if(mysqli_num_rows($q_keluhan) > 0):
                                    while($kl = mysqli_fetch_assoc($q_keluhan)):
                                ?>
                                <div class="col-md-6 col-xl-4">
                                    <div class="card border-0 shadow-sm h-100 border-top border-<?= $kl['status_baca'] == 0 ? 'danger' : 'success' ?> border-4 rounded-4">
                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="fw-bold mb-0 text-primary text-truncate pe-2"><?= $kl['nama_kos'] ?></h6>
                                                <span class="badge bg-<?= $kl['status_baca'] == 0 ? 'danger' : 'success' ?> rounded-pill small"><?= $kl['status_baca'] == 0 ? 'Baru' : 'Direspon' ?></span>
                                            </div>
                                            <div class="mb-4">
                                                <small class="text-muted d-block mb-2"><i class="bi bi-person-circle me-1"></i> <?= $kl['nama_lengkap'] ?></small>
                                                <div class="p-3 bg-light rounded-3 border">
                                                    <p class="small mb-0 fst-italic">"<?= $kl['isi_keluhan'] ?>"</p>
                                                </div>
                                            </div>
                                            <form action="proses_balas_keluhan.php" method="POST" class="mt-auto">
                                                <input type="hidden" name="id_keluhan" value="<?= $kl['id_keluhan'] ?>">
                                                <label class="form-label small fw-bold">Tanggapan Anda:</label>
                                                <div class="input-group">
                                                    <input type="text" name="balasan" class="form-control form-control-sm border-primary" placeholder="Ketik balasan..." value="<?= isset($kl['balasan_pemilik']) ? $kl['balasan_pemilik'] : '' ?>" required>
                                                    <button class="btn btn-primary btn-sm px-3" type="submit" name="kirim_balasan_keluhan"><i class="bi bi-send-fill"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                    endwhile; 
                                else:
                                ?>
                                <div class="col-12 text-center py-5">
                                    <p class="text-muted mb-0">Belum ada keluhan masuk.</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-ulasan" role="tabpanel">
                    <div class="custom-card mb-4">
                        <div class="custom-card-header d-flex justify-content-between align-items-center">
                            <span class="fw-bold"><i class="bi bi-star-fill me-2 text-warning"></i>Ulasan & Rating Pengguna</span>
                        </div>
                        <div class="card-body p-4 bg-light">
                            <div class="row g-4">
                                <?php 
                                $q_rat = mysqli_query($conn, "SELECT r.*, u.nama_lengkap, k.nama_kos FROM rating r JOIN users u ON r.id_user=u.id_user JOIN kos k ON r.id_kos=k.id_kos " . ($role == 'pemilik' ? "WHERE k.id_pemilik='$id_user'" : "ORDER BY r.tgl_rating DESC"));
                                if(mysqli_num_rows($q_rat) > 0):
                                    while($r = mysqli_fetch_assoc($q_rat)):
                                ?>
                                <div class="col-md-6 col-xl-4">
                                    <div class="card border-0 shadow-sm h-100 rounded-4">
                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between mb-2">
                                                <h6 class="fw-bold text-primary mb-0 text-truncate pe-2"><?= $r['nama_kos'] ?></h6>
                                                <span class="text-warning small"><?php for($i=1;$i<=$r['bintang'];$i++) echo "★"; ?></span>
                                            </div>
                                            <small class="text-muted d-block mb-3"><i class="bi bi-person-circle me-1"></i> <?= $r['nama_lengkap'] ?></small>
                                            
                                            <div class="p-3 bg-white rounded-3 border mb-4">
                                                <p class="small fst-italic mb-0">"<?= $r['ulasan'] ?>"</p>
                                            </div>

                                            <form action="proses_balas_rating.php" method="POST" class="mt-auto">
                                                <input type="hidden" name="id_rating" value="<?= $r['id_rating'] ?>">
                                                <div class="input-group">
                                                    <input type="text" name="balasan" class="form-control form-control-sm" placeholder="Balas ulasan..." value="<?= $r['balasan_pemilik'] ?>">
                                                    <button class="btn btn-outline-primary btn-sm px-3" type="submit" name="kirim_balasan">Balas</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                    endwhile; 
                                else:
                                ?>
                                <div class="col-12 text-center py-5">
                                    <p class="text-muted mb-0">Belum ada ulasan masuk.</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($role == 'mahasiswa'): ?>
                <div class="tab-pane fade show active" id="tab-mybooking" role="tabpanel">
                    <div class="custom-card">
                        <div class="custom-card-header">Riwayat Pengajuan Booking</div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light"><tr><th class="ps-4">Nama Kos</th><th>Tgl Pengajuan</th><th>Status</th><th class="text-end pe-4">Aksi</th></tr></thead>
                                    <tbody>
                                        <?php
                                        $q_mhs = mysqli_query($conn, "SELECT b.*, k.nama_kos FROM booking b JOIN kos k ON b.id_kos=k.id_kos WHERE b.id_mahasiswa='$id_user'");
                                        while($rm = mysqli_fetch_assoc($q_mhs)): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-primary"><?= $rm['nama_kos'] ?></td>
                                            <td><?= date('d M Y, H:i', strtotime($rm['tgl_booking'])) ?></td>
                                            <td><span class="badge bg-<?= $rm['status']=='Pending'?'warning text-dark':($rm['status']=='Disetujui'?'success':'danger') ?> rounded-pill px-3"><?= $rm['status'] ?></span></td>
                                            <td class="text-end pe-4">
                                                <?php if($rm['status'] != 'Pending'): ?>
                                                    <a href="proses_hapus.php?id=<?= $rm['id_booking'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Hapus riwayat?')"><i class="bi bi-trash"></i></a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-mykeluhan" role="tabpanel">
                    <div class="custom-card">
                        <div class="custom-card-header d-flex justify-content-between align-items-center">
                            <span>Riwayat Keluhan Anda</span>
                            <a href="keluhan.php" class="btn btn-primary btn-sm rounded-pill px-3"><i class="bi bi-plus-lg me-1"></i> Buat Keluhan Baru</a>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <?php 
                                $q_my_keluhan = mysqli_query($conn, "SELECT k.*, ks.nama_kos FROM keluhan k JOIN kos ks ON k.id_kos=ks.id_kos WHERE k.id_mahasiswa='$id_user' ORDER BY k.tgl_keluhan DESC");
                                if(mysqli_num_rows($q_my_keluhan) > 0):
                                    while($my_kl = mysqli_fetch_assoc($q_my_keluhan)):
                                ?>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-4 bg-light h-100 shadow-sm">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="fw-bold text-primary"><?= $my_kl['nama_kos'] ?></span>
                                            <span class="small text-muted"><i class="bi bi-clock"></i> <?= date('d M Y', strtotime($my_kl['tgl_keluhan'])) ?></span>
                                        </div>
                                        <p class="small mb-3">"<?= $my_kl['isi_keluhan'] ?>"</p>
                                        
                                        <?php if(!empty($my_kl['balasan_pemilik'])): ?>
                                            <div class="mt-2 p-3 bg-white rounded-3 border-start border-success border-4">
                                                <small class="text-success fw-bold d-block mb-1"><i class="bi bi-reply-fill"></i> Respon Pemilik:</small>
                                                <p class="small mb-0 text-dark">"<?= $my_kl['balasan_pemilik'] ?>"</p>
                                            </div>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark small"><i class="bi bi-hourglass-split me-1"></i> Menunggu Respon</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php 
                                    endwhile;
                                else:
                                ?>
                                <div class="col-12 text-center py-5">
                                    <i class="bi bi-emoji-smile text-muted display-4 d-block mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada riwayat keluhan.</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div> 
        </div> 
    </div> 
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Logika Sidebar Responsive (Diperbarui)
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');

    // Buka/Tutup saat tombol hamburger diklik
    if(toggleBtn) {
        toggleBtn.addEventListener('click', (e) => {
            e.stopPropagation(); // Mencegah klik bocor ke document
            sidebar.classList.toggle('show');
        });
    }

    // Tutup sidebar otomatis jika user mengklik area di luar sidebar (Main Content)
    document.addEventListener('click', (e) => {
        if(window.innerWidth <= 991.98 && sidebar.classList.contains('show')) {
            if(!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        }
    });

    // Tutup sidebar otomatis ketika user memilih salah satu menu/tab
    const navLinks = document.querySelectorAll('#v-pills-tab .nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if(window.innerWidth <= 991.98) {
                sidebar.classList.remove('show');
            }
        });
    });

    // 2. Script agar tab tidak hilang saat refresh
    const triggerTabList = document.querySelectorAll('#v-pills-tab button[data-bs-toggle="pill"]');
    let activeTab = localStorage.getItem('activeTab_KostFinder');
    if(activeTab){
        const tabToActivate = document.querySelector(`button[data-bs-target="${activeTab}"]`);
        if(tabToActivate) {
            let tab = new bootstrap.Tab(tabToActivate);
            tab.show();
        }
    }
    triggerTabList.forEach(triggerEl => {
        triggerEl.addEventListener('shown.bs.tab', event => {
            localStorage.setItem('activeTab_KostFinder', event.target.getAttribute('data-bs-target'));
        });
    });

    // 3. SweetAlert untuk tombol hapus
    const tombolHapus = document.querySelectorAll('[onclick*="confirm"]');
    tombolHapus.forEach(tombol => {
        const onclickAttr = tombol.getAttribute('onclick');
        const confirmTextMatch = onclickAttr.match(/confirm\(['"](.*?)['"]\)/);
        const textPeringatan = confirmTextMatch ? confirmTextMatch[1] : "Yakin ingin melanjutkan aksi ini?";
        const urlTujuan = tombol.getAttribute('href');
        tombol.removeAttribute('onclick');

        tombol.addEventListener('click', function(e) {
            e.preventDefault(); 
            Swal.fire({
                title: 'Konfirmasi',
                text: textPeringatan,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    if (urlTujuan) window.location.href = urlTujuan;
                    else if (tombol.closest('form')) tombol.closest('form').submit(); 
                }
            });
        });
    });
});
</script>

</body>
</html>