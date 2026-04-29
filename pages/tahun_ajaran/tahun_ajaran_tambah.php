<?php
include __DIR__ . '/../../config/connection.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div class="animate__animated animate__fadeIn p-4 md:p-8">
    <div class="max-w-2xl mx-auto">
        
        <nav class="flex mb-4 text-slate-400 text-xs font-bold uppercase tracking-widest">
            <a href="index.php?page=tahun_ajaran" class="hover:text-indigo-600 transition-colors">Tahun Pelajaran</a>
            <span class="mx-2">/</span>
            <span class="text-slate-800">Tambah Baru</span>
        </nav>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-50 bg-slate-50/50">
                <h2 class="text-xl font-bold text-slate-800">Tambah Tahun Pelajaran</h2>
                <p class="text-sm text-slate-500">Daftarkan periode akademik baru ke dalam sistem</p>
            </div>
            
            <form action="index.php?page=tahun_ajaran_tambah_proses" method="POST" class="p-8 space-y-6">
                <div>
                    <label class="block text-[11px] font-bold uppercase text-slate-400 mb-2 ml-1 tracking-wider">Nama Tahun Pelajaran</label>
                    <input type="text" name="nama_tahun" placeholder="Contoh: 2025/2026" required
                           class="w-full px-5 py-3.5 bg-slate-50 border-2 border-transparent focus:border-indigo-100 focus:bg-white rounded-2xl focus:ring-4 focus:ring-indigo-50 transition-all text-sm font-medium text-slate-700 outline-none">
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase text-slate-400 mb-2 ml-1 tracking-wider">Status Awal</label>
                    <div class="relative">
                        <select name="status" required
                                class="w-full px-5 py-3.5 bg-slate-50 border-2 border-transparent focus:border-indigo-100 focus:bg-white rounded-2xl focus:ring-4 focus:ring-indigo-50 transition-all text-sm font-medium text-slate-700 outline-none appearance-none cursor-pointer">
                            <option value="Tidak Aktif">Tidak Aktif (Arsip)</option>
                            <option value="Aktif">Aktif (Berjalan)</option>
                        </select>
                        <div class="absolute right-5 top-4 pointer-events-none text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-50">
                    <a href="index.php?page=tahun_ajaran" 
                       class="px-6 py-3 text-sm font-bold text-slate-400 hover:text-rose-500 transition-colors uppercase tracking-widest">
                        Batal
                    </a>
                    <button type="submit" name="simpan" 
                            class="px-10 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl shadow-xl shadow-indigo-100 hover:shadow-indigo-200 transform hover:-translate-y-0.5 transition-all">
                        Simpan Tahun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>