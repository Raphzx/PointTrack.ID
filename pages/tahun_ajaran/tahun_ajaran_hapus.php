<?php
include __DIR__ . '/../../config/connection.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $check = mysqli_query($connect, "SELECT status FROM tahun_pelajaran WHERE id = '$id'");
    $data = mysqli_fetch_assoc($check);

    if ($data) {
        if ($data['status'] == 'Aktif') {
            echo "<script>window.location.href='index.php?page=tahun_ajaran&status=error_active';</script>";
            exit;
        }

        $delete = mysqli_query($connect, "DELETE FROM tahun_pelajaran WHERE id = '$id'");

        if ($delete) {
            echo "<script>window.location.href='index.php?page=tahun_ajaran&status=success_delete';</script>";
            exit;
        } else {
            echo "<script>window.location.href='index.php?page=tahun_ajaran&status=error';</script>";
            exit;
        }
    } else {
        echo "<script>window.location.href='index.php?page=tahun_ajaran&status=not_found';</script>";
        exit;
    }
} else {
    echo "<script>window.location.href='index.php?page=tahun_ajaran';</script>";
    exit;
}
?>