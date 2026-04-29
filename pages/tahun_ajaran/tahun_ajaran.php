<?php
include __DIR__ . '/../../config/connection.php';

$limit        = 10;
$current_page = isset($_GET["p"]) ? max(1, intval($_GET["p"])) : 1;
$start        = ($current_page - 1) * $limit;
$keyword      = isset($_GET['search']) ? mysqli_real_escape_string($connect, $_GET['search']) : '';

$where_clause = "";
if (!empty($keyword)) {
    $where_clause = "WHERE nama_tahun LIKE '%$keyword%'";
}

$query = "SELECT * FROM tahun_pelajaran 
          $where_clause 
          ORDER BY nama_tahun DESC 
          LIMIT $start, $limit";
$result = mysqli_query($connect, $query);

$total_query = "SELECT COUNT(*) AS total FROM tahun_pelajaran $where_clause";
$total_res   = mysqli_fetch_assoc(mysqli_query($connect, $total_query));
$total_data  = $total_res['total'];
$total_page  = ceil($total_data / $limit);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<div class="animate__animated animate__fadeIn p-4 md:p-8">
<div class="max-w-6xl mx-auto bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">

    <!-- HEADER -->
    <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Tahun Pelajaran</h2>
            <p class="text-sm text-slate-500">Daftar periode akademik terdaftar</p>
        </div>

        <a href="index.php?page=tahun_ajaran_tambah"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-2xl transition-all shadow-md hover:shadow-indigo-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Tahun
        </a>
    </div>

    <!-- FILTER -->
    <form action="index.php" method="GET" class="p-6 bg-slate-50/60 border-b border-slate-100">
        <input type="hidden" name="page" value="tahun_ajaran">

        <div class="relative max-w-sm">
            <input type="text" name="search" value="<?= htmlspecialchars($keyword) ?>"
                placeholder="Cari tahun..."
                class="pl-11 pr-4 py-3 text-sm bg-white border rounded-2xl focus:ring-2 focus:ring-indigo-500 w-full transition-all">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
    </form>

    <!-- TABLE -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left">
            <thead>
                <tr class="bg-slate-50/50 text-slate-400 text-[11px] uppercase tracking-widest font-bold">
                    <th class="px-8 py-5">Tahun Pelajaran</th>
                    <th class="px-8 py-5 text-center">Status</th>
                    <th class="px-8 py-5 text-right">Opsi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($item = mysqli_fetch_assoc($result)):
                    $is_active = (strtolower($item['status']) == 'aktif');
                    $status_badge = $is_active
                        ? 'bg-emerald-50 text-emerald-600 ring-emerald-100'
                        : 'bg-rose-50 text-rose-600 ring-rose-100';
                ?>
                <tr class="group hover:bg-slate-50/80 transition-all duration-200">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center
                                        group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7
                                             a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="font-bold text-slate-800"><?= htmlspecialchars($item['nama_tahun']) ?></p>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase ring-1 <?= $status_badge ?>">
                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 <?= $is_active ? 'bg-emerald-500' : 'bg-rose-500' ?>"></span>
                            <?= htmlspecialchars($item['status']) ?>
                        </span>
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="index.php?page=tahun_ajaran_edit&id=<?= $item['id'] ?>"
                               class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11
                                             a2 2 0 002-2v-5m-1.414-9.414
                                             a2 2 0 112.828 2.828L11.828 15H9v-2.828
                                             l8.586-8.586z" />
                                </svg>
                            </a>
                            <a href="index.php?page=tahun_ajaran_hapus&id=<?= $item['id'] ?>"
                               onclick="return confirm('Hapus data tahun pelajaran ini?')"
                               class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                             a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6
                                             m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="px-8 py-24 text-center text-slate-400 italic">
                        Data tahun pelajaran tidak ditemukan.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($total_page > 1): ?>
    <div class="px-8 py-6 bg-slate-50/30 border-t border-slate-50 flex justify-between items-center">
        <p class="text-xs text-slate-400 font-medium">
            Halaman <?= $current_page ?> dari <?= $total_page ?>
        </p>
        <div class="flex gap-1">
            <?php for ($i = 1; $i <= $total_page; $i++): ?>
                <a href="index.php?page=tahun_ajaran&p=<?= $i ?>&search=<?= urlencode($keyword) ?>"
                   class="w-9 h-9 flex items-center justify-center rounded-xl text-xs font-bold transition-all
                   <?= $i == $current_page ? 'bg-indigo-600 text-white' : 'bg-white text-slate-400 border hover:bg-slate-100' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
    <?php endif; ?>

</div>
</div>
