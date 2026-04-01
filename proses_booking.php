<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memproses Booking...</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; }
    </style>
</head>
<body>

<?php
include 'config.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if(isset($_POST['booking']) && isset($_SESSION['role']) && $_SESSION['role'] == 'mahasiswa'){
    // Amankan ID berupa angka
    $id_kos = (int)$_POST['id_kos'];
    $id_mhs = (int)$_SESSION['id_user'];
    
    // Tangkap data dan amankan
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $catatan = isset($_POST['catatan']) ? mysqli_real_escape_string($conn, $_POST['catatan']) : '';
    
    // Update profil mahasiswa
    mysqli_query($conn, "UPDATE users SET nama_lengkap='$nama', no_hp='$no_hp' WHERE id_user='$id_mhs'");
    $_SESSION['nama'] = $nama;
    
    // Simpan pesanan
    $sql = "INSERT INTO booking (id_mahasiswa, id_kos, catatan) VALUES ('$id_mhs', '$id_kos', '$catatan')";
    
    if(mysqli_query($conn, $sql)){
        // Pop-up Sukses yang Modern
        echo "
        <script>
            Swal.fire({
                title: 'Booking Berhasil!',
                text: 'Pesanan kamar Anda telah dikirim ke pemilik kost. Silakan cek menu Dashboard.',
                icon: 'success',
                showConfirmButton: true,
                confirmButtonText: 'Ke Dashboard',
                confirmButtonColor: '#0d6efd',
                allowOutsideClick: false,
                timer: 4000,
                timerProgressBar: true
            }).then((result) => {
                window.location.href = 'dashboard.php';
            });
        </script>
        ";
    } else {
        // Pop-up Gagal yang Modern
        echo "
        <script>
            Swal.fire({
                title: 'Oops, Gagal!',
                text: 'Terjadi kesalahan saat memproses booking Anda. Silakan coba lagi.',
                icon: 'error',
                confirmButtonText: 'Kembali',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                window.history.back();
            });
        </script>
        ";
    }
} else {
    header("Location: index.php");
}
?>

</body>
</html>