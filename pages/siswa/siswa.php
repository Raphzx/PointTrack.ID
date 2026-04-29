<?php
include __DIR__ . '/../../config/connection.php';

$limit = 10;
$current_page = isset($_GET["p"]) ? max(1, intval($_GET["p"])) : 1;
$start = ($current_page - 1) * $limit;

$keyword = isset($_GET['search']) ? mysqli_real_escape_string($connect, $_GET['search']) : '';
$filter_tingkat = isset($_GET['tingkat']) ? mysqli_real_escape_string($connect, $_GET['tingkat']) : '';
$filter_kelas = isset($_GET['kelas']) ? mysqli_real_escape_string($connect, $_GET['kelas']) : '';

$conditions = [];
if ($keyword) $conditions[] = "(s.nama_siswa LIKE '%$keyword%' OR s.nis LIKE '%$keyword%')";
if ($filter_tingkat) $conditions[] = "k.tingkat = '$filter_tingkat'";
if ($filter_kelas) $conditions[] = "k.id = '$filter_kelas'";

$where_clause = $conditions ? "WHERE " . implode(" AND ", $conditions) : "";

$query = "
SELECT 
    s.id,
    s.nis,
    s.nama_siswa,
    s.tahun_masuk,
    k.nama_kelas,
    k.tingkat
FROM siswa s
LEFT JOIN (
    SELECT siswa_id, MAX(id) AS max_id
    FROM riwayat_kelas
    GROUP BY siswa_id
) last_rk ON s.id = last_rk.siswa_id
LEFT JOIN riwayat_kelas rk ON last_rk.max_id = rk.id
LEFT JOIN kelas k ON rk.kelas_id = k.id
$where_clause
ORDER BY k.tingkat ASC, k.nama_kelas ASC, s.nama_siswa ASC
LIMIT $start, $limit
";
$result = mysqli_query($connect, $query);

$total_query = "
SELECT COUNT(DISTINCT s.id) AS total
FROM siswa s
LEFT JOIN riwayat_kelas rk ON s.id = rk.siswa_id
LEFT JOIN kelas k ON rk.kelas_id = k.id
$where_clause
";
$total_data = mysqli_fetch_assoc(mysqli_query($connect, $total_query))['total'];
$total_page = ceil($total_data / $limit);

$kelas_by_tingkat = [];
if ($filter_tingkat) {
    $kelas_q = mysqli_query($connect,
        "SELECT id, nama_kelas FROM kelas WHERE tingkat='$filter_tingkat' ORDER BY nama_kelas ASC"
    );
    while ($k = mysqli_fetch_assoc($kelas_q)) $kelas_by_tingkat[] = $k;
}
?>

<div class="p-4 md:p-8">
<div class="max-w-6xl mx-auto bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">

<div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Data Siswa</h2>
        <p class="text-sm text-slate-400 mt-1">
            Total <span class="font-bold text-indigo-600"><?= $total_data ?></span> siswa aktif
        </p>
    </div>

    <a href="index.php?page=siswa_tambah"
       class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700
              text-white text-sm font-semibold rounded-xl shadow-md">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
            <path d="M12 4v16m8-8H4"/>
        </svg>
        Tambah
    </a>
</div>

