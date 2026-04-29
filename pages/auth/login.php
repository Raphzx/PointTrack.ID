<?php
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/connection.php';

$error = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = mysqli_prepare($connect, "SELECT id, fullname, role, status, password FROM user WHERE email = ?"
    );
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user   = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($user) {
        if ($user['status'] !== 'aktif') {
            $error = "Akun Anda nonaktif. Hubungi admin.";
        } elseif (password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['fullname'];
            $_SESSION['user_role'] = $user['role'];
            header("Location: /sistemptt_demo/index.php?page=dashboard");
            exit;
        } else { $error = "Password salah."; }
    } else { $error = "Email tidak ditemukan."; }
}
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login | Sistem Pelanggaran</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
  </style>
</head>
<body class="min-h-screen animated-bg flex items-center justify-center p-4 relative overflow-hidden">
  
  <div class="absolute top-0 left-0 w-full h-full bg-black/10 z-0"></div>

  <a href="/sistemptt_demo/" id="backBtn" 
      class="fixed top-6 left-6 z-50 flex items-center gap-2 px-6 py-3 bg-white/20 hover:bg-white/40 text-white rounded-2xl backdrop-blur-lg transition-all shadow-xl font-bold opacity-0 -translate-y-5" 
      style="transition: all 0.6s ease 0.2s;">
    <i class='bx bx-arrow-back text-xl'></i> <span>Back</span>
  </a>

  <div class="w-full max-w-md relative z-10">
    <div class="glass p-8 rounded-[2.5rem] shadow-2xl animate-entry <?= $error ? 'shake border-red-400' : '' ?>">
      <div class="text-center mb-8">
        <img src="/sistemptt_demo/layout/img/logo.webp" class="mx-auto w-24 h-24 rounded-3xl shadow-2xl mb-4 hover:scale-110 transition-transform duration-500">
        <h1 class="text-2xl font-black text-slate-800 tracking-tight leading-none uppercase">SMK ISFI</h1>
        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Sistem Pelanggaran</p>
      </div>

      <form method="POST" id="loginForm" class="space-y-5">
        <?php if ($error): ?>
          <div class="bg-red-50 text-red-600 px-4 py-3 rounded-xl border border-red-100 text-sm font-bold text-center"><?= $error ?></div>
        <?php endif; ?>

        <div class="form-item opacity-0">
          <label class="text-xs font-bold text-slate-500 ml-1 uppercase mb-1 block">Email Address</label>
          <div class="relative">
            <i class='bx bx-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl'></i>
            <input type="email" name="email" required placeholder="name@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
              class="w-full pl-12 pr-4 py-4 bg-slate-50 border-2 border-transparent focus:border-indigo-500 rounded-2xl outline-none transition-all font-semibold">
          </div>
        </div>

        <div class="form-item opacity-0">
          <label class="text-xs font-bold text-slate-500 ml-1 uppercase mb-1 block">Password</label>
          <div class="relative">
            <i class='bx bx-lock-alt absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl'></i>
            <input type="password" name="password" required placeholder="••••••••"
              class="w-full pl-12 pr-4 py-4 bg-slate-50 border-2 border-transparent focus:border-indigo-500 rounded-2xl outline-none transition-all font-semibold">
          </div>
        </div>

        <button type="submit" id="loginBtn" class="form-item opacity-0 w-full bg-slate-900 hover:bg-black text-white py-4 rounded-2xl font-black tracking-widest transition-all active:scale-95 shadow-xl relative overflow-hidden">
          LOGIN
        </button>
      </form>
    </div>
    <p class="text-center text-[10px] font-bold text-white/60 mt-8 uppercase tracking-[0.3em] animate-entry" style="animation-delay: 1s">© 2026 SMK ISFI Banjarmasin</p>
  </div>

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

      const backBtn = document.getElementById("backBtn");
      if (backBtn) {
        backBtn.style.opacity = "1";
        backBtn.style.transform = "translateY(0)";
      }

      const loginBtn = document.getElementById("loginBtn");
      if (loginBtn) {
        loginBtn.addEventListener("click", function(e) {
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
  </script>
  </script>
</body>
</html>
