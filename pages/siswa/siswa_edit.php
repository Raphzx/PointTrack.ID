<?php
include __DIR__ . '/../../config/connection.php';

if (!isset($_GET['id'])) {
    header("Location: index.php?page=siswa");
    exit;
}

$id = intval($_GET['id']);

$query = "SELECT * FROM siswa WHERE id = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$siswa = mysqli_fetch_assoc($result);

if (!$siswa) {
    echo "<div class='p-8 text-center text-slate-500'>Data siswa tidak ditemukan.</div>";
    exit;
}
?>

<div class="animate__animated animate__fadeIn p-4 md:p-8 relative z-10">
    <div class="bg-white rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-slate-100 overflow-hidden max-w-2xl mx-auto">
        
        <div class="p-8 border-b border-slate-50 bg-slate-50/50">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-2xl bg-indigo-500 flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-800 tracking-tight">Edit Profil Siswa</h2>
                    <p class="text-sm text-slate-500">Memperbarui data dasar <strong><?= htmlspecialchars($siswa['nama_siswa']) ?></strong></p>
                </div>
            </div>
        </div>

        <form action="index.php?page=siswa_edit_proses" method="POST" class="p-8 space-y-6">
            <input type="hidden" name="id" value="<?= $siswa['id'] ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">NIS</label>
                    <input type="text" name="nis" value="<?= htmlspecialchars($siswa['nis']) ?>" 
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 focus:outline-none transition-all duration-300" required />
                </div>
                
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Nama Lengkap</label>
                    <input type="text" name="nama_siswa" value="<?= htmlspecialchars($siswa['nama_siswa']) ?>" 
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 focus:outline-none transition-all duration-300" required />
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Alamat</label>
                <textarea name="alamat" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 focus:outline-none transition-all duration-300"><?= htmlspecialchars($siswa['alamat']) ?></textarea>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Tahun Masuk</label>
                <input type="number" name="tahun_masuk" value="<?= $siswa['tahun_masuk'] ?>" 
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 focus:outline-none transition-all duration-300" required />
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-50">
                <a href="index.php?page=siswa" class="px-6 py-3 rounded-2xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">Batal</a>
                <button type="submit" class="px-8 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold transition-all shadow-lg shadow-indigo-100 hover:-translate-y-1">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>