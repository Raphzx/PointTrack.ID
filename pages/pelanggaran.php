<?php 
include __DIR__ . '/../config/connection.php';

$query = "SELECT * FROM jenis_pelanggaran ORDER BY poin DESC, nama_pelanggaran ASC";
$result = mysqli_query($connect, $query);
$total_data = $result ? mysqli_num_rows($result) : 0;
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div class="animate__animated animate__fadeIn p-4 md:p-8">
    <div class="max-w-5xl mx-auto space-y-6">
        
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 text-center md:text-left">
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Daftar Pelanggaran</h2>
            <p class="text-sm text-slate-500 mt-1">Acuan poin kedisiplinan siswa untuk tahun ajaran aktif</p>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-400 text-[11px] uppercase tracking-widest font-bold border-b border-slate-100">
                            <th class="px-8 py-5 w-16 text-center">No</th>
                            <th class="px-8 py-5">Jenis Pelanggaran</th>
                            <th class="px-8 py-5 text-center">Poin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php if ($total_data > 0): ?>
                            <?php 
$no = 1; 
while($item = mysqli_fetch_assoc($result)): 
    $poin = $item['poin'];
    $point_style = 'bg-rose-50 text-rose-700 ring-rose-200'; 
?>
<tr class="hover:bg-slate-50/50 transition-colors">
    <td class="px-8 py-5 text-center text-slate-400 font-medium">
        <?= $no++; ?>
    </td>
    <td class="px-8 py-5">
        <span class="font-bold text-slate-700 text-base">
            <?= htmlspecialchars($item['nama_pelanggaran']) ?>
        </span>
    </td>
    <td class="px-8 py-5 text-center">
        <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-xs font-black <?= $point_style ?> ring-1 ring-inset shadow-sm">
            <?= $poin ?> POIN
        </span>
    </td>
</tr>
<?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="px-8 py-24 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-slate-400 font-medium italic">Data pelanggaran belum tersedia.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>