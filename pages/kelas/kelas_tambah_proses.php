<?php
include __DIR__ . '/../../config/connection.php';

if (isset($_POST['submit'])) {
    $nama_kelas = mysqli_real_escape_string($connect, $_POST['nama_kelas']);
    $tingkat    = mysqli_real_escape_string($connect, $_POST['tingkat']);

    if (empty($nama_kelas) || empty($tingkat)) {
        echo "<script>window.location.href='index.php?page=kelas_tambah&status=empty';</script>";
        exit;
    }

    $query = "INSERT INTO kelas (nama_kelas, tingkat) VALUES ('$nama_kelas', '$tingkat')";
    
    if (mysqli_query($connect, $query)) {
        echo "<script>window.location.href='index.php?page=kelas&status=success_add';</script>";
        exit;
    } else {
        echo "<script>window.location.href='index.php?page=kelas&status=error';</script>";
        exit;
    }
} else {
    echo "<script>window.location.href='index.php?page=kelas';</script>";
    exit;
}