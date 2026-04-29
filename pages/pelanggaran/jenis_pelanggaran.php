<?php 
include __DIR__ . '/../../config/connection.php';

$query = "SELECT * FROM jenis_pelanggaran ORDER BY id ASC";
$result = mysqli_query($connect, $query);
$total_data = mysqli_num_rows($result);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div class="animate__animated animate__fadeIn p-4 md:p-8">
    <div class="max-w-6xl mx-auto">

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">

            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                            Kategori Pelanggaran
                        </h2>
                        <p class="text-sm text-slate-500">
                            Total <span class="font-bold text-indigo-600"><?= $total_data ?></span> jenis pelanggaran terdaftar
                        </p>
                    </div>

                    <a href="index.php?page=jenis_pelanggaran_tambah"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-2xl transition-all shadow-md hover:shadow-indigo-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Data
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-400 text-[11px] uppercase tracking-widest font-bold">
                            <th class="px-8 py-5">Nama Pelanggaran</th>
                            <th class="px-8 py-5 text-center"">Poin</th>
                            <th class="px-8 py-5 text-right">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php if ($total_data > 0): ?>
                            <?php while($item = mysqli_fetch_assoc($result)): 
                                $point_color = 'bg-rose-50 text-rose-600 ring-rose-100'; 
                            ?>
                            <tr class="group hover:bg-slate-50/80 transition-all duration-200">
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800 text-base">
                                            <?= htmlspecialchars($item['nama_pelanggaran']) ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center"">
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-xs font-bold <?= $point_color ?> ring-1 ring-inset ring-current/10">
                                        <?= $item['poin'] ?> Poin
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="index.php?page=jenis_pelanggaran_edit&id=<?= $item['id'] ?>" 
                                           class="p-2.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all border border-transparent hover:border-indigo-100" 
                                           title="Edit Data">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <a href="index.php?page=jenis_pelanggaran_hapus&id=<?= $item['id'] ?>" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')"
                                           class="p-2.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all border border-transparent hover:border-rose-100" 
                                           title="Hapus Data">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="px-8 py-20 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-3xl bg-slate-50 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <p class="text-slate-400 font-medium italic">
                                        Belum ada data pelanggaran yang diinputkan.
                                    </p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
