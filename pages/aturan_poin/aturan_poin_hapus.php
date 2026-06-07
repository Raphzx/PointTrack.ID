<?php
include __DIR__ . '/../../config/connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM aturan_poin WHERE id = '$id'";

    if (mysqli_query($connect, $query)) {
        echo "<script>window.location.href='aturan_poin';</script>";
        exit();
    } else {
        echo "<script>alert('Error: " . mysqli_error($connect) . "'); window.history.back();</script>";
    }
} else {
    echo "<script>window.location.href='aturan_poin';</script>";
    exit();
}
?>
