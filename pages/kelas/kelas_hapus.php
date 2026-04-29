<?php
include __DIR__ . '/../../config/connection.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $delete = mysqli_query($connect, "DELETE FROM kelas WHERE id = '$id'");
    
    if ($delete) {
        echo "<script>window.location.href='index.php?page=kelas&status=success_delete';</script>";
        exit;
    } else {
        echo "<script>window.location.href='index.php?page=kelas&status=error';</script>";
        exit;
    }
} else {
    echo "<script>window.location.href='index.php?page=kelas';</script>";
    exit;
}
?>