<?php
include __DIR__ . '/../../config/connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = $_POST["name"];
    $email = $_POST["email"];
    $role = $_POST["role"];
    $status = $_POST["status"];
    $password = $_POST["password"];

    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($connect, "INSERT INTO user (fullname, email, role, status, password) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssss", $fullname, $email, $role, $status, $password_hashed);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        echo "<script>window.location.href='index.php?page=users';</script>";
        exit;
    } else {
        mysqli_stmt_close($stmt);
        header("Location: index.php?page=users_tambah&error=1");
        exit;
    }
}
?>
