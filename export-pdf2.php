<?php
date_default_timezone_set('Asia/Makassar');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/connection.php';

use Mpdf\Mpdf;

$tahun_id = $_GET['tahun'] ?? '';
$kelas_id = $_GET['kelas'] ?? '';
$search   = $_GET['search'] ?? '';

$where = [];

if ($tahun_id !== '') {
    $tahun_id = mysqli_real_escape_string($connect, $tahun_id);
    $where[] = "rk.tahun_pelajaran_id = '$tahun_id'";
}

if ($kelas_id !== '') {
    $kelas_id = mysqli_real_escape_string($connect, $kelas_id);
    $where[] = "k.id = '$kelas_id'";
}

if ($search !== '') {
    $search = mysqli_real_escape_string($connect, $search);
    $where[] = "s.nama_siswa LIKE '%$search%'";
}

$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$query = "
SELECT
    p.tanggal,
    s.nis,
    s.nama_siswa,
    CONCAT(k.tingkat,' ',k.nama_kelas) AS kelas,
    jp.nama_pelanggaran,
    jp.poin,
    tp.nama_tahun
FROM pelanggaran p
JOIN riwayat_kelas rk ON p.riwayat_kelas_id = rk.id
JOIN siswa s ON rk.siswa_id = s.id
JOIN kelas k ON rk.kelas_id = k.id
LEFT JOIN tahun_pelajaran tp ON rk.tahun_pelajaran_id = tp.id
JOIN jenis_pelanggaran jp ON p.pelanggaran_id = jp.id
$whereSQL
ORDER BY p.tanggal DESC, s.nama_siswa ASC
";

$result = mysqli_query($connect, $query);

if (!$result) {
    die('Query Error: ' . mysqli_error($connect));
}

/* FILTER INFO */
$infoFilter = [];

if ($tahun_id) {
    $q = mysqli_query($connect, "SELECT nama_tahun FROM tahun_pelajaran WHERE id='$tahun_id'");
    if ($q && mysqli_num_rows($q)) {
        $t = mysqli_fetch_assoc($q);
        $infoFilter[] = "Tahun: " . $t['nama_tahun'];
    }
}

if ($kelas_id) {
    $q = mysqli_query($connect, "SELECT tingkat,nama_kelas FROM kelas WHERE id='$kelas_id'");
    if ($q && mysqli_num_rows($q)) {
        $k = mysqli_fetch_assoc($q);
        $infoFilter[] = "Kelas: " . $k['tingkat'] . ' ' . $k['nama_kelas'];
    }
}

if ($search) {
    $infoFilter[] = "Cari: " . $search;
}

$infoText = implode(' | ', $infoFilter);

/* HTML */
$html = '
<style>
body{
    font-family: dejavusans;
    font-size:10pt;
    color:#0f172a;
    line-height:1.5;
}

.title{
    text-align:center;
    font-size:16pt;
    font-weight:bold;
    margin-top:5px;
}

.subtitle{
    text-align:center;
    font-size:10pt;
    color:#64748b;
    margin-bottom:8px;
}

.info{
    text-align:center;
    font-size:9pt;
    color:#475569;
    margin-bottom:12px;
}

table{
    width:100%;
    border-collapse:collapse;
    table-layout:fixed;
}

th,td{
    border:1px solid #e5e7eb;
    padding:7px;
    word-wrap:break-word;
}

th{
    background:#f1f5f9;
    font-weight:bold;
    text-align:center;
    font-size:9.5pt;
}

tr:nth-child(even){
    background:#f8fafc;
}

.poin{
    color:#dc2626;
    font-weight:bold;
}

.tanggal{
    color:#475569;
}

.footer{
    font-size:9pt;
    color:#64748b;
}
</style>

<div class="title">RIWAYAT PELANGGARAN SISWA</div>
<div class="subtitle">SMK ISFI BANJARMASIN</div>
<div class="info">'.$infoText.'</div>

<table>
<thead>
<tr>
    <th width="5%">No</th>
    <th width="15%">Tanggal</th>
    <th width="15%">NIS</th>
    <th>Nama Siswa</th>
    <th width="15%">Kelas</th>
    <th>Pelanggaran</th>
    <th width="10%">Poin</th>
</tr>
</thead>
<tbody>
';

$no = 1;

if (mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {

        $tgl = !empty($row['tanggal'])
            ? date('d M Y', strtotime($row['tanggal']))
            : '-';

        $html .= '
        <tr>
            <td align="center">'.$no.'</td>
            <td align="center" class="tanggal">'.$tgl.'</td>
            <td>'.htmlspecialchars($row['nis']).'</td>
            <td>'.htmlspecialchars($row['nama_siswa']).'</td>
            <td align="center">'.htmlspecialchars($row['kelas']).'</td>
            <td>'.htmlspecialchars($row['nama_pelanggaran']).'</td>
            <td align="center" class="poin">-'.$row['poin'].'</td>
        </tr>
        ';
        $no++;
    }

} else {
    $html .= '
    <tr>
        <td colspan="7" align="center" style="color:#64748b;">Data tidak ditemukan</td>
    </tr>';
}

$html .= '
</tbody>
</table>
';

/* MPDF */
$mpdf = new Mpdf([
    'format' => 'A4',
    'orientation' => 'P',
    'margin_top' => 35,
    'margin_bottom' => 18,
    'default_font' => 'dejavusans'
]);

/* HEADER */
$mpdf->SetHTMLHeader('
<div style="text-align:center;">
    <img src="layout/img/logo.webp" style="height:65px;"><br>
    <hr style="margin-top:10px; border:0.5px solid #e5e7eb;">
</div>
');

/* FOOTER */
$mpdf->SetHTMLFooter('
<div style="border-top:1px solid #e5e7eb; font-size:9pt; color:#64748b; padding-top:6px;">
    <table width="100%">
        <tr>
            <td align="left">Dicetak: '.date('d/m/Y H:i').' WITA</td>
            <td align="right">Halaman {PAGENO}</td>
        </tr>
    </table>
</div>
');

$mpdf->WriteHTML($html);
$mpdf->Output('riwayat-pelanggaran.pdf','D');