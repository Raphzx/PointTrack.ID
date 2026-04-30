<?php
require "config/connection.php";
require "config/auth.php";
require "config/routes.php";

if (!is_logged_in()) {
    include "pages/guest/guest_siswa.php";
    exit;
}

auth_check();

$page = $_GET['page'] ?? 'dashboard';

$admin_only_pages = [
    'users','users_tambah','users_edit','users_hapus','users_tambah_proses','users_edit_proses',
    'siswa','siswa_tambah','siswa_edit','siswa_hapus','siswa_tambah_proses','siswa_edit_proses',
    'kelas','kelas_tambah','kelas_hapus','kelas_tambah_proses',
    'tahun_ajaran','tahun_ajaran_tambah','tahun_ajaran_edit','tahun_ajaran_hapus',
    'tahun_ajaran_tambah_proses','tahun_ajaran_edit_proses',
    'riwayat_kelas','riwayat_tambah','riwayat_hapus','riwayat_tambah_proses',
    'jenis_pelanggaran','jenis_pelanggaran_tambah','jenis_pelanggaran_edit',
    'jenis_pelanggaran_hapus','jenis_pelanggaran_tambah_proses','jenis_pelanggaran_edit_proses',
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
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Pelanggaran Tata Tertib Sekolah</title>

<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<link rel="icon" href="layout/img/logo.svg">

<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
[x-cloak]{ display:none !important; }

html, body {
    width: 100%;
    overflow-x: hidden;
}

.sidebar-depth{
    box-shadow:
    inset -1px 0 0 rgba(0,0,0,.04),
    0 10px 30px rgba(0,0,0,.08);
}

.menu-item{
    position:relative;
    overflow:hidden;
}
.menu-item::before{
    content:"";
    position:absolute;
    inset:0;
    background:linear-gradient(120deg,transparent,rgba(255,255,255,.3),transparent);
    transform:translateX(-100%);
    transition:.5s;
}
.menu-item:hover::before{
    transform:translateX(100%);
}

.page-content{
    width:100%;
}

@media (max-width:1279px){
    .page-content{
        max-width:100% !important;
        margin:0 !important;
    }
}

@media (min-width:1280px){
    .page-content{
        max-width:1400px;
        margin:auto;
    }
}
</style>
</head>

<body class="bg-gray-100 antialiased">

<div 
class="min-h-screen flex"
x-data="{
    open: window.innerWidth >= 1280, // 🔥 FIX ANDROID
    toggle(){ this.open = !this.open }
}"
>

<div 
class="fixed inset-y-0 left-0 z-50 transform transition-transform duration-300"
:class="open 
? 'translate-x-0 w-64' 
: '-translate-x-full xl:translate-x-0 xl:w-20'"
>
    <?php include "layout/sidebar.php"; ?>
</div>

<div 
x-show="open && window.innerWidth < 1280"
x-transition
class="fixed inset-0 bg-black/40 z-40 xl:hidden"
@click="open = false"
></div>

<div 
class="flex-1 flex flex-col min-h-screen transition-all duration-300"
:class="open ? 'xl:ml-64' : 'xl:ml-20'"
>

<?php include "layout/topbar.php"; ?>

<main class="flex-1 w-full px-4 sm:px-6 md:px-8 lg:px-10 xl:px-12 py-6">

    <div class="page-content">
        <?php include $page_file; ?>
    </div>

</main>

<?php include "layout/footer.php"; ?>

</div>
</div>

</body>
</html>