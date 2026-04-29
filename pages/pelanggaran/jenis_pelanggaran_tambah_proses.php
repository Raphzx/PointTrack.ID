<?php
include __DIR__ . '/../../config/connection.php';

if (isset($_POST['simpan'])) {
    $nama_pelanggaran = mysqli_real_escape_string($connect, $_POST['nama_pelanggaran']);
    $poin             = intval($_POST['poin']);

    if (empty($nama_pelanggaran) || $poin <= 0) {
        echo "<script>window.location.href='index.php?page=jenis_pelanggaran_tambah&status=empty';</script>";
        exit;
    }

    $query = "INSERT INTO jenis_pelanggaran (nama_pelanggaran, poin) VALUES ('$nama_pelanggaran', '$poin')";
    
    if (mysqli_query($connect, $query)) {
        echo "<script>window.location.href='index.php?page=jenis_pelanggaran&status=success_add';</script>";
        exit;
    } else {
        echo "<script>window.location.href='index.php?page=jenis_pelanggaran&status=error';</script>";
        exit;
    }
} else {
    echo "<script>window.location.href='index.php?page=jenis_pelanggaran';</script>";
    exit;
}
?>