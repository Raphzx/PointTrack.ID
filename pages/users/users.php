<?php 
include __DIR__ . '/../../config/connection.php';

$login_user_id = $_SESSION['user_id'] ?? 0;

$limit = 5;
$current_page = isset($_GET["p"]) ? intval($_GET["p"]) : 1;
$start = ($current_page - 1) * $limit;
$keyword = isset($_GET['search']) ? mysqli_real_escape_string($connect, $_GET['search']) : '';
$where_clause = !empty($keyword) ? "WHERE fullname LIKE '%$keyword%' OR email LIKE '%$keyword%'" : "";

$query = "SELECT * FROM user $where_clause LIMIT $start, $limit";
$result = mysqli_query($connect, $query);

$total_data_query = mysqli_query($connect, "SELECT COUNT(*) AS total FROM user $where_clause");
$total_data_row = mysqli_fetch_assoc($total_data_query);
$total_data = $total_data_row['total'];
$total_page = ceil($total_data / $limit);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    .glass-effect {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
    }
    .table-row-animate {
        animation: fadeInUp;
        animation-duration: 0.5s;
    }
</style>

<div class="animate__animated animate__fadeIn p-4 md:p-8">
    <div class="bg-white rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-slate-100 overflow-hidden max-w-5xl mx-auto">

        <div class="p-8 border-b border-slate-50 bg-slate-50/50">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Management User</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Dikelola dengan total <span class="font-semibold text-blue-600"><?= $total_data ?></span> pengguna terdaftar.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <form action="index.php" method="GET" class="group relative">
                        <input type="hidden" name="page" value="users">
                        <input type="text" name="search" value="<?= htmlspecialchars($keyword) ?>" placeholder="Cari sesuatu..." class="pl-11 pr-4 py-2.5 text-sm bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-400 focus:outline-none w-full md:w-72 transition-all duration-300 shadow-sm group-hover:shadow-md">
                        <div class="absolute left-4 top-3 text-slate-400 group-focus-within:text-blue-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </form>

                    <a href="index.php?page=users_tambah"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700
                              text-white text-sm font-semibold rounded-xl shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah User
                    </a>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-white text-slate-400 text-[11px] uppercase tracking-[0.1em] font-bold">
                    <tr>
                        <th class="px-8 py-5">Profil Pengguna</th>
                        <th class="px-8 py-5">Role</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                <?php if (mysqli_num_rows($result) > 0): 
                    $delay = 0;
                    while($item = mysqli_fetch_assoc($result)): 
                    $delay += 0.1; ?>
                    <tr class="table-row-animate group hover:bg-blue-50/30 transition-colors duration-300"
                        style="animation-delay: <?= $delay ?>s">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-600
                                            flex items-center justify-center text-white font-bold shadow-lg shadow-blue-100 uppercase">
                                    <?= substr($item["fullname"], 0, 1) ?>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800"><?= htmlspecialchars($item["fullname"]) ?></div>
                                    <div class="text-xs text-slate-400"><?= htmlspecialchars($item["email"]) ?></div>
                                </div>
                            </div>
                        </td>

                        <td class="px-8 py-5">
                            <span class="px-3 py-1 rounded-lg bg-slate-100 text-slate-600 font-medium text-xs">
                                <?= $item["role"] ?>
                            </span>
                        </td>

                        <td class="px-8 py-5">
                            <?php if($item['status'] === 'aktif'): ?>
                                <span class="inline-flex items-center gap-1.5 text-emerald-600 font-bold text-xs">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Aktif
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1.5 text-rose-500 font-bold text-xs">
                                    <span class="h-1.5 w-1.5 rounded-full bg-rose-400"></span>
                                    Nonaktif
                                </span>
                            <?php endif; ?>
                        </td>

                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">

                                <a href="index.php?page=users_edit&id=<?= $item['id'] ?>" 
                                   class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-100 rounded-xl transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536
                                                 L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>

                                <?php if ($item['id'] != $login_user_id): ?>
                                <a href="index.php?page=users_hapus&id=<?= $item['id'] ?>" 
                                   onclick="return confirm('Hapus user ini?')"
                                   class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-100 rounded-xl transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                                 a2 2 0 01-1.995-1.858L5 7
                                                 m5 4v6m4-6v6m1-10V4
                                                 a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </a>
                                <?php endif; ?>

                            </div>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <p class="text-slate-400 font-medium">Data pengguna tidak ditemukan</p>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_page > 1): ?>
        <div class="p-6 bg-white border-t border-slate-50 flex justify-center">
            <nav class="flex gap-2">
                <?php for($i = 1; $i <= $total_page; $i++): ?>
                <a href="index.php?page=users&p=<?= $i ?>&search=<?= urlencode($keyword) ?>"
                   class="w-10 h-10 flex items-center justify-center rounded-xl text-sm font-bold transition-all duration-300
                   <?= $i == $current_page ? 'bg-blue-600 text-white shadow-lg shadow-blue-200 scale-110'
                                          : 'bg-white text-slate-400 hover:bg-slate-100 border border-slate-100' ?>">
                    <?= $i ?>
                </a>
                <?php endfor; ?>
            </nav>
        </div>
        <?php endif; ?>

    </div>
</div>
