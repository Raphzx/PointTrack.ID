<?php
require_once __DIR__ . '/../../config/connection.php';

$dataSiswa = null;
$pelanggaranList = [];
$totalPoin = 0;
$error = '';
$showModal = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nis = $_POST['nis'];
    $action = $_POST['action'] ?? '';

    if ($action === 'close_modal') {
        $showModal = false;
    } else {
        $stmt = mysqli_prepare($connect, "SELECT id, nama_siswa FROM siswa WHERE nis = ?");
        mysqli_stmt_bind_param($stmt, "s", $nis);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $dataSiswa = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($dataSiswa) {
            $query = "
                SELECT jp.nama_pelanggaran, jp.poin, p.tanggal
                FROM pelanggaran p
                JOIN riwayat_kelas rk ON p.riwayat_kelas_id = rk.id
                JOIN jenis_pelanggaran jp ON p.pelanggaran_id = jp.id
                WHERE rk.siswa_id = ?
                ORDER BY p.tanggal DESC
            ";

            $stmt = mysqli_prepare($connect, $query);
            mysqli_stmt_bind_param($stmt, "i", $dataSiswa['id']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
                $pelanggaranList[] = $row;
                $totalPoin += $row['poin'];
            }
            mysqli_stmt_close($stmt);
            
            $showModal = true;
        } else {
            $error = "NIS tidak ditemukan";
        }
    }
}
function pointBadge($p){
    if($p < 25)  return 'bg-emerald-100 text-emerald-600 ring-emerald-200';
    if($p < 50)  return 'bg-yellow-100 text-yellow-600 ring-yellow-200';
    if($p < 75)  return 'bg-orange-100 text-orange-600 ring-orange-200';
    if($p < 100) return 'bg-rose-100 text-rose-600 ring-rose-200';
    return 'bg-slate-200 text-slate-700 ring-slate-300';
}

function statusLabel($p){
    if($p < 25)  return 'Aman';
    if($p < 50)  return 'Panggilan Siswa';
    if($p < 75)  return 'Panggilan Wali / Orang Tua';
    if($p < 100) return 'Tidak Naik Kelas';
    return 'Dropout';
}

function statusCircle($p){
    if($p < 25)  return 'bg-emerald-100 text-emerald-600';
    if($p < 50)  return 'bg-yellow-100 text-yellow-600';
    if($p < 75)  return 'bg-orange-100 text-orange-600';
    if($p < 100) return 'bg-rose-100 text-rose-600';
    return 'bg-slate-200 text-slate-700';
}

