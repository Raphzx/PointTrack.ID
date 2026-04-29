<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div class="animate__animated animate__fadeIn p-4 md:p-8">
    <div class="max-w-2xl mx-auto">
        
        <a href="index.php?page=jenis_pelanggaran" class="inline-flex items-center gap-2 text-slate-500 hover:text-indigo-600 transition-colors mb-6 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span class="text-sm font-semibold">Kembali ke Daftar</span>
        </a>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="bg-slate-50/50 border-b border-slate-100 p-8">
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Tambah Kategori</h2>
                <p class="text-sm text-slate-500 mt-1">Buat kategori pelanggaran baru untuk sistem poin siswa.</p>
            </div>

            <form action="index.php?page=jenis_pelanggaran_tambah_proses" method="POST" class="p-8 space-y-6">
                
                <div class="space-y-2">
                    <label for="nama_pelanggaran" class="block text-sm font-bold text-slate-700 ml-1">Nama Pelanggaran</label>
                    <input type="text" name="nama_pelanggaran" id="nama_pelanggaran" required
                           placeholder="Contoh: Terlambat masuk sekolah"
                           class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all text-slate-800 placeholder:text-slate-400">
                </div>

                <div class="space-y-2">
                    <label for="poin" class="block text-sm font-bold text-slate-700 ml-1">Bobot Poin (1 - 100)</label>
                    <div class="relative">
                        <input type="number" name="poin" id="poin" required min="1" max="100"
                               placeholder="Masukan angka poin"
                               class="w-full pl-5 pr-16 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all text-slate-800">
                        <div class="absolute right-5 top-4 text-slate-400 font-bold pointer-events-none">PTS</div>
                    </div>
                </div>

                <div class="pt-4 flex flex-col sm:flex-row items-center gap-3">
                    <button type="submit" name="simpan"
                            class="w-full sm:flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-indigo-100 active:scale-[0.98]">
                        Simpan Kategori
                    </button>
                    <button type="reset" 
                            class="w-full sm:w-auto px-8 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-2xl transition-all">
                        Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>