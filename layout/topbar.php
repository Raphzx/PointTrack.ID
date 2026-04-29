<header
  class="bg-white/80 px-6 py-5 shadow-md rounded-2xl mx-4 mt-4
         flex justify-between items-center
         transition-all duration-300
         backdrop-blur-md z-10"
>
  <div>
    <h1 class="text-xl font-semibold text-slate-700">
      Ringkasan informasi pelanggaran siswa
    </h1>
    <p class="text-sm text-slate-400">Ringkasan pelanggaran</p>
  </div>

  <div class="relative" x-data="{ open:false }">
    <button
      @click="open = !open"
      class="flex items-center gap-3 px-4 py-2 rounded-xl bg-white/70
             hover:bg-white transition-all duration-300
             hover:shadow-lg active:scale-95"
    >
      <div
        class="h-10 w-10 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-600
                                            flex items-center justify-center text-white font-bold shadow-lg shadow-blue-100 uppercase">
        <?= user_initials($_SESSION['user_name']) ?>
      </div>

      <span class="text-sm font-medium text-slate-700">
        <?= htmlspecialchars($_SESSION['user_name']) ?>
      </span>

      <span
        class="transition-transform duration-300"
        :class="open ? 'rotate-180' : ''"
      >
        ▾
      </span>
    </button>

    <div
      x-show="open"
      x-cloak
      @click.outside="open = false"
      x-transition
      class="absolute right-0 mt-3 w-44 bg-white rounded-xl
             shadow-2xl border border-slate-100 overflow-hidden z-20"
    >
      <div class="px-4 py-3 border-b">
        <p class="text-xs text-slate-400">Login sebagai</p>
        <p class="text-sm font-semibold text-slate-700 truncate">
          <?= htmlspecialchars($_SESSION['user_name']) ?>
        </p>
      </div>

      <a
        href="pages/auth/logout.php"
        class="flex items-center gap-3 px-4 py-3
               text-red-600 hover:bg-red-50 transition group"
      >
        <i class="bx bx-log-out text-xl group-hover:-translate-x-1"></i>
        <span>Logout</span>
      </a>
    </div>
  </div>
</header>
