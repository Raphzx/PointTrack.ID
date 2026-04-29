<?php 
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/connection.php';

$user = $_SESSION['user_role'] ?? null;

if (!in_array($user, ['admin','guru'])) {
    header("Location: ../auth/login.php");
    exit;
}

function countData($connect, $sql) {
    $q = mysqli_query($connect, $sql);
    $r = mysqli_fetch_assoc($q);
    return $r['total'] ?? 0;
}

$pie = mysqli_query($connect,"
SELECT jp.nama_pelanggaran, COUNT(*) total
FROM pelanggaran p
JOIN jenis_pelanggaran jp ON p.pelanggaran_id = jp.id
GROUP BY jp.id, jp.nama_pelanggaran
");

$pie_label=[]; $pie_data=[];
while($d=mysqli_fetch_assoc($pie)){
    $pie_label[]=$d['nama_pelanggaran'];
    $pie_data[]=$d['total'];
}

$line = mysqli_query($connect,"
SELECT DATE_FORMAT(tanggal,'%Y-%m') bulan, COUNT(*) total
FROM pelanggaran
GROUP BY DATE_FORMAT(tanggal,'%Y-%m')
ORDER BY bulan ASC
");

$line_label=[]; $line_data=[];
while($d=mysqli_fetch_assoc($line)){
    $line_label[]=$d['bulan'];
    $line_data[]=$d['total'];
}

// BAR (TOP 5)
$bar = mysqli_query($connect,"
SELECT jp.nama_pelanggaran, COUNT(*) total
FROM pelanggaran p
JOIN jenis_pelanggaran jp ON p.pelanggaran_id = jp.id
GROUP BY jp.id, jp.nama_pelanggaran
ORDER BY total DESC
LIMIT 5
");

$bar_label=[]; $bar_data=[];
while($d=mysqli_fetch_assoc($bar)){
    $bar_label[]=$d['nama_pelanggaran'];
    $bar_data[]=$d['total'];
}

// TABLE
$recent = mysqli_query($connect,"
SELECT s.nama_siswa, jp.nama_pelanggaran, p.tanggal
FROM pelanggaran p
JOIN jenis_pelanggaran jp ON p.pelanggaran_id = jp.id
JOIN riwayat_kelas rk ON p.riwayat_kelas_id = rk.id
JOIN siswa s ON rk.siswa_id = s.id
ORDER BY p.tanggal DESC
LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelanggaran Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.8s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'pulse-slow': 'pulse 3s infinite',
                        'slide': 'slideText 3s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideText: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-3px)' }
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- HEADER -->
        <div class="text-center mb-12 animate-fade-in">
            <h1 class="text-4xl lg:text-5xl font-black bg-gradient-to-r from-slate-800 via-blue-800 to-indigo-900 bg-clip-text text-transparent mb-4">
                Dashboard Pelanggaran
            </h1>
            <p class="text-xl text-slate-600 font-medium">Analisis Data Pelanggaran Siswa Real-time</p>
        </div>

        <!-- STATS CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <?php 
            $stats = [
                ['icon'=>'users', 'title'=>'Total Siswa', 'value'=>countData($connect,"SELECT COUNT(*) total FROM siswa"), 'color'=>'blue'],
                ['icon'=>'building-columns', 'title'=>'Total Kelas', 'value'=>countData($connect,"SELECT COUNT(*) total FROM kelas"), 'color'=>'orange'],
                ['icon'=>'exclamation-triangle', 'title'=>'Jenis Pelanggaran', 'value'=>countData($connect,"SELECT COUNT(*) total FROM jenis_pelanggaran"), 'color'=>'purple'],
                ['icon'=>'list-check', 'title'=>'Total Pelanggaran', 'value'=>countData($connect,"SELECT COUNT(*) total FROM pelanggaran"), 'color'=>'red']
            ];
            
            foreach($stats as $i => $stat): 
            ?>
            <div class="group bg-white/80 backdrop-blur-xl p-8 rounded-3xl shadow-xl border border-white/50 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 animate-slide-up" style="animation-delay: <?= $i * 100 ?>ms">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-4 bg-gradient-to-br from-<?= $stat['color'] ?>-500 to-<?= $stat['color'] ?>-600 rounded-2xl shadow-lg">
                        <i class="fas fa-<?= $stat['icon'] ?> text-2xl text-white"></i>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-r from-<?= $stat['color'] ?>-500 to-<?= $stat['color'] ?>-600 rounded-full opacity-20 group-hover:opacity-40 transition-opacity duration-300 animate-pulse-slow"></div>
                </div>
                <p class="text-slate-600 font-medium text-sm uppercase tracking-wider mb-2 opacity-80"><?= $stat['title'] ?></p>
                <p class="text-4xl font-black bg-gradient-to-r from-<?= $stat['color'] ?>-600 to-<?= $stat['color'] ?>-700 bg-clip-text text-transparent group-hover:scale-105 transition-transform duration-300">
                    <?= number_format($stat['value']) ?>
                </p>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- CHARTS ROW -->
        <div class="grid lg:grid-cols-2 gap-8 mb-12">
            <!-- PIE CHART -->
            <div class="bg-white/80 backdrop-blur-xl p-8 rounded-3xl shadow-2xl border border-white/50 hover:shadow-3xl transition-all duration-500 animate-slide-up" style="animation-delay: 200ms">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        <i class="fas fa-chart-pie mr-3 text-blue-500"></i>
                        Distribusi Jenis Pelanggaran
                    </h2>
                </div>
                <div class="relative">
                    <canvas id="pieChart" class="h-80 w-full"></canvas>
                </div>
            </div>

            <!-- LINE CHART -->
            <div class="bg-white/80 backdrop-blur-xl p-8 rounded-3xl shadow-2xl border border-white/50 hover:shadow-3xl transition-all duration-500 animate-slide-up" style="animation-delay: 300ms">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        <i class="fas fa-chart-line mr-3 text-emerald-500"></i>
                        Tren Bulanan
                    </h2>
                </div>
                <canvas id="lineChart" class="h-80 w-full"></canvas>
            </div>
        </div>

        <!-- BAR CHART & TABLE ROW -->
        <div class="grid lg:grid-cols-2 gap-8">
            <!-- BAR CHART -->
            <div class="bg-white/80 backdrop-blur-xl p-8 rounded-3xl shadow-2xl border border-white/50 hover:shadow-3xl transition-all duration-500 animate-slide-up" style="animation-delay: 400ms">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        <i class="fas fa-fire mr-3 text-orange-500"></i>
                        Top 5 Pelanggaran
                    </h2>
                </div>
                <canvas id="barChart" class="h-80 w-full"></canvas>
            </div>

            <!-- RECENT TABLE -->
            <div class="bg-white/80 backdrop-blur-xl p-8 rounded-3xl shadow-2xl border border-white/50 hover:shadow-3xl transition-all duration-500 animate-slide-up" style="animation-delay: 500ms">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        <i class="fas fa-clock-rotate-left mr-3 text-purple-500"></i>
                        Pelanggaran Terbaru
                    </h2>
                    <span class="px-4 py-2 bg-gradient-to-r from-orange-400 to-red-500 text-white text-sm font-semibold rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
                        5 Terakhir
                    </span>
                </div>
                
                <div class="space-y-4">
                    <?php while($r=mysqli_fetch_assoc($recent)): ?>
                    <div class="group flex items-center p-4 bg-gradient-to-r from-slate-50 to-blue-50 rounded-2xl hover:from-slate-100 hover:to-blue-100 border-l-4 border-orange-400 hover:shadow-md transition-all duration-300 hover:-translate-x-2">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-red-500 rounded-2xl flex items-center justify-center shadow-lg mr-4 flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-slate-800 truncate"><?= htmlspecialchars($r['nama_siswa']) ?></p>
                            <p class="text-sm text-slate-600 truncate"><?= htmlspecialchars($r['nama_pelanggaran']) ?></p>
                        </div>
                        <div class="text-right ml-4">
                            <p class="font-mono text-sm text-slate-500"><?= date('d/m/Y', strtotime($r['tanggal'])) ?></p>
                            <span class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded-full font-medium">
                                Baru
                            </span>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Enhanced PIE CHART with doughnut style
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($pie_label); ?>,
                datasets: [{
                    data: <?= json_encode($pie_data); ?>,
                    backgroundColor: [
                        'rgba(59,130,246,0.85)', 'rgba(247,115,22,0.85)', 'rgba(96,165,250,0.85)', 
                        'rgba(251,146,60,0.85)', 'rgba(147,197,253,0.85)'
                    ],
                    borderColor: [
                        'rgba(59,130,246,1)', 'rgba(247,115,22,1)', 'rgba(96,165,250,1)', 
                        'rgba(251,146,60,1)', 'rgba(147,197,253,1)'
                    ],
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverOffset: 10
                }]
            },
            options: {
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 25,
                            usePointStyle: true,
                            font: { size: 13, weight: '600' },
                            color: '#374151'
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    duration: 2000
                }
            }
        });

        // Enhanced LINE CHART
        new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels: <?= json_encode($line_label); ?>,
                datasets: [{
                    data: <?= json_encode($line_data); ?>,
                    borderColor: 'rgba(16,185,129,1)',
                    backgroundColor: 'rgba(16,185,129,0.1)',
                    borderWidth: 4,
                    fill: true,
                    tension: 0.5,
                    pointBackgroundColor: 'rgba(16,185,129,1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3,
                    pointRadius: 8,
                    pointHoverRadius: 12
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { color: 'rgba(0,0,0,0.05)' } },
                    y: { 
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { font: { weight: '600' } }
                    }
                },
                animation: {
                    duration: 2500,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Enhanced BAR CHART
        const barCtx = document.getElementById('barChart').getContext('2d');
        const gradient = barCtx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(251,146,60,0.9)');
        gradient.addColorStop(1, 'rgba(251,146,60,0.2)');

        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($bar_label); ?>,
                datasets: [{
                    label: 'Jumlah',
                    data: <?= json_encode($bar_data); ?>,
                    backgroundColor: gradient,
                    borderColor: 'rgba(247,115,22,1)',
                    borderWidth: 2,
                    borderRadius: 12,
                    borderSkipped: false,
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    x: { 
                        grid: { display: false },
                        ticks: { font: { weight: '700' } }
                    },
                    y: { 
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { font: { weight: '600' } }
                    }
                },
                animation: {
                    duration: 2000,
                    delay: 500,
                    easing: 'easeOutCubic'
                }
            }
        });
    </script>
</body>
</html>