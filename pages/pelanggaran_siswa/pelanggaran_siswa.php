<?php
include __DIR__ . '/../../config/connection.php';

$getUser = mysqli_fetch_assoc(
    mysqli_query($connect, "SELECT id FROM user LIMIT 1")
);
$user_id = $getUser['id'] ?? null;

if (!$user_id) {
    die("User belum tersedia. Isi tabel user dulu.");
}

$dataKelas = mysqli_query(
    $connect,
    "SELECT id, nama_kelas, tingkat 
     FROM kelas 
     ORDER BY tingkat, nama_kelas"
);

$querySiswa = "
    SELECT 
        rk.id AS riwayat_id,
        s.nama_siswa,
        k.id AS kelas_id
    FROM riwayat_kelas rk
    JOIN siswa s ON rk.siswa_id = s.id
    JOIN kelas k ON rk.kelas_id = k.id
    JOIN (
        SELECT siswa_id, MAX(id) AS last_id
        FROM riwayat_kelas
        GROUP BY siswa_id
    ) last ON rk.id = last.last_id
    ORDER BY s.nama_siswa
";
$dataSiswa = mysqli_query($connect, $querySiswa);

$jenis = mysqli_query($connect, "SELECT * FROM jenis_pelanggaran");

if (isset($_POST['simpan'])) {
    $riwayat_id = $_POST['riwayat_id'];
    $tanggal    = $_POST['tanggal'];
    $jenisArr   = $_POST['jenis'] ?? [];

    foreach ($jenisArr as $j) {
        mysqli_query($connect, "
            INSERT INTO pelanggaran 
            (riwayat_kelas_id, user_id, pelanggaran_id, tanggal, keterangan)
            VALUES 
            ('$riwayat_id', '$user_id', '$j', '$tanggal', 'Input otomatis')
        ");
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Input Pelanggaran</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>

<body class="bg-slate-100 min-h-screen">

<div class="animate__animated animate__fadeIn p-4 md:p-8">
<div class="max-w-6xl mx-auto">

<div class="bg-white rounded-3xl shadow-md border border-slate-100 p-8 space-y-10">

    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            Input Pelanggaran
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            Pencatatan pelanggaran siswa berdasarkan kelas dan jenis pelanggaran
        </p>
    </div>

    <hr class="border-slate-200">

    <form method="post" class="space-y-10">

        <div>
            <div class="flex items-center gap-3 mb-4">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 7h18M3 12h18M3 17h18"/>
                </svg>
                <h3 class="text-sm font-bold text-slate-700">
                    Data Kelas dan Siswa
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">
                        Kelas
                    </label>
                    <select id="kelasSelect" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 border focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option value="">Pilih Kelas</option>
                        <?php while ($k = mysqli_fetch_assoc($dataKelas)) : ?>
                            <option value="<?= $k['id'] ?>">
                                <?= $k['tingkat'] ?> <?= $k['nama_kelas'] ?>
                            </option>
                        <?php endwhile ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">
                        Siswa
                    </label>
                    <select name="riwayat_id" id="siswaSelect" required disabled
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 border focus:ring-2 focus:ring-indigo-500 focus:outline-none disabled:bg-slate-100 disabled:cursor-not-allowed">
                        <option value="">Pilih Siswa</option>
                        <?php while ($s = mysqli_fetch_assoc($dataSiswa)) : ?>
                            <option value="<?= $s['riwayat_id'] ?>" data-kelas="<?= $s['kelas_id'] ?>">
                                <?= $s['nama_siswa'] ?>
                            </option>
                        <?php endwhile ?>
                    </select>
                </div>
            </div>
        </div>

        <div>
            <div class="flex justify-between items-center mb-3">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4"/>
                    </svg>
                    <h3 class="text-sm font-bold text-slate-700">
                        Jenis Pelanggaran
                    </h3>
                </div>
                <button type="button" onclick="tambah()"
                    class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                    Tambah
                </button>
            </div>

            <div id="pelanggaranWrapper" class="space-y-3">
                <select name="jenis[]" required
                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="">Pilih Pelanggaran</option>
                    <?php mysqli_data_seek($jenis, 0); while ($j = mysqli_fetch_assoc($jenis)) : ?>
                        <option value="<?= $j['id'] ?>">
                            <?= $j['nama_pelanggaran'] ?> (<?= $j['poin'] ?> poin)
                        </option>
                    <?php endwhile ?>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">
                    Tanggal
                </label>
                <input type="date" name="tanggal" required
                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>

            <button name="simpan"
                class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-lg transition-all">
                Simpan
            </button>
        </div>

    </form>
</div>
</div>
</div>

<script>
const kelasSelect = document.getElementById('kelasSelect');
const siswaSelect = document.getElementById('siswaSelect');

kelasSelect.addEventListener('change', () => {
    const kelasId = kelasSelect.value;
    siswaSelect.value = "";
    siswaSelect.disabled = !kelasId;

    [...siswaSelect.options].forEach(opt => {
        if (!opt.dataset.kelas) return;
        opt.hidden = kelasId && opt.dataset.kelas !== kelasId;
    });
});

function tambah() {
    const wrapper = document.getElementById('pelanggaranWrapper');
    const select = wrapper.querySelector('select');

    const row = document.createElement('div');
    row.className = "flex gap-2";

    const newSelect = select.cloneNode(true);
    newSelect.value = "";

    const btn = document.createElement('button');
    btn.type = "button";
    btn.innerHTML = `
        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M6 18L18 6M6 6l12 12"/>
        </svg>
    `;
    btn.onclick = () => row.remove();

    row.appendChild(newSelect);
    row.appendChild(btn);
    wrapper.appendChild(row);
}
</script>

</body>
</html>
