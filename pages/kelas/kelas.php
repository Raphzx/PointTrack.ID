<?php 
include __DIR__ . '/../../config/connection.php';

$limit = 10;
$current_page = isset($_GET["p"]) ? max(1, intval($_GET["p"])) : 1;
$start = ($current_page - 1) * $limit;
$keyword = isset($_GET['search']) ? mysqli_real_escape_string($connect, $_GET['search']) : '';
$filter_tingkat = isset($_GET['tingkat']) ? mysqli_real_escape_string($connect, $_GET['tingkat']) : '';

$conditions = [];
if (!empty($keyword)) {
    $conditions[] = "nama_kelas LIKE '%$keyword%'";
}
if (!empty($filter_tingkat)) {
    $conditions[] = "tingkat = '$filter_tingkat'";
}
$where_clause = $conditions ? "WHERE " . implode(" AND ", $conditions) : "";

$query = "SELECT * FROM kelas 
          $where_clause 
          ORDER BY tingkat ASC, nama_kelas ASC 
          LIMIT $start, $limit";
$result = mysqli_query($connect, $query);

$total_query = "SELECT COUNT(*) AS total FROM kelas $where_clause";
$total_data = mysqli_fetch_assoc(mysqli_query($connect, $total_query))['total'] ?? 0;
$total_page = ceil($total_data / $limit);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div class="animate__animated animate__fadeIn p-4 md:p-8">
<div class="max-w-6xl mx-auto bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">

<div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Data Kelas</h2>
        <p class="text-sm text-slate-500">
            Total <span class="font-bold text-indigo-600"><?= $total_data ?></span> kelas terdaftar
        </p>
    </div>

    <a href="index.php?page=kelas_tambah"
       class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700
              text-white text-sm font-semibold rounded-xl shadow-md">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
            <path d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Kelas
    </a>
</div>

<form method="GET"
      class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4 bg-slate-50/60 border-b border-slate-100">
    <input type="hidden" name="page" value="kelas">

    <select name="tingkat" onchange="this.form.submit()"
        class="w-full px-4 py-3 text-sm bg-white border rounded-xl focus:ring-2 focus:ring-indigo-500">
        <option value="">Semua Tingkat</option>
        <option value="X" <?= $filter_tingkat=='X'?'selected':'' ?>>Kelas X</option>
        <option value="XI" <?= $filter_tingkat=='XI'?'selected':'' ?>>Kelas XI</option>
        <option value="XII" <?= $filter_tingkat=='XII'?'selected':'' ?>>Kelas XII</option>
    </select>

    <div class="relative md:col-span-2">
        <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"
             fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" name="search" value="<?= htmlspecialchars($keyword) ?>"
               placeholder="Cari nama kelas..."
               class="w-full pl-10 pr-4 py-3 text-sm bg-white border rounded-xl focus:ring-2 focus:ring-indigo-500">
    </div>
</form>

<div class="overflow-x-auto">
<table class="min-w-full text-sm">
<thead>
<tr class="bg-slate-50/50 text-slate-400 text-[11px] uppercase tracking-widest font-bold">
    <th class="px-8 py-5">Nama Kelas</th>
    <th class="px-8 py-5">Tingkat</th>
    <th class="px-8 py-5 text-right">Opsi</th>
</tr>
</thead>

<tbody class="divide-y divide-slate-100">
<?php if(mysqli_num_rows($result)>0): ?>
<?php while($item=mysqli_fetch_assoc($result)):
$badge='bg-slate-100 text-slate-600';
if($item['tingkat']=='X')$badge='bg-blue-50 text-blue-600';
if($item['tingkat']=='XI')$badge='bg-purple-50 text-purple-600';
if($item['tingkat']=='XII')$badge='bg-orange-50 text-orange-600';
?>
<tr class="group hover:bg-slate-50 transition">
<td class="px-8 py-5 text">
<div class="flex items-center gap-4">
<div class="h-10 w-10 rounded-xl bg-slate-100 text-slate-600
            flex items-center justify-center font-bold text-xs
            group-hover:bg-indigo-600 group-hover:text-white transition">
<?= $item['tingkat'] ?>
</div>
<p class="font-bold text-slate-800"><?= htmlspecialchars($item['nama_kelas']) ?></p>
</div>
</td>

<td class="px-8 py-5 text-center">
<span class="inline-block px-3 py-1 rounded-lg text-[10px] font-bold <?= $badge ?>">
Tingkat <?= $item['tingkat'] ?>
</span>
</td>

<td class="px-8 py-5 text-right">
<div class="flex justify-end opacity-0 group-hover:opacity-100 transition">
<a href="index.php?page=kelas_hapus&id=<?= $item['id'] ?>"
   onclick="return confirm('Hapus data kelas ini?')"
   class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl">
<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
     viewBox="0 0 24 24">
<path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
         a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6
         M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"/>
</svg>
</a>
</div>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="3" class="px-8 py-16 text-center text-slate-400 italic">
Tidak ada data kelas ditemukan
</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>

<?php if($total_page>1): ?>
<div class="px-8 py-6 bg-slate-50/40 border-t border-slate-100 flex justify-between items-center">
<p class="text-xs text-slate-400">
Halaman <?= $current_page ?> dari <?= $total_page ?>
</p>
<div class="flex gap-1">
<?php
$nav="&search=".urlencode($keyword)."&tingkat=$filter_tingkat";
for($i=1;$i<=$total_page;$i++):
?>
<a href="index.php?page=kelas&p=<?= $i.$nav ?>"
   class="w-9 h-9 flex items-center justify-center rounded-xl text-xs font-bold
   <?= $i==$current_page?'bg-indigo-600 text-white':'bg-white border text-slate-400 hover:bg-slate-100' ?>">
<?= $i ?>
</a>
<?php endfor; ?>
</div>
</div>
<?php endif; ?>

</div>
</div>
