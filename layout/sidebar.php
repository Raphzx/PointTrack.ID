<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$page = $_GET['page'] ?? 'dashboard';
function active(string $p, string $page): string {
    return $p === $page
        ? 'bg-slate-200 text-slate-900 font-semibold'
        : 'hover:bg-slate-200';
}
$role = $_SESSION['user_role'] ?? 'guru';
?>
<aside
  x-data="{
    adminMenu: localStorage.getItem('adminMenu') !== 'false',
    guruMenu: localStorage.getItem('guruMenu') !== 'false',
    init() {
      this.$watch('adminMenu', v => localStorage.setItem('adminMenu', v))
      this.$watch('guruMenu', v => localStorage.setItem('guruMenu', v))
    }
  }"
  x-cloak
  :class="open ? 'w-64' : 'w-20'"
  class="
    fixed top-0 left-0 z-40
    h-screen
    bg-slate-50 border-r
    overflow-hidden
    transition-all duration-500 ease-[cubic-bezier(.4,0,.2,1)]
  "
>
  <div
    class="px-6 py-5 flex items-center justify-between
           border-b text-slate-700 font-bold text-lg"
  >
<span x-show="open" x-transition>
    <span class="text-black">Point</span><span class="text-blue-600">Track</span><span class="text-red-600">.id</span>
</span>
    <button
      @click="open = !open"
      class="p-1 rounded hover:bg-slate-200 transition"
    >
      <svg
        class="w-6 h-6 transition-transform duration-300"
        :class="open ? 'rotate-0' : 'rotate-180'"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M4 12h16M4 6h16M4 18h16"
        />
      </svg>
    </button>
  </div>
  <nav
  class="
    px-3 py-4 space-y-1 text-slate-600
    overflow-y-auto no-scrollbar
  "
  style="height: calc(100vh - 72px);"
>
    <a
      href="index.php?page=dashboard"
      class="flex items-center gap-3 px-4 py-2 rounded-lg transition <?= active('dashboard',$page) ?>"
    >
      <svg
        class="w-5 h-5"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z"
        />
      </svg>
      <span x-show="open">Dashboard</span>
    </a>
    <?php if ($role === 'admin'): ?>
<div>
  <button @click="adminMenu = !adminMenu"
    class="w-full flex items-center justify-between
           px-4 py-2 rounded-lg hover:bg-slate-200 transition"
  >
        <div class="flex items-center gap-3">
          <svg
            class="w-5 h-5"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M12 3l8 4v5c0 5.25-3.5 9-8 11
                 -4.5-2-8-5.75-8-11V7l8-4z"
            />
            <circle cx="12" cy="11" r="3" />
          </svg>
          <span x-show="open">Data Master</span>
        </div>
        <svg
          x-show="open"
          class="w-4 h-4 transition-transform duration-300"
          :class="adminMenu ? 'rotate-180' : ''"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M19 9l-7 7-7-7"
          />
        </svg>
      </button>
      <div
        x-show="adminMenu"
        x-transition
        :class="open ? 'ml-10' : 'flex flex-col items-center'"
        class="mt-1 space-y-1"
      >
        <a
          href="index.php?page=users"
          class="flex items-center gap-3 px-3 py-2 rounded transition <?= active('users',$page) ?>"
        >
          <svg
            class="w-4 h-4"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
            viewBox="0 0 24 24"
          >
            <circle cx="9" cy="7" r="4" />
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M3 21a6 6 0 0112 0"
            />
          </svg>
          <span x-show="open">Kelola Pengguna</span>
        </a>
        <a
          href="index.php?page=siswa"
          class="flex items-center gap-3 px-3 py-2 rounded transition <?= active('siswa',$page) ?>"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <rect x="3" y="4" width="18" height="16" rx="2" stroke-linecap="round" stroke-linejoin="round" />
            <circle cx="9" cy="10" r="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <span x-show="open">Data Siswa</span>
        </a>
        <a
          href="index.php?page=kelas" 
          class="flex items-center gap-3 px-3 py-2 rounded transition <?= active('kelas',$page) ?>"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
          <span x-show="open">Data Kelas</span>
        </a>
        <a
          href="index.php?page=tahun_ajaran"
          class="flex items-center gap-3 px-3 py-2 rounded transition <?= active('tahun_ajaran',$page) ?>"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <span x-show="open">Kelola Tahun Ajaran</span>
        </a>
        <a
  href="index.php?page=riwayat_kelas"
  class="flex items-center gap-3 px-3 py-2 rounded transition <?= active('riwayat_kelas', $page) ?>"
