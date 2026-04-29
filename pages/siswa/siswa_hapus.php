<?php
include __DIR__ . '/../../config/connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    mysqli_begin_transaction($connect);

    try {
        $query_riwayat = "DELETE FROM riwayat_kelas WHERE siswa_id = $id";
        if (!mysqli_query($connect, $query_riwayat)) {
            throw new Exception("Gagal menghapus riwayat kelas.");
        }

        $query_siswa = "DELETE FROM siswa WHERE id = $id";
        if (!mysqli_query($connect, $query_siswa)) {
            throw new Exception("Gagal menghapus profil.");
        }

        mysqli_commit($connect);

        echo "<script>window.location.href='index.php?page=siswa&status=success_delete';</script>";
        exit;

    } catch (Exception $e) {
        mysqli_rollback($connect);

        echo "<script>window.location.href='index.php?page=siswa&status=error';</script>";
        exit;
    }
} else {
    echo "<script>window.location.href='index.php?page=siswa';</script>";
    exit;
}
?>