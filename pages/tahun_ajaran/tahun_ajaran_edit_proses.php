<?php
include __DIR__ . '/../../config/connection.php';

if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $nama_tahun = mysqli_real_escape_string($connect, $_POST['nama_tahun']);
    $status = mysqli_real_escape_string($connect, $_POST['status']);

    mysqli_begin_transaction($connect);

    try {
        if ($status == 'Aktif') {
            $reset_others = "UPDATE tahun_pelajaran SET status = 'Tidak Aktif' WHERE id != '$id'";
            mysqli_query($connect, $reset_others);
        }

        $sql = "UPDATE tahun_pelajaran SET 
                nama_tahun = '$nama_tahun', 
                status = '$status' 
                WHERE id = '$id'";
        
        $update = mysqli_query($connect, $sql);

        if ($update) {
            mysqli_commit($connect);
            echo "<script>window.location.href='index.php?page=tahun_ajaran&status=success_edit';</script>";
            exit;
        } else {
            throw new Exception(mysqli_error($connect));
        }

    } catch (Exception $e) {
        mysqli_rollback($connect);
        echo "<script>window.location.href='index.php?page=tahun_ajaran&status=error';</script>";
        exit;
    }

} else {
    echo "<script>window.location.href='index.php?page=tahun_ajaran';</script>";
    exit;
}
?>