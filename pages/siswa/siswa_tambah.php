<?php
include __DIR__ . '/../../config/connection.php';

$query_kelas = mysqli_query($connect, "SELECT * FROM kelas ORDER BY tingkat ASC, nama_kelas ASC");

$query_tp = mysqli_query($connect, "SELECT * FROM tahun_pelajaran ORDER BY nama_tahun DESC");
?>

<div class="animate__animated animate__fadeIn p-4 md:p-8 relative z-10">
    <div class="bg-white rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-slate-100 overflow-hidden max-w-2xl mx-auto">
        
        <div class="p-8 border-b border-slate-50 bg-slate-50/50">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-800 tracking-tight">Tambah Siswa Baru</h2>
                    <p class="text-sm text-slate-500">Input data siswa dan tentukan kelas awal mereka.</p>
                </div>
            </div>
        </div>

        <form action="index.php?page=siswa_tambah_proses" method="POST" class="p-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Nomor Induk Siswa (NIS)</label>
                    <input type="text" name="nis" placeholder="Contoh: 2024001" 
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 focus:outline-none transition-all duration-300" required />
                </div>
                
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Nama Lengkap</label>
                    <input type="text" name="nama_siswa" placeholder="Masukkan Nama Siswa" 
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 focus:outline-none transition-all duration-300" required />
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Alamat Lengkap</label>
                <textarea name="alamat" rows="3" placeholder="Jl. Contoh No. 123..." 
                          class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 focus:outline-none transition-all duration-300"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Tahun Masuk</label>
                    <input type="number" name="tahun_masuk" value="<?= date('Y') ?>" 
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 focus:outline-none transition-all duration-300" required />
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Tahun Pelajaran Saat Ini</label>
                    <select name="tahun_pelajaran_id" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 focus:outline-none transition-all duration-300 appearance-none cursor-pointer bg-white" required>
                        <option value="" disabled selected>Pilih Tahun Pelajaran</option>
                        <?php while($tp = mysqli_fetch_assoc($query_tp)): ?>
                            <option value="<?= $tp['id'] ?>"><?= $tp['nama_tahun'] ?> (<?= $tp['status'] ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Penempatan Kelas</label>
                <div class="relative group">
                    <div class="absolute left-4 top-3.5 text-slate-300 group-focus-within:text-indigo-500 transition-colors duration-300 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <select name="kelas_id" 
                            class="w-full rounded-2xl border border-slate-200 pl-11 pr-10 py-3 text-sm text-slate-700 bg-white hover:border-indigo-300 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 focus:outline-none transition-all duration-300 appearance-none cursor-pointer shadow-sm" required>
                        <option value="" disabled selected>Pilih Kelas untuk Siswa Ini</option>
                        <?php while($kls = mysqli_fetch_assoc($query_kelas)): ?>
                            <option value="<?= $kls['id'] ?>">Tingkat <?= $kls['tingkat'] ?> - <?= $kls['nama_kelas'] ?></option>
                        <?php endwhile; ?>
                    </select>
                    <div class="absolute right-4 top-4 text-slate-400 pointer-events-none group-focus-within:rotate-180 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                <p class="text-[10px] text-slate-400 mt-1 ml-1">*Siswa akan otomatis tercatat dalam riwayat kelas yang dipilih.</p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-50">
                <a href="index.php?page=siswa" 
                   class="px-6 py-3 rounded-2xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all duration-300">
                    Batal
                </a>
                <button type="submit" 
                        class="px-8 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold transition-all duration-300 shadow-lg shadow-indigo-200 hover:-translate-y-1 active:scale-95">
                    Simpan Siswa
                </button>
            </div>
        </form>
    </div>
</div>