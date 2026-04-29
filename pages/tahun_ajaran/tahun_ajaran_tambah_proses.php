<?php
include __DIR__ . '/../../config/connection.php';

if (isset($_POST['simpan'])) {
    $nama_tahun = mysqli_real_escape_string($connect, $_POST['nama_tahun']);
    $status     = mysqli_real_escape_string($connect, $_POST['status']);

    mysqli_begin_transaction($connect);

    try {
        if ($status == 'Aktif') {
            mysqli_query($connect, "UPDATE tahun_pelajaran SET status = 'Tidak Aktif'");
        }

        $sql = "INSERT INTO tahun_pelajaran (nama_tahun, status) VALUES ('$nama_tahun', '$status')";
        
        if (mysqli_query($connect, $sql)) {
            mysqli_commit($connect);
            echo "<script>window.location.href='index.php?page=tahun_ajaran&status=success_add';</script>";
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