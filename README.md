# PointTrack.ID

PointTrack.ID adalah sistem informasi pelanggaran tata tertib sekolah berbasis web yang dirancang untuk membantu sekolah dalam mencatat, memantau, dan mengelola pelanggaran siswa secara digital, cepat, dan terstruktur.

Sistem ini dibuat sebagai proyek pengembangan perangkat lunak oleh siswa jurusan Pengembangan Perangkat Lunak dan Gim (PPLG/RPL) di SMK ISFI Banjarmasin.

---

## 📌 Latar Belakang

Banyak sekolah masih menggunakan sistem manual dalam pencatatan pelanggaran siswa menggunakan buku atau arsip kertas. Hal tersebut menimbulkan beberapa kendala seperti:

- Risiko kehilangan data
- Kesalahan pencatatan
- Sulit menghitung total poin pelanggaran
- Lambat dalam pembuatan laporan
- Sulit memantau siswa secara real-time

PointTrack.ID hadir sebagai solusi digital untuk mempermudah proses tersebut.

---

## 🎯 Tujuan Project

- Membuat sistem pelanggaran sekolah berbasis web yang mudah digunakan
- Membantu guru dalam mencatat pelanggaran siswa
- Menghitung poin pelanggaran secara otomatis
- Menyediakan laporan pelanggaran per siswa maupun per kelas
- Meningkatkan efisiensi administrasi sekolah

---

## 🚀 Fitur Utama

### 🔐 Autentikasi
- Login pengguna
- Logout
- Hak akses berdasarkan role

### 👨‍🏫 Role User

#### Admin
- Kelola data siswa
- Kelola data guru
- Kelola data kelas
- Kelola tahun pelajaran
- Kelola jenis pelanggaran
- Monitoring seluruh data pelanggaran
- Export laporan PDF / Excel

#### Guru
- Input pelanggaran siswa
- Lihat riwayat pelanggaran
- Pantau total poin siswa

### 📊 Dashboard
- Statistik jumlah siswa
- Statistik pelanggaran
- Rekap poin
- Monitoring siswa bermasalah

### 📄 Reporting
- Cetak laporan PDF
- Filter berdasarkan kelas
- Filter berdasarkan tahun ajaran
- Search siswa

---

## 🛠️ Teknologi Yang Digunakan

### Frontend
- HTML5
- CSS3
- Tailwind CSS
- JavaScript

### Backend
- PHP Native

### Database
- MySQL

### Tools
- Visual Studio Code
- Laragon / XAMPP
- phpMyAdmin
- Google Chrome / Microsoft Edge

---

## 🗂️ Struktur Database

Beberapa tabel utama:

- users
- siswa
- guru
- kelas
- tahun_pelajaran
- jenis_pelanggaran
- pelanggaran
- riwayat_kelas

---

## ⚙️ Cara Menjalankan Project

### 1. Clone Repository

```bash
git clone https://github.com/username/pointtrack.id.git