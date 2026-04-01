<?php
include 'config.php';

if(isset($_POST['kirim_balasan_keluhan'])){
    $id_keluhan = $_POST['id_keluhan'];
    $balasan = mysqli_real_escape_string($conn, $_POST['balasan']);

    // Update balasan dan ubah status_baca menjadi 1 
    $sql = "UPDATE keluhan SET balasan_pemilik='$balasan', status_baca=1 WHERE id_keluhan='$id_keluhan'";
    
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Tanggapan keluhan berhasil dikirim!'); window.location='dashboard.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>