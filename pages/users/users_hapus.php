<?php
include __DIR__ . '/../../config/connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = mysqli_prepare($connect, "DELETE FROM user WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        echo "<script>window.location.href='index.php?page=users';</script>";
        exit;
    } else {
        mysqli_stmt_close($stmt);
        echo "Gagal menghapus user.";
    }
} else {
    header("Location: index.php?page=users");
    exit;
}
?>
