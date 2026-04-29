<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div class="animate__animated animate__fadeIn p-4 md:p-8">
    <div class="max-w-2xl mx-auto">
        <a href="index.php?page=kelas" class="inline-flex items-center gap-2 text-slate-500 hover:text-indigo-600 transition-colors mb-6 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span class="text-sm font-semibold">Kembali ke Data Kelas</span>
        </a>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-50">
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Tambah Kelas Baru</h2>
                <p class="text-sm text-slate-500">Silakan lengkapi form di bawah untuk menambah data kelas.</p>
            </div>

            <form action="index.php?page=kelas_tambah_proses" method="POST" class="p-8 space-y-6">
                <div class="space-y-2">
                    <label for="nama_kelas" class="text-sm font-bold text-slate-700 ml-1">Nama Kelas</label>
                    <div class="relative">
                        <input type="text" name="nama_kelas" id="nama_kelas" required
                               placeholder="Contoh: Rekayasa Perangkat Lunak B"
                               class="w-full pl-4 pr-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all text-sm text-slate-800">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="tingkat" class="text-sm font-bold text-slate-700 ml-1">Tingkat</label>
                    <select name="tingkat" id="tingkat" required
                            class="w-full px-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all text-sm text-slate-800 cursor-pointer">
                        <option value="" disabled selected>Pilih Tingkat</option>
                        <option value="X">Tingkat X</option>
                        <option value="XI">Tingkat XI</option>
                        <option value="XII">Tingkat XII</option>
                    </select>
                </div>

                <div class="pt-4">
                    <button type="submit" name="submit"
                            class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-100 hover:shadow-indigo-200 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Simpan Data Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>