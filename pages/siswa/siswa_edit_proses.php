<?php
include __DIR__ . '/../../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        header("Location: index.php?page=siswa");
        exit;
    }

    $id = intval($_POST['id']);
    $nis = mysqli_real_escape_string($connect, $_POST['nis']);
    $nama_siswa = mysqli_real_escape_string($connect, $_POST['nama_siswa']);
    $alamat = mysqli_real_escape_string($connect, $_POST['alamat']);
    $tahun_masuk = mysqli_real_escape_string($connect, $_POST['tahun_masuk']);

    $sql_siswa = "UPDATE siswa SET 
                  nis = '$nis', 
                  nama_siswa = '$nama_siswa', 
                  alamat = '$alamat', 
                  tahun_masuk = '$tahun_masuk' 
                  WHERE id = $id";
    
    if (mysqli_query($connect, $sql_siswa)) {
        echo "<script>window.location.href='index.php?page=siswa&status=success_edit';</script>";
        exit;
    } else {
        $error_msg = urlencode("Gagal memperbarui profil siswa: " . mysqli_error($connect));
        echo "<script>window.location.href='index.php?page=siswa_edit&id=$id&status=error&msg=$error_msg';</script>";
        exit;
    }
} else {
    header("Location: index.php?page=siswa");
    exit;
}