>
  <svg
    class="w-4 h-4"
    fill="none"
    stroke="currentColor"
    stroke-width="1.8"
    viewBox="0 0 24 24"
  >
    <circle cx="12" cy="12" r="9" />
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M12 7v5l3 3"
    />
  </svg>
  <span x-show="open">Riwayat Kelas</span>
</a>
        <a
          href="index.php?page=jenis_pelanggaran"
          class="flex items-center gap-3 px-3 py-2 rounded transition <?= active('jenis_pelanggaran',$page) ?>"
        >
          <svg
            class="w-4 h-4"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M8 6h13M8 12h13M8 18h13"
            />
            <circle cx="4" cy="6" r="1" />
            <circle cx="4" cy="12" r="1" />
            <circle cx="4" cy="18" r="1" />
          </svg>
          <span x-show="open">Jenis Pelanggaran</span>
        </a>
      </div>
    </div>
<?php endif; ?>
    <div>
      <button
        @click="guruMenu = !guruMenu"
        class="w-full flex items-center justify-between
               px-4 py-2 rounded-lg hover:bg-slate-200 transition"
      >
        <div class="flex items-center gap-3">
          <svg
            class="w-5 h-5"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M12 4L3 9l9 5 9-5-9-5z"
            />
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M7 11v4c0 1.5 2.5 3 5 3s5-1.5 5-3v-4"
            />
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M21 10v4"
            />
          </svg>
          <span x-show="open">Laporan</span>
        </div>
        <svg
          x-show="open"
          class="w-4 h-4 transition-transform duration-300"
          :class="guruMenu ? 'rotate-180' : ''"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M19 9l-7 7-7-7"
          />
        </svg>
      </button>
      <div
        x-show="guruMenu"
        x-transition
        :class="open ? 'ml-10' : 'flex flex-col items-center'"
        class="mt-1 space-y-1"
      >
        <a
          href="index.php?page=pelanggaran_siswa"
          class="flex items-center gap-3 px-3 py-2 rounded transition <?= active('pelanggaran_siswa',$page) ?>"
        >
          <svg
            class="w-4 h-4"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
            viewBox="0 0 24 24"
          >
            <circle cx="12" cy="7" r="4" />
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M5.5 21a6.5 6.5 0 0113 0"
            />
          </svg>
          <span x-show="open">Pelanggaran Siswa</span>
        </a>
        <a
          href="index.php?page=riwayat_siswa"
          class="flex items-center gap-3 px-3 py-2 rounded transition <?= active('riwayat_siswa',$page) ?>"
        >
          <svg
            class="w-4 h-4"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
            viewBox="0 0 24 24"
          >
            <circle cx="12" cy="12" r="9" />
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M12 7v5l3 3"
            />
          </svg>
          <span x-show="open">Riwayat Pelanggaran</span>
        </a>
        <a
  href="index.php?page=poin_siswa"
  class="flex items-center gap-3 px-3 py-2 rounded transition <?= active('poin_siswa', $page) ?>"
>
          <svg
            class="w-4 h-4"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
            viewBox="0 0 24 24"
          >
            <rect x="3" y="5" width="18" height="16" rx="2" />
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M3 9h18"
            />
          </svg>
          <span x-show="open">Akumulasi Poin</span>
        </a>
      </div>
    </div>
    <a
      href="index.php?page=pelanggaran"
      class="flex items-center gap-3 px-4 py-2 rounded-lg transition <?= active('pelanggaran',$page) ?>"
    >
      <svg
        class="w-5 h-5"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
        viewBox="0 0 24 24"
      >
        <rect x="3" y="4" width="18" height="16" rx="2" />
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M9 9h6M9 13h6M9 17h6"
        />
      </svg>
      <span x-show="open">Pelanggaran</span>
    </a>
  </nav>
</aside>