<?php 
include __DIR__ . '/../../config/connection.php';

$id = $_GET['id'];
$query = "SELECT * FROM aturan_poin WHERE id = '$id'";
$result = mysqli_query($connect, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: aturan_poin");
    exit();
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div class="animate__animated animate__fadeIn p-4 md:p-8">
    <div class="max-w-2xl mx-auto">
        <nav class="flex mb-4 text-slate-400 text-xs font-bold uppercase tracking-widest">
            <a href="aturan_poin" class="hover:text-indigo-600 transition-colors">Aturan Poin</a>
            <span class="mx-2">/</span>
            <span class="text-slate-800">Edit Data</span>
        </nav>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 md:p-8 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                    Edit Aturan Poin
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Ubah data aturan poin di bawah ini.
                </p>
            </div>

            <form action="aturan_poin_edit_proses" method="POST" class="p-6 md:p-8 space-y-6">
                <input type="hidden" name="id" value="<?= $data['id'] ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Minimal Poin <span class="text-rose-500">*</span></label>
                        <input type="number" name="min_poin" value="<?= $data['min_poin'] ?>" required
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white outline-none transition-all text-slate-700 font-medium">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Maksimal Poin <span class="text-rose-500">*</span></label>
                        <input type="number" name="max_poin" value="<?= $data['max_poin'] ?>" required
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white outline-none transition-all text-slate-700 font-medium">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700">Tindakan / Status <span class="text-rose-500">*</span></label>
                    <input type="text" name="tindakan" value="<?= htmlspecialchars($data['tindakan']) ?>" required
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white outline-none transition-all text-slate-700 font-medium">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700">Warna Badge <span class="text-rose-500">*</span></label>
                    <select name="warna" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white outline-none transition-all text-slate-700 font-medium cursor-pointer">
                        <option value="">Pilih Warna</option>
                        <option value="emerald" <?= $data['warna'] == 'emerald' ? 'selected' : '' ?> class="text-emerald-600 font-bold">Emerald (Hijau)</option>
                        <option value="yellow" <?= $data['warna'] == 'yellow' ? 'selected' : '' ?> class="text-yellow-600 font-bold">Yellow (Kuning)</option>
                        <option value="orange" <?= $data['warna'] == 'orange' ? 'selected' : '' ?> class="text-orange-600 font-bold">Orange (Oranye)</option>
                        <option value="rose" <?= $data['warna'] == 'rose' ? 'selected' : '' ?> class="text-rose-600 font-bold">Rose (Merah)</option>
                        <option value="slate" <?= $data['warna'] == 'slate' ? 'selected' : '' ?> class="text-slate-700 font-bold">Slate (Abu-abu)</option>
                    </select>
                </div>

                <div class="pt-6 flex gap-3">
                    <a href="aturan_poin" 
                       class="flex-1 text-center px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="flex-[2] px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors shadow-md shadow-indigo-200">
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
