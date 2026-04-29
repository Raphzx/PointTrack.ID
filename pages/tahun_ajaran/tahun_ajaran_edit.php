<?php
include __DIR__ . '/../../config/connection.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query_data = mysqli_query($connect, "SELECT * FROM tahun_pelajaran WHERE id = '$id'");
$data = mysqli_fetch_assoc($query_data);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php?page=tahun_ajaran';</script>";
    exit;
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div class="animate__animated animate__fadeIn p-4 md:p-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-50 bg-slate-50/50">
                <h2 class="text-xl font-bold text-slate-800">Edit Status Tahun Pelajaran</h2>
                <p class="text-sm text-slate-500">Ubah status aktif tanpa mengubah periode akademik</p>
            </div>
            
            <form action="index.php?page=tahun_ajaran_edit_proses" method="POST" class="p-8 space-y-6">
                <input type="hidden" name="id" value="<?= $data['id'] ?>">

                <div>
                    <label class="block text-[11px] font-bold uppercase text-slate-400 mb-2 ml-1">Nama Tahun Pelajaran</label>
                    <input type="text" name="nama_tahun" value="<?= htmlspecialchars($data['nama_tahun']) ?>" 
                           readonly 
                           class="w-full px-5 py-3.5 bg-slate-100 border-none rounded-2xl text-sm font-medium text-slate-500 cursor-not-allowed shadow-inner"
                           title="Nama tahun tidak dapat diubah">
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase text-slate-400 mb-2 ml-1">Status</label>
                    <div class="relative">
                        <select name="status" required
                                class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all text-sm font-medium appearance-none cursor-pointer">
                            <option value="Tidak Aktif" <?= $data['status'] == 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                            <option value="Aktif" <?= $data['status'] == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                        </select>
                        <div class="absolute right-5 top-4 pointer-events-none text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-50">
                    <a href="index.php?page=tahun_ajaran" class="px-6 py-3 text-sm font-semibold text-slate-500 hover:text-rose-500 transition-colors">Batal</a>
                    <button type="submit" name="update" 
                            class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl shadow-lg shadow-indigo-100 transition-all">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>