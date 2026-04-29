<?php
date_default_timezone_set('Asia/Makassar');

require __DIR__ . '/vendor/autoload.php';
include __DIR__ . '/config/connection.php';

use Mpdf\Mpdf;

$tahun = $_GET['tahun'] ?? '';
$kelas = $_GET['kelas'] ?? '';

$where = [];

if ($tahun !== '') {
    $tahun = mysqli_real_escape_string($connect, $tahun);
    $where[] = "tp.nama_tahun = '$tahun'";
}

if ($kelas !== '') {
    $kelas = mysqli_real_escape_string($connect, $kelas);
    $where[] = "CONCAT(k.tingkat,' ',k.nama_kelas) = '$kelas'";
}

$whereSql = '';
if (!empty($where)) {
    $whereSql = 'WHERE ' . implode(' AND ', $where);
}

$query = "
SELECT 
    s.nis,
    s.nama_siswa,
    k.nama_kelas,
    k.tingkat,
    tp.nama_tahun,
    SUM(jp.poin) AS total_poin
FROM pelanggaran p
JOIN riwayat_kelas rk ON p.riwayat_kelas_id = rk.id
JOIN siswa s ON rk.siswa_id = s.id
JOIN kelas k ON rk.kelas_id = k.id
LEFT JOIN tahun_pelajaran tp ON rk.tahun_pelajaran_id = tp.id
JOIN jenis_pelanggaran jp ON p.pelanggaran_id = jp.id
$whereSql
GROUP BY s.id, s.nis, s.nama_siswa, k.nama_kelas, k.tingkat, tp.nama_tahun
ORDER BY total_poin DESC
";

$result = mysqli_query($connect, $query);

$infoFilter = '';

if ($tahun) $infoFilter .= "Tahun: $tahun ";
if ($kelas) $infoFilter .= "Kelas: $kelas";

$html = '
<style>
body{
    font-family: sans-serif;
    font-size:10pt;
    color:#0f172a;
}
table{
    width:100%;
    border-collapse:collapse;
}
th,td{
    border:1px solid #e5e7eb;
    padding:6px;
}
th{
    background:#f8fafc;
    font-weight:bold;
    text-align:center;
}

.safe    { background:#dcfce7; }
.call    { background:#fef9c3; }
.parent  { background:#ffedd5; }
.nokelas { background:#fee2e2; }
.drop    { background:#e5e7eb; }

.poin-safe    { color:#16a34a; font-weight:bold; }
.poin-call    { color:#ca8a04; font-weight:bold; }
.poin-parent  { color:#ea580c; font-weight:bold; }
.poin-nokelas { color:#dc2626; font-weight:bold; }
.poin-drop    { color:#020617; font-weight:bold; }

.status{
    font-size:8pt;
    font-weight:bold;
}
</style>

<table>
<thead>
<tr>
    <th width="5%">No</th>
    <th width="15%">NIS</th>
    <th width="30%">Nama Siswa</th>
    <th width="20%">Kelas</th>
    <th width="15%">Tahun</th>
    <th width="15%">Poin</th>
</tr>
</thead>
<tbody>
';

$no = 1;
while ($row = mysqli_fetch_assoc($result)) {

    $total = (int)$row['total_poin'];

    if ($total < 25) {
        $class = 'safe';
        $poinClass = 'poin-safe';
        $label = 'Aman';
    } elseif ($total < 50) {
        $class = 'call';
        $poinClass = 'poin-call';
        $label = 'Panggilan Siswa';
    } elseif ($total < 75) {
        $class = 'parent';
        $poinClass = 'poin-parent';
        $label = 'Panggilan Wali / Orang Tua';
    } elseif ($total < 100) {
        $class = 'nokelas';
        $poinClass = 'poin-nokelas';
        $label = 'Tidak Naik Kelas';
    } else {
        $class = 'drop';
        $poinClass = 'poin-drop';
        $label = 'Dropout';
    }

    $html .= "
<tr class='{$class}'>
    <td align='center'>{$no}</td>
    <td>{$row['nis']}</td>
    <td>{$row['nama_siswa']}</td>
    <td>{$row['tingkat']} {$row['nama_kelas']}</td>
    <td>{$row['nama_tahun']}</td>
    <td align='center'>
        <div class='{$poinClass}'>{$total}</div>
        <div class='status'>{$label}</div>
    </td>
</tr>
";

    $no++;
}

$html .= '</tbody></table>';

$mpdf = new Mpdf([
    'format' => 'A4',
    'orientation' => 'P',
    'margin_top' => 35,
    'margin_bottom' => 18
]);

$mpdf->SetHTMLHeader('
<div style="text-align:center;">
    <img src="layout/img/logo.webp" style="height:70px;"><br>
    <div style="font-size:14pt; font-weight:bold; margin-top:6px;">
        SMK ISFI BANJARMASIN
    </div>
    <div style="font-size:10pt; color:#64748b;">
        Laporan Monitoring Poin Pelanggaran Siswa<br>
        '.$infoFilter.'
    </div>
    <hr style="margin-top:10px;">
</div>
');

$mpdf->SetHTMLFooter('
<div style="border-top:1px solid #e5e7eb; font-size:9pt; color:#64748b; padding-top:6px;">
    <table width="100%">
        <tr>
            <td align="left">
                Dicetak: '.date('d/m/Y H:i').' WITA
            </td>
            <td align="right">
                Halaman {PAGENO}
            </td>
        </tr>
    </table>
</div>
');

$mpdf->WriteHTML($html);
$mpdf->Output('laporan-poin-siswa.pdf','D');