<form method="GET"
      class="p-6 grid grid-cols-1 md:grid-cols-4 gap-4 bg-slate-50/60 border-b border-slate-100">
    <input type="hidden" name="page" value="siswa">

    <select name="tingkat" onchange="this.form.submit()"
        class="w-full px-4 py-3 text-sm bg-white border rounded-xl focus:ring-2 focus:ring-indigo-500">
        <option value="">Semua Tingkat</option>
        <option value="X" <?= $filter_tingkat=='X'?'selected':'' ?>>Tingkat X</option>
        <option value="XI" <?= $filter_tingkat=='XI'?'selected':'' ?>>Tingkat XI</option>
        <option value="XII" <?= $filter_tingkat=='XII'?'selected':'' ?>>Tingkat XII</option>
    </select>

    <select name="kelas" <?= !$filter_tingkat?'disabled':'' ?>
        onchange="this.form.submit()"
        class="w-full px-4 py-3 text-sm bg-white border rounded-xl
               focus:ring-2 focus:ring-indigo-500
               disabled:bg-slate-100 disabled:text-slate-400">
        <option value="">Semua Kelas</option>
        <?php foreach ($kelas_by_tingkat as $k): ?>
            <option value="<?= $k['id'] ?>" <?= $filter_kelas==$k['id']?'selected':'' ?>>
                <?= $k['nama_kelas'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <div class="relative md:col-span-2">
        <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"
             fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" name="search" value="<?= htmlspecialchars($keyword) ?>"
               placeholder="Cari NIS atau Nama Siswa..."
               class="w-full pl-10 pr-4 py-3 text-sm bg-white border rounded-xl focus:ring-2 focus:ring-indigo-500">
    </div>
</form>

<div class="overflow-x-auto">
<table class="min-w-full text-sm">
<thead>
<tr class="bg-slate-50 text-slate-400 text-[11px] uppercase tracking-widest font-bold">
    <th class="px-8 py-5">Siswa</th>
    <th class="px-8 py-5">Kelas</th>
    <th class="px-8 py-5">Tahun Masuk</th>
    <th class="px-8 py-5 text-right">Opsi</th>
</tr>
</thead>

<tbody class="divide-y divide-slate-100">
<?php if(mysqli_num_rows($result)>0): ?>
<?php while($row=mysqli_fetch_assoc($result)):
$badge='bg-slate-100 text-slate-600';
if($row['tingkat']=='X')$badge='bg-blue-100 text-blue-600';
if($row['tingkat']=='XI')$badge='bg-purple-100 text-purple-600';
if($row['tingkat']=='XII')$badge='bg-orange-100 text-orange-600';
?>
<tr class="group hover:bg-slate-50 transition cursor-pointer">
<td class="px-8 py-5">
<div class="flex items-center gap-4">
<div class="h-10 w-10 rounded-full bg-indigo-50 text-indigo-600
            flex items-center justify-center font-bold text-xs uppercase
            transition-all group-hover:bg-indigo-600 group-hover:text-white">
<?= strtoupper(substr($row['nama_siswa'],0,2)) ?>
</div>
<div>
<p class="font-semibold text-slate-800 transition group-hover:text-indigo-600">
<?= htmlspecialchars($row['nama_siswa']) ?>
</p>
<p class="text-xs text-slate-400">NIS: <?= $row['nis'] ?></p>
</div>
</div>
</td>

<td class="px-8 py-5 text-center">
<?php if($row['nama_kelas']): ?>
<div class="font-semibold text-slate-700"><?= $row['nama_kelas'] ?></div>
<span class="inline-block mt-1 px-2 py-0.5 rounded-lg text-[10px] font-bold <?= $badge ?>">
Tingkat <?= $row['tingkat'] ?>
</span>
<?php else: ?>
<span class="italic text-slate-300">Belum ada kelas</span>
<?php endif; ?>
</td>

<td class="px-8 py-5 text-slate-600 text-center"><?= $row['tahun_masuk'] ?></td>

<td class="px-8 py-5 text-right">
<div class="flex justify-end gap-1">
<a href="index.php?page=siswa_edit&id=<?= $row['id'] ?>"
   class="p-2 rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50">
<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
     viewBox="0 0 24 24">
<path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
         m-1.414-9.414a2 2 0 112.828 2.828
         L11.828 15H9v-2.828l8.586-8.586z"/>
</svg>
</a>

<a href="index.php?page=siswa_hapus&id=<?= $row['id'] ?>"
   onclick="return confirm('Hapus data siswa ini?')"
   class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50">
<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
     viewBox="0 0 24 24">
<path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
         a2 2 0 01-1.995-1.858L5 7
         m5 4v6m4-6v6
         M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3
         M4 7h16"/>
</svg>
</a>
</div>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="4" class="py-16 text-center text-slate-400 italic">
Data siswa tidak ditemukan
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
$nav="&search=".urlencode($keyword)."&tingkat=$filter_tingkat&kelas=$filter_kelas";
for($i=1;$i<=$total_page;$i++):
?>
<a href="index.php?page=siswa&p=<?= $i.$nav ?>"
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