function statusText($p){
    if($p < 25)  return 'text-emerald-600';
    if($p < 50)  return 'text-yellow-500';
    if($p < 75)  return 'text-orange-500';
    if($p < 100) return 'text-rose-600';
    return 'text-slate-900';
}
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Cek Pelanggaran | Sistem Pelanggaran</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <style>
    
    @keyframes gradientBG { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
    .animated-bg { background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab); background-size: 400% 400%; animation: gradientBG 15s ease infinite; }
    
    @keyframes fadeSlide { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .animate-entry { animation: fadeSlide 0.8s ease-out forwards; }
    
    .glass { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); }
    
    .ripple { position: absolute; width: 100px; height: 100px; background: rgba(255,255,255,0.4); border-radius: 50%; transform: translate(-50%,-50%); animation: rippleEffect .6s ease-out; pointer-events: none; }
    @keyframes rippleEffect { from { scale: 0; opacity: 1; } to { scale: 4; opacity: 0; } }
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-8px); } 50% { transform: translateX(8px); } 75% { transform: translateX(-4px); } }
    .shake { animation: shake .45s ease; }

    @keyframes modalSlide { from { opacity: 0; transform: translateY(50px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
    .modal-enter { animation: modalSlide 0.5s ease-out forwards; }
    
    body {
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      min-height: 100vh !important;
      margin: 0 !important;
      padding: 1rem !important;
    }
    
    .w-full.max-w-md {
      max-width: 32rem !important;
      width: 100% !important;
      margin-left: auto !important;
      margin-right: auto !important;
    }
    
    @media (min-width: 1024px) {
      .glass {
        padding: 2.5rem !important;
      }
    }
    
    @media (min-width: 768px) and (max-width: 1280px) {
      body {
        padding: 1.5rem !important;
      }
      .w-full.max-w-md {
        max-width: 30rem !important;
      }
    }
    
    @media (min-width: 1280px) {
      body {
        padding: 2rem !important;
      }
      .w-full.max-w-md {
        max-width: 34rem !important;
      }
    }
    
    @media (max-width: 640px) {
      body {
        padding: 0.75rem !important;
      }
      .glass {
        padding: 1.5rem !important;
      }
    }
    
    .fixed.top-6.right-6 {
      position: fixed !important;
      top: 1.5rem !important;
      right: 1.5rem !important;
      z-index: 50 !important;
    }
    
    .fixed.inset-0 {
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      padding: 1rem !important;
    }
    
    .modal-container {
      max-width: 56rem !important;
      width: 100% !important;
      max-height: 85vh !important;
      display: flex !important;
      flex-direction: column !important;
    }
    
    .modal-body-scroll {
      flex: 1 !important;
      overflow-y: auto !important;
      min-height: 0 !important;
    }
    
    .modal-footer {
      flex-shrink: 0 !important;
    }
    
    @media (min-width: 768px) {
      .modal-container {
        width: 85% !important;
      }
    }
    
    @media (min-width: 1024px) {
      .modal-container {
        width: 80% !important;
        max-width: 60rem !important;
      }
    }
    
    @media (min-width: 1280px) {
      .modal-container {
        width: 75% !important;
        max-width: 64rem !important;
      }
    }
  </style>
</head>
<body class="min-h-screen animated-bg p-4 relative overflow-hidden">
  
  <div class="absolute top-0 left-0 w-full h-full bg-black/10 z-0"></div>

  <a href="/sistemptt_demo/pages/auth/login.php" id="loginBtnGuest" 
     class="fixed top-6 right-6 z-50 flex items-center gap-2 px-6 py-3 bg-white/20 hover:bg-white/40 text-white rounded-2xl backdrop-blur-lg transition-all shadow-xl font-bold opacity-0 -translate-y-5" 
     style="transition: all 0.6s ease 0.2s;">
    <i class='bx bx-log-in-circle text-xl'></i> <span>Login</span>
  </a>

  <div class="w-full max-w-md relative z-10">
    <div class="glass p-8 rounded-[2.5rem] shadow-2xl animate-entry <?= isset($error) && $error ? 'shake border-red-400' : '' ?>">
      <div class="text-center mb-8">
        <img src="/sistemptt_demo/layout/img/logo.webp" class="mx-auto w-24 h-24 rounded-3xl shadow-2xl mb-4 hover:scale-110 transition-transform duration-500">
        <h1 class="text-2xl font-black text-slate-800 tracking-tight leading-none uppercase">SMK ISFI</h1>
        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Cek Pelanggaran</p>
      </div>

      <form method="POST" id="searchForm" class="space-y-5">
        <input type="hidden" name="action" value="search">
        <?php if (isset($error) && $error): ?>
          <div class="bg-red-50 text-red-600 px-4 py-3 rounded-xl border border-red-100 text-sm font-bold text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="form-item opacity-0">
          <label class="text-xs font-bold text-slate-500 ml-1 uppercase mb-1 block">NIS Siswa</label>
          <div class="relative">
            <i class='bx bx-id-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl'></i>
            <input type="text" name="nis" required placeholder="Masukkan NIS" value="<?= htmlspecialchars($_POST['nis'] ?? '') ?>"
              class="w-full pl-12 pr-4 py-4 bg-slate-50 border-2 border-transparent focus:border-indigo-500 rounded-2xl outline-none transition-all font-semibold">
          </div>
        </div>

        <button type="submit" id="searchBtn" class="form-item opacity-0 w-full bg-slate-900 hover:bg-black text-white py-4 rounded-2xl font-black tracking-widest transition-all active:scale-95 shadow-xl relative overflow-hidden">
          CARI DATA
        </button>
      </form>
    </div>
    <p class="text-center text-[10px] font-bold text-white/60 mt-8 uppercase tracking-[0.3em] animate-entry" style="animation-delay: 1s">© 2026 SMK ISFI Banjarmasin</p>
  </div>

  <?php if ($showModal && $dataSiswa): ?>
  <div id="modal"
       class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 opacity-0 invisible"
       style="transition: all 0.4s ease;">
    
    <div class="modal-container relative glass rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col" style="max-height: 85vh;">
      
      <div class="p-6 md:p-8 border-b border-white/20 flex-shrink-0">
        <div class="flex justify-between items-start gap-6 flex-wrap">
          <div>
            <h2 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">
              <?= htmlspecialchars($dataSiswa['nama_siswa']) ?>
            </h2>
            <p class="text-xs md:text-sm font-bold text-slate-500 uppercase tracking-widest mt-1">Hasil Pencarian Pelanggaran</p>
          </div>

          <div class="flex items-center gap-4 text-right">
            <div>
              <p class="text-xs uppercase tracking-wide text-slate-400 font-bold">Total Poin</p>
              <p class="text-lg md:text-xl font-black <?= statusText($totalPoin) ?>">
                <?= statusLabel($totalPoin) ?>
              </p>
            </div>

            <div class="<?= statusCircle($totalPoin) ?> w-16 h-16 md:w-20 md:h-20 rounded-2xl md:rounded-3xl flex items-center justify-center text-xl md:text-2xl font-black shadow-2xl ring-4 ring-white/50">
              <?= $totalPoin ?>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-body-scroll p-6 md:p-8 bg-slate-50/80">
        <div class="space-y-4">
          <?php foreach ($pelanggaranList as $p): ?>
          <div class="glass p-4 md:p-6 rounded-2xl hover:shadow-xl transition-all border-white/30 hover:-translate-y-1">
            <div class="flex justify-between items-start gap-4 flex-wrap">
              <div class="flex gap-4 items-start flex-1 min-w-[200px]">
                <div class="w-3 h-3 rounded-full mt-2.5 <?= statusCircle($p['poin']) ?> shadow-lg"></div>
                <div>
                  <p class="font-black text-slate-800 text-base md:text-lg"><?= htmlspecialchars($p['nama_pelanggaran']) ?></p>
                  <p class="text-xs md:text-sm text-slate-500 font-semibold mt-1"><?= date('d M Y', strtotime($p['tanggal'])) ?></p>
                </div>
              </div>

              <span class="<?= pointBadge($p['poin']) ?> inline-flex items-center px-3 md:px-5 py-1.5 md:py-2.5 rounded-xl md:rounded-2xl text-xs md:text-sm font-black ring-2 ring-inset shadow-lg">
                <?= $p['poin'] ?> Poin
              </span>
            </div>
          </div>
          <?php endforeach; ?>

          <?php if (!$pelanggaranList): ?>
          <div class="text-center py-12 md:py-20">
            <i class='bx bx-check-circle text-5xl md:text-6xl text-emerald-400 mb-4'></i>
            <p class="text-xl md:text-2xl font-black text-slate-400 mb-2">Selamat!</p>
            <p class="text-sm md:text-base text-slate-500 font-semibold">Tidak ada riwayat pelanggaran</p>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="modal-footer p-6 md:p-8 border-t border-white/20 bg-white/50">
        <form method="POST" id="closeForm">
          <input type="hidden" name="action" value="close_modal">
          <input type="hidden" name="nis" value="<?= htmlspecialchars($_POST['nis'] ?? '') ?>">
          <button type="submit" id="closeBtn"
                  class="w-full bg-slate-900 hover:bg-black text-white py-3 md:py-4 rounded-xl md:rounded-2xl font-black tracking-widest transition-all active:scale-95 shadow-xl relative overflow-hidden">
            TUTUP
          </button>
        </form>
      </div>

    </div>
  </div>
  <?php endif; ?>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const formItems = document.querySelectorAll(".form-item");
  formItems.forEach((el, i) => {
    setTimeout(() => {
      el.style.transition = "all 0.6s ease";
      el.style.opacity = "1";
      el.style.transform = "translateY(0)";
    }, 300 + (i * 150));
  });

  const loginBtnGuest = document.getElementById("loginBtnGuest");
  if (loginBtnGuest) {
    loginBtnGuest.style.opacity = "1";
    loginBtnGuest.style.transform = "translateY(0)";
  }

  const searchBtn = document.getElementById("searchBtn");
  if (searchBtn) {
    searchBtn.addEventListener("click", function(e) {
      let x = e.clientX - e.target.getBoundingClientRect().left;
      let y = e.clientY - e.target.getBoundingClientRect().top;
      let ripple = document.createElement("span");
      ripple.className = "ripple";
      ripple.style.left = `${x}px`;
      ripple.style.top = `${y}px`;
      this.appendChild(ripple);
      setTimeout(() => ripple.remove(), 600);
    });
  }

  <?php if ($showModal && $dataSiswa): ?>
  setTimeout(() => {
    const modal = document.getElementById('modal');
    if (modal) {
      modal.style.opacity = '1';
      modal.style.visibility = 'visible';
      const modalContent = modal.querySelector('.modal-container');
      if (modalContent) modalContent.style.opacity = '1';
    }
  }, 100);
  <?php endif; ?>

  const closeBtn = document.getElementById("closeBtn");
  if (closeBtn) {
    closeBtn.addEventListener("click", function(e) {
      let x = e.clientX - e.target.getBoundingClientRect().left;
      let y = e.clientY - e.target.getBoundingClientRect().top;
      let ripple = document.createElement("span");
      ripple.className = "ripple";
      ripple.style.left = `${x}px`;
      ripple.style.top = `${y}px`;
      this.appendChild(ripple);
      setTimeout(() => ripple.remove(), 600);
    });
  }
});

document.addEventListener('click', function(e) {
  const modal = document.getElementById('modal');
  const modalContent = modal ? modal.querySelector('.modal-container') : null;
  if (modal && modalContent && !modalContent.contains(e.target) && modal.style.visibility === 'visible') {
    document.getElementById('closeForm').submit();
  }
});

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    const closeForm = document.getElementById('closeForm');
    if (closeForm) closeForm.submit();
  }
});
</script>
</body>
</html>