<?php
include __DIR__ . '/../../config/connection.php';

if (isset($_POST['update'])) {
    $id               = intval($_POST['id']);
    $nama_pelanggaran = mysqli_real_escape_string($connect, $_POST['nama_pelanggaran']);
    $poin             = intval($_POST['poin']);

    if (empty($nama_pelanggaran) || $id <= 0 || $poin <= 0) {
        echo "<script>window.location.href='index.php?page=jenis_pelanggaran_edit&id=$id&status=empty';</script>";
        exit;
    }

    $query = "UPDATE jenis_pelanggaran SET 
              nama_pelanggaran = '$nama_pelanggaran', 
              poin = '$poin' 
              WHERE id = '$id'";
    
    if (mysqli_query($connect, $query)) {
        echo "<script>window.location.href='index.php?page=jenis_pelanggaran&status=success_edit';</script>";
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