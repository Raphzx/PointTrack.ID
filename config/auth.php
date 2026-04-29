<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}

function auth_check(): void {
    if (!is_logged_in()) {
        header("Location: /sistemptt_demo/pages/auth/login.php");
        exit;
    }
}

function check_role(array $allowed_roles = []): void {
    auth_check();

    if (!empty($allowed_roles) && !in_array($_SESSION['user_role'], $allowed_roles)) {
        http_response_code(403);
        echo "<h2>Anda tidak memiliki akses ke halaman ini.</h2>";
        exit;
    }
}
function user_initials(string $name): string {
    $words = explode(' ', trim($name));
    $initials = '';

    foreach ($words as $w) {
        $initials .= strtoupper($w[0]);
        if (strlen($initials) === 2) break;
    }

    return $initials;
}
