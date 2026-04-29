<?php
include __DIR__ . '/../../config/connection.php';

if (!isset($_GET['id'])) {
    header("Location: index.php?page=users");
    exit;
}

$id = intval($_GET['id']);

$stmt = mysqli_prepare($connect, "SELECT * FROM user WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$user) {
    echo "<div class='p-8 text-center text-slate-500'>User tidak ditemukan.</div>";
    exit;
}
?>

<div class="animate__animated animate__fadeIn p-4 md:p-8 relative z-10">
    <div class="bg-white rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-slate-100 overflow-hidden max-w-2xl mx-auto">
        
        <div class="p-8 border-b border-slate-50 bg-slate-50/50">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-2xl bg-amber-500 flex items-center justify-center text-white shadow-lg shadow-amber-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-800 tracking-tight">Edit Pengguna</h2>
                    <p class="text-sm text-slate-500">Perbarui informasi akun <strong><?= htmlspecialchars($user['fullname']) ?></strong></p>
                </div>
            </div>
        </div>

        <form action="index.php?page=users_edit_proses" method="POST" class="p-8 space-y-6">
            <input type="hidden" name="id" value="<?= $user['id'] ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Nama Lengkap</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($user['fullname']) ?>" 
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-400 focus:outline-none transition-all duration-300" required />
                </div>
                
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Alamat Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" 
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-400 focus:outline-none transition-all duration-300" required />
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">
                    Password <span class="normal-case font-medium text-slate-400">(Kosongkan jika tidak ingin diubah)</span>
                </label>
                <div class="relative group">
                    <div class="absolute left-4 top-3.5 text-slate-300 group-focus-within:text-blue-500 transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input type="password" name="password" placeholder="Masukkan password baru" 
                           class="w-full rounded-2xl border border-slate-200 pl-11 pr-4 py-3 text-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-400 focus:outline-none transition-all duration-300" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Role Pengguna</label>
                    <div class="relative group">
                        <div class="absolute left-4 top-3.5 text-slate-300 group-focus-within:text-blue-500 transition-colors duration-300 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <select name="role" 
                                class="w-full rounded-2xl border border-slate-200 pl-11 pr-10 py-3 text-sm text-slate-700 bg-white hover:border-blue-300 focus:ring-4 focus:ring-blue-100 focus:border-blue-400 focus:outline-none transition-all duration-300 appearance-none cursor-pointer shadow-sm" required>
                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="guru" <?= $user['role'] === 'guru' ? 'selected' : '' ?>>Guru</option>
                        </select>
                        <div class="absolute right-4 top-4 text-slate-400 pointer-events-none group-focus-within:rotate-180 transition-transform duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Status Akun</label>
                    <div class="relative group">
                        <div class="absolute left-4 top-3.5 text-slate-300 group-focus-within:text-blue-500 transition-colors duration-300 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <select name="status" 
                                class="w-full rounded-2xl border border-slate-200 pl-11 pr-10 py-3 text-sm text-slate-700 bg-white hover:border-blue-300 focus:ring-4 focus:ring-blue-100 focus:border-blue-400 focus:outline-none transition-all duration-300 appearance-none cursor-pointer shadow-sm" required>
                            <option value="aktif" <?= $user['status'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= $user['status'] === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                        <div class="absolute right-4 top-4 text-slate-400 pointer-events-none group-focus-within:rotate-180 transition-transform duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-50">
                <a href="index.php?page=users" 
                   class="px-6 py-3 rounded-2xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all duration-300">
                    Batal
                </a>
                <button type="submit" 
                        class="px-8 py-3 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold transition-all duration-300 shadow-lg shadow-amber-100 hover:-translate-y-1 active:scale-95">
                    Update Pengguna
                </button>
            </div>
        </form>
    </div>
</div>