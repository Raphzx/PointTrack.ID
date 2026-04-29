<?php
include __DIR__ . '/../../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nis = mysqli_real_escape_string($connect, $_POST['nis']);
    $nama_siswa = mysqli_real_escape_string($connect, $_POST['nama_siswa']);
    $alamat = mysqli_real_escape_string($connect, $_POST['alamat']);
    $tahun_masuk = mysqli_real_escape_string($connect, $_POST['tahun_masuk']);
    
    $kelas_id = intval($_POST['kelas_id']);
    $tahun_pelajaran_id = intval($_POST['tahun_pelajaran_id']);

    mysqli_begin_transaction($connect);

    try {
        $query_siswa = "INSERT INTO siswa (nis, nama_siswa, alamat, tahun_masuk) 
                        VALUES ('$nis', '$nama_siswa', '$alamat', '$tahun_masuk')";
        
        if (!mysqli_query($connect, $query_siswa)) {
            throw new Exception("Gagal simpan data profil siswa.");
        }

        $siswa_id = mysqli_insert_id($connect);

        $query_riwayat = "INSERT INTO riwayat_kelas (siswa_id, kelas_id, tahun_pelajaran_id) 
                          VALUES ($siswa_id, $kelas_id, $tahun_pelajaran_id)";
        
        if (!mysqli_query($connect, $query_riwayat)) {
            throw new Exception("Gagal simpan data riwayat kelas.");
        }

        mysqli_commit($connect);
        
        echo "<script>window.location.href='index.php?page=siswa&status=success_add';</script>";
        exit;

    } catch (Exception $e) {
        mysqli_rollback($connect);

        $error_msg = urlencode($e->getMessage());
        echo "<script>window.location.href='index.php?page=siswa_tambah&status=error&msg=$error_msg';</script>";
        exit;
    }
} else {
    echo "<script>window.location.href='index.php?page=siswa';</script>";
    exit;
}
?>