<?php
session_start();

require "../../config/connection.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /sistemptt_demo/pages/auth/login.php");
    exit;
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    $_SESSION['login_error'] = "Email dan password wajib diisi.";
    header("Location: /sistemptt_demo/pages/auth/login.php");
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, email, password, role FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['login_error'] = "Email atau password salah.";
        header("Location: /sistemptt_demo/pages/auth/login.php");
        exit;
    }

    $_SESSION['user_id']   = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];

    session_regenerate_id(true);

    header("Location: /sistemptt_demo/index.php?page=dashboard");
    exit;

} catch (PDOException $e) {
    $_SESSION['login_error'] = "Terjadi kesalahan sistem.";
    header("Location: /sistemptt_demo/pages/auth/login.php");
    exit;
}
