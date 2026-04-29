<?php
include __DIR__ . '/../../config/connection.php';

$query = "
SELECT 
    s.id AS siswa_id,
    s.nis,
    s.nama_siswa,
    k.nama_kelas,
    k.tingkat,
    tp.nama_tahun,
    SUM(jp.poin) AS total_poin
FROM pelanggaran p
JOIN riwayat_kelas rk ON p.riwayat_kelas_id = rk.id
JOIN siswa s ON rk.siswa_id = s.id
JOIN kelas k ON rk.kelas_id = k.id
LEFT JOIN tahun_pelajaran tp ON rk.tahun_pelajaran_id = tp.id
JOIN jenis_pelanggaran jp ON p.pelanggaran_id = jp.id
GROUP BY s.id, s.nis, s.nama_siswa, k.nama_kelas, k.tingkat, tp.nama_tahun
ORDER BY total_poin DESC
";

$result = mysqli_query($connect, $query);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {

    $detail = mysqli_query($connect, "
        SELECT p.tanggal, jp.nama_pelanggaran, jp.poin
        FROM pelanggaran p
        JOIN jenis_pelanggaran jp ON p.pelanggaran_id = jp.id
        JOIN riwayat_kelas rk ON p.riwayat_kelas_id = rk.id
        WHERE rk.siswa_id = '{$row['siswa_id']}'
        ORDER BY p.tanggal DESC
    ");

    $riwayat = [];
    while ($d = mysqli_fetch_assoc($detail)) {
        $riwayat[] = $d;
    }

    $data[] = [
        'nis' => $row['nis'],
        'nama_siswa' => $row['nama_siswa'],
        'nama_kelas' => $row['tingkat'] . ' ' . $row['nama_kelas'],
        'nama_tahun' => $row['nama_tahun'] ?? '-',
        'total_poin' => (int)$row['total_poin'],
        'riwayat_pelanggaran' => $riwayat
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Monitoring Poin Siswa</title>

<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

<style>
[x-cloak]{display:none;}
@media print {.no-print{display:none}}
</style>
</head>

<body x-data="monitoringSiswa()" class="bg-slate-100 min-h-screen p-6">

<div class="max-w-6xl mx-auto">
<div class="bg-white rounded-3xl shadow border overflow-hidden">

    <div class="p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Monitoring Poin Siswa</h1>
            <p class="text-sm text-slate-500">
                Total Data :
                <span class="font-semibold"><?= count($data) ?></span>
            </p>
        </div>

        <button
            @click="exportPDF()"
            class="bg-rose-600 hover:bg-rose-700 text-white px-5 py-2 rounded-xl text-sm font-semibold flex items-center gap-2 shadow-lg no-print"
        >
            <i class='bx bxs-file-pdf text-lg'></i>
            Export
        </button>
    </div>

    <div class="px-8 pb-6 grid md:grid-cols-2 gap-4 no-print">

        <select
            x-model="filterTahun"
            class="w-full px-4 py-3 border rounded-xl text-sm focus:ring-2 focus:ring-indigo-500"
        >
            <option value="">Semua Tahun Pelajaran</option>
            <template x-for="t in listTahun" :key="t">
                <option x-text="t"></option>
            </template>
        </select>

        <select
            x-model="filterKelas"
            class="w-full px-4 py-3 border rounded-xl text-sm focus:ring-2 focus:ring-indigo-500"
        >
            <option value="">Semua Kelas</option>
            <template x-for="k in listKelas" :key="k">
                <option x-text="k"></option>
            </template>
        </select>

    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">

            <thead class="bg-slate-50 text-slate-400 text-[11px] uppercase tracking-widest">
                <tr>
                    <th class="px-6 py-4 text-center">No</th>
                    <th class="px-6 py-4 text-center">NIS</th>
                    <th class="px-6 py-4 text-center">Nama</th>
                    <th class="px-6 py-4 text-center">Kelas</th>
                    <th class="px-6 py-4 text-center">Poin</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">

                <template x-for="(s,i) in filteredSiswa" :key="s.nis">
                    <tr class="hover:bg-slate-50">

                        <td class="px-6 py-4 text-center" x-text="i+1"></td>

                        <td class="px-6 py-4 text-center" x-text="s.nis"></td>

                        <td class="px-6 py-4 text-center">
                            <button
                                @click="openModal(s)"
                                class="font-semibold hover:text-indigo-600"
                                x-text="s.nama_siswa">
                            </button>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div x-text="s.nama_kelas"></div>
                            <div class="text-xs text-slate-400" x-text="s.nama_tahun"></div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span
                                :class="pointBadge(s.total_poin)"
                                class="px-4 py-1.5 rounded-full text-xs font-bold ring-1 ring-inset"
                                x-text="s.total_poin">
                            </span>
                        </td>

                    </tr>
                </template>

            </tbody>

        </table>
    </div>

</div>
</div>

<div
    x-show="modalOpen"
    x-cloak
    class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 no-print"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
>

    <div
    x-show="modalOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    @click.outside="modalOpen=false"
    class="w-full max-w-2xl bg-white rounded-[2rem] shadow-2xl overflow-hidden max-h-[88vh]"
>

        <div class="px-7 py-6 border-b">
            <div class="flex justify-between gap-4">

                <div>
                    <h2
                        class="text-3xl font-black text-slate-800"
                        x-text="selected?.nama_siswa">
                    </h2>

                    <p
                        class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold mt-1"
                        x-text="selected?.nama_kelas">
                    </p>

                    <p
                        class="text-xs text-slate-400 mt-1"
                        x-text="selected?.nama_tahun">
                    </p>
                </div>

                <div class="flex items-center gap-3">

                    <div class="text-right">
                        <p class="text-[10px] uppercase text-slate-400 font-bold">
                            Total Poin
                        </p>

                        <p
                            class="text-sm font-bold"
                            :class="statusText(selected?.total_poin)"
                            x-text="statusLabel(selected?.total_poin)">
                        </p>
                    </div>

                    <div
                        :class="statusCircle(selected?.total_poin)"
                        class="w-16 h-16 rounded-3xl flex items-center justify-center text-2xl font-black shadow"
                    >
                        <span x-text="selected?.total_poin"></span>
                    </div>

                </div>

            </div>
        </div>

        <div class="px-7 py-5 bg-slate-50 overflow-y-auto max-h-[55vh]">

            <div class="space-y-3">

                <template x-for="(r,i) in selected?.riwayat_pelanggaran" :key="i">

                    <div class="bg-white rounded-2xl px-5 py-4 flex justify-between items-center">

                        <div class="flex gap-4 items-start">

                            <div
                                class="w-2.5 h-2.5 rounded-full mt-2"
                                :class="statusCircle(r.poin)">
                            </div>

                            <div>
                                <p
                                    class="font-bold text-slate-800"
                                    x-text="r.nama_pelanggaran">
                                </p>

                                <p
                                    class="text-sm text-slate-500"
                                    x-text="formatTanggal(r.tanggal)">
                                </p>
                            </div>

                        </div>

                        <span
                            :class="pointBadge(r.poin)"
                            class="px-4 py-2 rounded-full text-xs font-bold shadow"
                            x-text="r.poin + ' Poin'">
                        </span>

                    </div>

                </template>

                <template x-if="selected?.riwayat_pelanggaran.length === 0">
                    <div class="text-center py-10 text-slate-400">
                        Tidak ada riwayat pelanggaran
                    </div>
                </template>

            </div>

        </div>

        <div class="p-5 border-t bg-white">
            <button
                @click="modalOpen=false"
                class="w-full py-4 rounded-2xl bg-slate-950 hover:bg-slate-800 text-white font-black tracking-[0.25em] uppercase"
            >
                Tutup
            </button>
        </div>

    </div>
</div>

<script>
function monitoringSiswa(){
return {

    modalOpen:false,
    selected:null,

    filterTahun:'',
    filterKelas:'',

    dataSiswa: <?= json_encode($data) ?>,

    exportPDF(){
        let url = 'export-pdf.php?';

        if(this.filterTahun){
            url += 'tahun=' + encodeURIComponent(this.filterTahun) + '&';
        }

        if(this.filterKelas){
            url += 'kelas=' + encodeURIComponent(this.filterKelas);
        }

        window.open(url,'_blank');
    },

    get listTahun(){
        return [...new Set(this.dataSiswa.map(s => s.nama_tahun))].sort();
    },

    get listKelas(){
        return [...new Set(this.dataSiswa.map(s => s.nama_kelas))].sort();
    },

    get filteredSiswa(){
        return this.dataSiswa.filter(s =>
            (!this.filterTahun || s.nama_tahun === this.filterTahun) &&
            (!this.filterKelas || s.nama_kelas === this.filterKelas)
        );
    },

    openModal(s){
        this.selected = s;
        this.modalOpen = true;
    },

    formatTanggal(tgl){
        return new Date(tgl).toLocaleDateString('id-ID',{
            day:'2-digit',
            month:'short',
            year:'numeric'
        });
    },

    pointBadge(p){
        if(p < 25) return 'bg-emerald-100 text-emerald-600 ring-emerald-200';
        if(p < 50) return 'bg-yellow-100 text-yellow-600 ring-yellow-200';
        if(p < 75) return 'bg-orange-100 text-orange-600 ring-orange-200';
        if(p < 100) return 'bg-rose-100 text-rose-600 ring-rose-200';
        return 'bg-slate-200 text-slate-700 ring-slate-300';
    },

    statusLabel(p){
        if(p < 25) return 'Aman';
        if(p < 50) return 'Panggilan Siswa';
        if(p < 75) return 'Panggilan Wali / Orang Tua';
        if(p < 100) return 'Tidak Naik Kelas';
        return 'Dropout';
    },

    statusCircle(p){
        if(p < 25) return 'bg-emerald-100 text-emerald-600';
        if(p < 50) return 'bg-yellow-100 text-yellow-600';
        if(p < 75) return 'bg-orange-100 text-orange-600';
        if(p < 100) return 'bg-rose-100 text-rose-600';
        return 'bg-slate-200 text-slate-700';
    },

    statusText(p){
        if(p < 25) return 'text-emerald-600';
        if(p < 50) return 'text-yellow-500';
        if(p < 75) return 'text-orange-500';
        if(p < 100) return 'text-rose-600';
        return 'text-slate-900';
    }

}
}
</script>

</body>
</html>