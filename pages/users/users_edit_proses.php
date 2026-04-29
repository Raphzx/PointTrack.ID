<?php
include __DIR__ . '/../../config/connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $fullname = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($connect, "UPDATE user SET fullname = ?, email = ?, role = ?, status = ?, password = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sssssi", $fullname, $email, $role, $status, $password_hashed, $id);
    } else {
        $stmt = mysqli_prepare($connect, "UPDATE user SET fullname = ?, email = ?, role = ?, status = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssssi", $fullname, $email, $role, $status, $id);
    }

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        echo "<script>window.location.href='index.php?page=users';</script>";
        exit;
    } else {
        mysqli_stmt_close($stmt);
        echo "Gagal update user.";
    }
}
?>
