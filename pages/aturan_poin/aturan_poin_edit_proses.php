<?php
include __DIR__ . '/../../config/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $min_poin = $_POST['min_poin'];
    $max_poin = $_POST['max_poin'];
    $tindakan = $_POST['tindakan'];
    $warna = $_POST['warna'];

    $query = "UPDATE aturan_poin SET min_poin = '$min_poin', max_poin = '$max_poin', tindakan = '$tindakan', warna = '$warna' WHERE id = '$id'";

    if (mysqli_query($connect, $query)) {
        echo "<script>window.location.href='aturan_poin';</script>";
        exit();
    } else {
        echo "<script>alert('Error: " . mysqli_error($connect) . "'); window.history.back();</script>";
    }
}
?>
