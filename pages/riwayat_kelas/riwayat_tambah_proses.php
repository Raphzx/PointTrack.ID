<?php
include __DIR__ . '/../../config/connection.php';

if (isset($_POST['simpan'])) {
    $tahun_pelajaran_id = intval($_POST['tahun_pelajaran_id']);
    $kelas_id = intval($_POST['kelas_id']);
    $siswa_ids = $_POST['siswa_ids'];

    if (empty($siswa_ids)) {
        echo "<script>alert('Pilih minimal satu siswa!'); window.history.back();</script>";
        exit;
    }

    mysqli_begin_transaction($connect);

    try {
        foreach ($siswa_ids as $id_siswa) {
            $id_s = intval($id_siswa);
            
            $cek = mysqli_query($connect, "SELECT id FROM riwayat_kelas WHERE siswa_id=$id_s AND tahun_pelajaran_id=$tahun_pelajaran_id");
            
            if (mysqli_num_rows($cek) > 0) {
                mysqli_query($connect, "UPDATE riwayat_kelas SET kelas_id=$kelas_id WHERE siswa_id=$id_s AND tahun_pelajaran_id=$tahun_pelajaran_id");
            } else {
                mysqli_query($connect, "INSERT INTO riwayat_kelas (siswa_id, kelas_id, tahun_pelajaran_id) VALUES ($id_s, $kelas_id, $tahun_pelajaran_id)");
            }
        }

        mysqli_commit($connect);
        header("Location: ../../index.php?page=riwayat_kelas&status=success");
    } catch (Exception $e) {
        mysqli_rollback($connect);
        echo "Error: " . $e->getMessage();
    }
}