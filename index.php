<?php
require "config/connection.php";
require "config/auth.php";
require "config/routes.php";

// Cek apakah sudah login atau belum
if (!is_logged_in()) {
    // Jika belum login, tampilkan guest page
    include "pages/guest/guest_siswa.php";
    exit;
}

// Jika sudah login, lanjutkan seperti biasa
auth_check();

$page = $_GET['page'] ?? 'dashboard';

$admin_only_pages = [
    'users',
    'users_tambah',
    'users_edit',
    'users_hapus',
    'users_tambah_proses',
    'users_edit_proses',

    'siswa',
    'siswa_tambah',
    'siswa_edit',
    'siswa_hapus',
    'siswa_tambah_proses',
    'siswa_edit_proses',

    'kelas',
    'kelas_tambah',
    'kelas_hapus',
    'kelas_tambah_proses',

    'tahun_ajaran',
    'tahun_ajaran_tambah',
    'tahun_ajaran_edit',
    'tahun_ajaran_hapus',
    'tahun_ajaran_tambah_proses',
    'tahun_ajaran_edit_proses',

    'riwayat_kelas',
    'riwayat_tambah',
    'riwayat_hapus',
    'riwayat_tambah_proses',

    'jenis_pelanggaran',
    'jenis_pelanggaran_tambah',
    'jenis_pelanggaran_edit',
    'jenis_pelanggaran_hapus',
    'jenis_pelanggaran_tambah_proses',
    'jenis_pelanggaran_edit_proses',
];

if (in_array($page, $admin_only_pages)) {
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        exit('Akses ditolak. Halaman ini hanya untuk admin.');
    }
}

$page_file = $pages[$page] ?? $pages['dashboard'];

if (!file_exists($page_file)) {
    $page_file = $pages['dashboard'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pelanggaran Tata Tertib Sekolah</title>

<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<link rel="icon" type="image/svg+xml" href="layout/img/logo.svg">
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        animation: {
          'typing': 'typing 3.5s steps(14) infinite',
          'typing-dot': 'typingDot 1.5s steps(1) infinite',
        },
        keyframes: {
          typing: {
            '0%, 100%': { width: '0' },
            '50%': { width: '100px' }
          },
          typingDot: {
            '0%, 100%': { opacity: '1' },
            '50%': { opacity: '0' }
          }
        }
      }
    }
  }
</script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
[x-cloak] { display:none !important; }

.menu-item {
    position: relative;
    overflow: hidden;
}

.menu-item::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(120deg, transparent, rgba(255,255,255,.4), transparent);
    transform: translateX(-100%);
    transition: transform .6s;
}

.menu-item:hover::before {
    transform: translateX(100%);
}

@keyframes logoPulse {
    0%,100% { transform:scale(1); }
    50% { transform:scale(1.05); }
}

.logo-pulse {
    animation: logoPulse 3s ease-in-out infinite;
}

.sidebar-depth {
    box-shadow:
        inset -1px 0 0 rgba(0,0,0,.04),
        0 10px 30px rgba(0,0,0,.08);
}

.no-scrollbar::-webkit-scrollbar {
    display:none;
}

.no-scrollbar {
    -ms-overflow-style:none;
    scrollbar-width:none;
}

.icon-bounce:hover {
    animation:bounce .4s ease;
}

@keyframes bounce {
    0% { transform:translateY(0); }
    30% { transform:translateY(-4px); }
    60% { transform:translateY(2px); }
    100% { transform:translateY(0); }
}
.page-content > div:first-child{
    width:100% !important;
    max-width:none !important;
}

.page-content .max-w-2xl,
.page-content .max-w-3xl,
.page-content .max-w-4xl,
.page-content .max-w-5xl,
.page-content .max-w-6xl,
.page-content .max-w-7xl{
    max-width:none !important;
    width:100% !important;
}
</style>
</head>

<body class="bg-gray-100">

<div
class="min-h-screen"
x-data="{
    open: localStorage.getItem('sidebarOpen') !== 'false',
    init() {
        this.$watch('open', value => localStorage.setItem('sidebarOpen', value))
    }
}"
>

<?php include "layout/sidebar.php"; ?>

<div
class="flex flex-col min-h-screen min-w-0 transition-all duration-500 ease-[cubic-bezier(.4,0,.2,1)]"
:class="open 
? 'ml-64 w-[calc(100%-16rem)]' 
: 'ml-20 w-[calc(100%-5rem)]'"
>

<?php include "layout/topbar.php"; ?>

<main class="flex-1 w-full p-6 overflow-x-auto">
    <div class="page-content w-full">
        <?php include $page_file; ?>
    </div>
</main>

<?php include "layout/footer.php"; ?>

</div>
</div>

</body>
</html>