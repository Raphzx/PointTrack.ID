<?php
include __DIR__ . '/../../config/connection.php';

$tahun_list = mysqli_query($connect, "SELECT * FROM tahun_pelajaran ORDER BY nama_tahun DESC");
$kelas_list = mysqli_query($connect, "SELECT * FROM kelas ORDER BY tingkat ASC, nama_kelas ASC");

$tahun_id = $_GET['tahun'] ?? '';
$kelas_id = $_GET['kelas'] ?? '';

$where = [];
if ($tahun_id) $where[] = "riwayat_kelas.tahun_pelajaran_id = '$tahun_id'";
if ($kelas_id) $where[] = "kelas.id = '$kelas_id'";
$where_sql = $where ? "WHERE " . implode(" AND ", $where) : "";

$query = "
    SELECT 
        pelanggaran.id AS pelanggaran_id,
        pelanggaran.tanggal,
        siswa.nama_siswa,
        kelas.nama_kelas,
        kelas.tingkat,
        jenis_pelanggaran.nama_pelanggaran,
        jenis_pelanggaran.poin
    FROM pelanggaran
    JOIN riwayat_kelas ON pelanggaran.riwayat_kelas_id = riwayat_kelas.id
    JOIN siswa ON riwayat_kelas.siswa_id = siswa.id
    JOIN kelas ON riwayat_kelas.kelas_id = kelas.id
    JOIN jenis_pelanggaran ON pelanggaran.pelanggaran_id = jenis_pelanggaran.id
    $where_sql
    ORDER BY pelanggaran.tanggal DESC, siswa.nama_siswa ASC
";

$result = mysqli_query($connect, $query);
$total  = mysqli_num_rows($result);
?>
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
      

<div class="animate__animated animate__fadeIn p-4 md:p-8">
<div class="max-w-6xl mx-auto bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">

    <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Riwayat Pelanggaran Siswa</h1>
            <p class="text-sm text-slate-500 mt-1">
                Total data
                <span class="font-bold text-indigo-600"><?= $total ?></span>
            </p>
        </div>

        <button
            onclick="exportPDFRiwayat()"
            class="bg-rose-600 hover:bg-rose-700 text-white px-5 py-2 rounded-xl text-sm font-semibold flex items-center gap-2 shadow-lg no-print"
        >
            <i class='bx bxs-file-pdf text-lg'></i>
            Export
        </button>
    </div>

    <form method="GET" class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4 bg-slate-50/60">
        <input type="hidden" name="page" value="riwayat_siswa">

        <select name="tahun" onchange="this.form.submit()"
            class="w-full px-4 py-3 text-sm bg-white border rounded-2xl focus:ring-2 focus:ring-indigo-500">
            <option value="">Semua Tahun Pelajaran</option>
            <?php while($t = mysqli_fetch_assoc($tahun_list)): ?>
                <option value="<?= $t['id'] ?>" <?= $tahun_id == $t['id'] ? 'selected' : '' ?>>
                    <?= $t['nama_tahun'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <select name="kelas" onchange="this.form.submit()"
            class="w-full px-4 py-3 text-sm bg-white border rounded-2xl focus:ring-2 focus:ring-indigo-500">
            <option value="">Semua Kelas</option>
            <?php while($k = mysqli_fetch_assoc($kelas_list)): ?>
                <option value="<?= $k['id'] ?>" <?= $kelas_id == $k['id'] ? 'selected' : '' ?>>
                    <?= $k['tingkat'] ?> <?= $k['nama_kelas'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <div class="relative">
            <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"
                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/>
            </svg>
            <input type="text" placeholder="Cari nama siswa..."
                class="w-full pl-10 pr-4 py-3 text-sm bg-white border rounded-2xl focus:ring-2 focus:ring-indigo-500">
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-slate-50/50 text-slate-400 text-[11px] uppercase tracking-widest font-bold">
                    <th class="px-6 py-4 text-center">No</th>
                    <th class="px-6 py-4 text-center">Tanggal</th>
                    <th class="px-6 py-4">Nama Siswa</th>
                    <th class="px-6 py-4">Kelas</th>
                    <th class="px-6 py-4">Pelanggaran</th>
                    <th class="px-6 py-4 text-center">Poin</th>
                    <th class="px-6 py-4 text-center">Opsi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
            <?php if ($total > 0): $no = 1; ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr class="group hover:bg-slate-50 transition">
                    <td class="px-6 py-4 text-center text-slate-500"><?= $no++ ?></td>

                    <td class="px-6 py-4 text-center text-slate-500">
                        <?= $row['tanggal'] ? date('d M Y', strtotime($row['tanggal'])) : '-' ?>
                    </td>

                    <td class="px-6 py-4 font-semibold text-slate-800 text-center">
                        <?= htmlspecialchars($row['nama_siswa']) ?>
                    </td>

                    <td class="px-6 py-4 text-slate-600 text-center">
                        <?= $row['tingkat'] ?> <?= $row['nama_kelas'] ?>
                    </td>

                    <td class="px-6 py-4 text-slate-600 text-center">
                        <?= $row['nama_pelanggaran'] ?>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full bg-rose-100 text-rose-600 text-xs font-bold">
                            -<?= $row['poin'] ?>
                        </span>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="index.php?page=riwayat_siswa_hapus&id=<?= $row['pelanggaran_id'] ?>"
                               onclick="return confirm('Hapus riwayat pelanggaran ini?')"
                               class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all"
                               title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                             a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6
                                             m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3
                                             M4 7h16"/>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center text-slate-400 italic">
                        Data riwayat pelanggaran tidak tersedia
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
</div>
<script>
function exportPDFRiwayat(){

    const tahun  = document.querySelector('select[name="tahun"]').value
    const kelas  = document.querySelector('select[name="kelas"]').value
    const search = document.querySelector('input[placeholder="Cari nama siswa..."]').value

    let url = 'export-pdf2.php?'

    if(tahun)  url += 'tahun=' + encodeURIComponent(tahun) + '&'
    if(kelas)  url += 'kelas=' + encodeURIComponent(kelas) + '&'
    if(search) url += 'search=' + encodeURIComponent(search)

    window.open(url,'_blank')
}
</script>

