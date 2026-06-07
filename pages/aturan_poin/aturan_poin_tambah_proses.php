<?php
include __DIR__ . '/../../config/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $min_poin = $_POST['min_poin'];
    $max_poin = $_POST['max_poin'];
    $tindakan = $_POST['tindakan'];
    $warna = $_POST['warna'];

    $query = "INSERT INTO aturan_poin (min_poin, max_poin, tindakan, warna) VALUES ('$min_poin', '$max_poin', '$tindakan', '$warna')";

    if (mysqli_query($connect, $query)) {
        echo "<script>window.location.href='aturan_poin';</script>";
        exit();
    } else {
        echo "<script>alert('Error: " . mysqli_error($connect) . "'); window.history.back();</script>";
    }
}
?>
