# 🚨 PointTrack.ID

> Sistem Informasi Pelanggaran Tata Tertib Sekolah Berbasis Web

PointTrack.ID adalah aplikasi berbasis web yang dirancang untuk membantu sekolah dalam mencatat, memantau, dan mengelola pelanggaran siswa secara **digital, cepat, dan terstruktur**.

Sistem ini dikembangkan sebagai proyek pembelajaran oleh siswa jurusan **Pengembangan Perangkat Lunak dan Gim (PPLG/RPL)** di **SMK ISFI Banjarmasin**.

---

## 📖 Tentang Project

Di banyak sekolah, pencatatan pelanggaran siswa masih dilakukan secara manual menggunakan buku atau arsip kertas. Cara tersebut sering menimbulkan kendala seperti:

- Data mudah hilang atau rusak  
- Kesalahan pencatatan  
- Sulit menghitung total poin pelanggaran  
- Proses laporan memakan waktu  
- Sulit melakukan monitoring siswa secara real-time  

PointTrack.ID hadir sebagai solusi modern untuk mendigitalisasi sistem tata tertib sekolah.

---

## 🎯 Tujuan Pengembangan

Project ini dibuat dengan tujuan:

- Membantu sekolah mengelola data pelanggaran siswa secara efisien  
- Mempermudah guru dalam input pelanggaran  
- Menghitung total poin otomatis  
- Menyediakan laporan cepat dan akurat  
- Meningkatkan kedisiplinan siswa melalui monitoring terstruktur  

---

## ✨ Fitur Utama

## 🔐 Sistem Login

- Login user  
- Logout  
- Hak akses berdasarkan role pengguna  

---

## 👨‍🏫 Role Pengguna

### 👑 Admin

Memiliki akses penuh terhadap seluruh sistem:

- Kelola data siswa  
- Kelola data guru  
- Kelola data kelas  
- Kelola tahun pelajaran  
- Kelola jenis pelanggaran  
- Input pelanggaran siswa  
- Monitoring seluruh data pelanggaran  
- Riwayat pelanggaran siswa  
- Pantau total poin siswa  
- Export laporan PDF  

---

### 👨‍💼 Guru

Memiliki akses terbatas pada fitur berikut:

- Input pelanggaran siswa  
- Lihat riwayat pelanggaran  
- Pantau total poin siswa  
- Export laporan PDF  

---

## 📊 Dashboard

Menampilkan informasi ringkas dan statistik sistem:

- Total siswa  
- Total pelanggaran  
- Rekap poin siswa  
- Siswa dengan poin tertinggi  
- Monitoring pelanggaran terbaru  

---

## 📄 Reporting

Fitur laporan yang memudahkan administrasi sekolah:

- Cetak laporan PDF  
- Filter berdasarkan kelas  
- Filter berdasarkan tahun pelajaran  
- Search data siswa  
- Rekap data pelanggaran  

---

## 🛠️ Teknologi yang Digunakan

### 🎨 Frontend

- HTML5  
- CSS3  
- Tailwind CSS  
- JavaScript  

### ⚙️ Backend

- PHP Native  

### 🗄️ Database

- MySQL  

### 🔧 Tools Development

- Visual Studio Code  
- Laragon  
- phpMyAdmin  
- Google Chrome / Microsoft Edge  

---

## 🗂️ Struktur Database

Beberapa tabel utama yang digunakan:

```sql
users
siswa
guru
kelas
tahun_pelajaran
jenis_pelanggaran
pelanggaran
riwayat_kelas

---

## ⚙️ Cara Menjalankan Project

### 1. Clone Repository

```bash
git clone https://github.com/username/pointtrack.id.git