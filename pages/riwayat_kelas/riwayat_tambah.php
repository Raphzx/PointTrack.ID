<?php 
include __DIR__ . '/../../config/connection.php';

$tahun_list = mysqli_query($connect, "SELECT * FROM tahun_pelajaran ORDER BY nama_tahun DESC");
$kelas_list = mysqli_query($connect, "SELECT * FROM kelas ORDER BY tingkat ASC, nama_kelas ASC");

$siswa_list = mysqli_query($connect, "SELECT id, nis, nama_siswa, alamat, tahun_masuk FROM siswa ORDER BY nama_siswa ASC");
?>

<div class="animate__animated animate__fadeIn p-4 md:p-8">
    <div class="max-w-6xl mx-auto">
        <form action="pages/riwayat_kelas/riwayat_tambah_proses.php" method="POST" class="space-y-6">
            
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                <h2 class="text-2xl font-bold text-slate-800 mb-6">Tambah Data Riwayat</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Tahun Ajaran (Tujuan)</label>
                        <select name="tahun_pelajaran_id" required class="w-full px-4 py-3 text-sm bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500">
                            <option value="">Pilih Tahun Ajaran</option>
                            <?php while($th = mysqli_fetch_assoc($tahun_list)): ?>
                                <option value="<?= $th['id'] ?>"><?= $th['nama_tahun'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Kelas (Tujuan)</label>
                        <select name="kelas_id" required class="w-full px-4 py-3 text-sm bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500">
                            <option value="">Pilih Kelas</option>
                            <?php while($kls = mysqli_fetch_assoc($kelas_list)): ?>
                                <option value="<?= $kls['id'] ?>"><?= $kls['nama_kelas'] ?> (<?= $kls['tingkat'] ?>)</option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Filter Tahun Masuk</label>
                        <input type="number" id="filterTahunMasuk" placeholder="Contoh: 2025" class="w-full px-4 py-3 text-sm bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 shadow-inner">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto max-h-[500px]">
                    <table class="min-w-full text-sm text-left" id="tabelSiswa">
                        <thead>
                            <tr class="bg-slate-50/50 text-slate-400 text-[11px] uppercase tracking-widest font-bold sticky top-0 bg-white z-10">
                                <th class="px-8 py-4 w-10">
                                    <input type="checkbox" id="checkAll" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                </th>
                                <th class="px-8 py-4">Nis</th>
                                <th class="px-8 py-4">Nama</th>
                                <th class="px-8 py-4 text-center">Angkatan</th>
                                <th class="px-8 py-4">Alamat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php while($s = mysqli_fetch_assoc($siswa_list)): ?>
                            <tr class="hover:bg-slate-50/80 transition-all">
                                <td class="px-8 py-4">
                                    <input type="checkbox" name="siswa_ids[]" value="<?= $s['id'] ?>" class="siswa-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                </td>
                                <td class="px-8 py-4 text-slate-600"><?= $s['nis'] ?></td>
                                <td class="px-8 py-4 font-bold text-slate-800"><?= $s['nama_siswa'] ?></td>
                                <td class="px-8 py-4 text-center">
                                    <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-semibold col-tahun">
                                        <?= $s['tahun_masuk'] ?>
                                    </span>
                                </td>
                                <td class="px-8 py-4 text-slate-500 text-xs"><?= $s['alamat'] ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="p-6 bg-slate-50/50 flex items-center justify-between">
                    <p class="text-xs text-slate-400">* Gunakan filter angkatan untuk memudahkan pemilihan siswa per tahun masuk.</p>
                    <button type="submit" name="simpan" class="px-10 py-3 bg-indigo-600 text-white rounded-2xl font-bold text-sm hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all active:scale-95">
                        Simpan Data Riwayat
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('checkAll').onclick = function() {
    let checkboxes = document.querySelectorAll('.siswa-checkbox');
    for (let checkbox of checkboxes) {
        if(checkbox.closest('tr').style.display !== 'none') {
            checkbox.checked = this.checked;
        }
    }
}

document.getElementById('filterTahunMasuk').addEventListener('input', function() {
    let filterValue = this.value;
    let rows = document.querySelectorAll('#tabelSiswa tbody tr');
    
    rows.forEach(row => {
        let tahunText = row.querySelector('.col-tahun').innerText;
        if (filterValue === "" || tahunText.includes(filterValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
            row.querySelector('.siswa-checkbox').checked = false;
        }
    });
});
</script>