<p align="center">
  <img src="public/images/logo.png" alt="Logo Ayam Geprek Rejo" width="120"/>
</p>

<h1 align="center">Sistem Self-Order UMKM Ayam Geprek Rejo</h1>

<p align="center">
  Aplikasi pemesanan mandiri berbasis web untuk UMKM Ayam Geprek Rejo.<br>
  Dibangun dengan Laravel 13, Livewire 4, Flux UI, dan Tailwind CSS 4.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel"/>
  <img src="https://img.shields.io/badge/Livewire-4.x-FB70A9?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire"/>
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP"/>
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL"/>
  <img src="https://img.shields.io/badge/Vite-8.x-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite"/>
  <img src="https://img.shields.io/badge/TailwindCSS-4.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind"/>
</p>

---

## Daftar Isi

- [Tentang Aplikasi](#-tentang-aplikasi)
- [Fitur Unggulan](#-fitur-unggulan)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Prasyarat Sistem](#-prasyarat-sistem)
- [Instalasi Lokal (Development)](#-instalasi-lokal-development)
- [Deployment ke Production Server](#-deployment-ke-production-server)
- [Struktur Database](#-struktur-database)
- [Akun Default Sistem](#-akun-default-sistem)
- [Alur Penggunaan Aplikasi](#-alur-penggunaan-aplikasi)
- [Pengujian BDD (Behavior-Driven Development)](#-pengujian-bdd-behavior-driven-development)
- [Tim Pengembang](#-tim-pengembang)

---

## Tentang Aplikasi

**Sistem Self-Order UMKM Ayam Geprek Rejo** adalah aplikasi web yang memungkinkan pelanggan memesan makanan secara mandiri (self-order) tanpa perlu antri di kasir. Aplikasi ini dirancang khusus untuk kebutuhan UMKM restoran cepat saji dengan manajemen operasional yang terintegrasi.

**URL Deployment:** http://kelas-b-8.informatika-unjedir.web.id

**Repository:** https://github.com/LATIFANIKA-NURAFWI1/UMKM-Ayam-Geprek-Rejo

---

## Fitur Unggulan

### Untuk Pelanggan (Customer)
- **Self-Order Menu** — Pesan makanan langsung dari meja tanpa antri
- **Filter Kategori** — Tampilan menu berdasarkan kategori (Ayam Geprek, Camilan, Ekstra, Minuman, Paket Nasi)
- **Keranjang Belanja** — Tambah, kurangi, dan kelola item pesanan
- **Checkout & Pembayaran** — Proses checkout dengan pilihan pembayaran QRIS
- **Kode Voucher** — Input voucher diskon saat checkout
- **Program Member & Poin** — Daftar member untuk kumpulkan poin dan dapatkan reward
- **Status Pesanan Real-time** — Pantau status pesanan secara langsung

### Untuk Owner/Admin
- **Dashboard Analitik** — Ringkasan penjualan, pendapatan, dan statistik harian
- **Manajemen Menu** — Tambah, edit, hapus menu beserta gambar dan kategori
- **Manajemen Kategori** — Kelola kategori menu
- **Manajemen Stok Bahan Baku** — Pantau stok bahan dengan alert stok rendah
- **Manajemen Pesanan** — Lihat dan kelola seluruh pesanan
- **Manajemen Member** — Kelola data anggota dan poin reward
- **Manajemen Voucher** — Buat dan kelola kode diskon
- **Manajemen Pengeluaran** — Catat pengeluaran operasional
- **Laporan Penjualan** — Laporan laba rugi dengan export PDF
- **Pengaturan QRIS** — Upload gambar QRIS untuk pembayaran

### Untuk Kasir
- **Dashboard Kasir** — Tampilan pesanan masuk dan antrian pembayaran
- **Konfirmasi Pembayaran** — Tandai pesanan sebagai lunas

### Untuk Tim Dapur (KDS - Kitchen Display System)
- **Display Pesanan Dapur** — Tampilan real-time pesanan yang perlu disiapkan
- **Update Status Masak** — Tandai pesanan sebagai sedang diproses atau selesai

---

## 🛠 Teknologi yang Digunakan

| Komponen         | Teknologi                    | Versi  |
|-----------------|------------------------------|--------|
| Framework Backend | Laravel                    | 13.x   |
| Framework Frontend | Livewire + Flux UI         | 4.x    |
| Bahasa Pemrograman | PHP                        | ^8.3   |
| Database         | MySQL                        | 8.0    |
| Build Tool       | Vite                         | ^8.0   |
| CSS Framework    | Tailwind CSS                 | ^4.0   |
| Auth             | Laravel Fortify              | ^1.37  |
| PDF Export       | barryvdh/laravel-dompdf      | *      |
| Testing Framework | Pest PHP                    | ^4.7   |
| Server           | Apache/Nginx (HestiaCP)      | -      |

---

## ⚙ Prasyarat Sistem

Pastikan sistem Anda telah terinstall:

| Software    | Versi Minimum | Cek Versi              |
|-------------|---------------|------------------------|
| PHP         | 8.3           | `php -v`               |
| Composer    | 2.x           | `composer --version`   |
| Node.js     | 18.x          | `node -v`              |
| NPM         | 9.x           | `npm -v`               |
| MySQL       | 8.0           | `mysql --version`      |
| Git         | 2.x           | `git --version`        |

---

## Instalasi Lokal (Development)

### Langkah 1: Clone Repository

```bash
git clone https://github.com/LATIFANIKA-NURAFWI1/UMKM-Ayam-Geprek-Rejo.git
cd UMKM-Ayam-Geprek-Rejo
```

### Langkah 2: Install Dependensi PHP

```bash
composer install
```

### Langkah 3: Konfigurasi Environment

```bash
# Salin file konfigurasi contoh
cp .env.example .env

# Generate application key
php artisan key:generate
```

Edit file `.env` dan sesuaikan konfigurasi database:

```env
APP_NAME="Ayam Geprek Rejo"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=geprek_rejo        # Nama database yang Anda buat
DB_USERNAME=root               # Username MySQL Anda
DB_PASSWORD=                   # Password MySQL Anda
```

### Langkah 4A: Setup Database dengan File SQL (Direkomendasikan)

```bash
# Buat database kosong terlebih dahulu di MySQL
mysql -u root -p -e "CREATE DATABASE geprek_rejo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import data dari file backup
mysql -u root -p geprek_rejo < geprek_rejo_backup.sql
```

> **Catatan:** File `geprek_rejo_backup.sql` sudah berisi seluruh struktur tabel dan data awal (termasuk menu, kategori, dan data dummy).

### Langkah 4B: Setup Database dengan Migrasi (Alternatif)

```bash
# Jalankan migrasi
php artisan migrate

# Isi data awal (user, menu, kategori, dll)
php artisan db:seed
```

### Langkah 5: Install Dependensi Node.js & Build Assets

```bash
npm install
npm run build
```

### Langkah 6: Setup Storage

```bash
# Buat symlink untuk storage publik (gambar menu, QRIS, dll)
php artisan storage:link
```

### Langkah 7: Atur Permission

```bash
chmod -R 775 storage bootstrap/cache
```

### Langkah 8: Jalankan Aplikasi

```bash
# Mode Development
npm run dev &
php artisan serve

# Akses di browser:
# http://localhost:8000
```

---

## Deployment ke Production Server (HestiaCP)

### Prasyarat Server
- PHP 8.3+
- MySQL 8.0+
- Node.js 18+
- Composer 2.x
- Git

### Langkah 1: Konfigurasi HestiaCP Web GUI

1. Login ke HestiaCP di `https://[IP_SERVER]:8083`
2. Buka tab **Web** → klik **Edit** pada domain Anda
3. Buka **Advanced Options** → centang **Custom Document Root**
4. Set nilai ke: `public` (agar web server membaca `public_html/public`)
5. Aktifkan **SSL (Let's Encrypt)**
6. Klik **Save**

### Langkah 2: Buat Database di HestiaCP

1. Buka tab **DB** → klik **Add Database**
2. Isi nama database, username, dan password
3. Catat kredensial database yang dibuat

### Langkah 3: Clone & Konfigurasi via SSH

```bash
# Login ke server
ssh username@domain.com

# Masuk ke direktori web
cd ~/web/[DOMAIN]/

# Hapus folder public_html lama
rm -rf public_html

# Clone project ke public_html
git clone https://github.com/LATIFANIKA-NURAFWI1/UMKM-Ayam-Geprek-Rejo.git public_html

# Masuk ke folder project
cd public_html
```

### Langkah 4: Install Dependensi

```bash
# Install PHP dependencies (tanpa paket development)
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies & build assets
npm install
npm run build
```

### Langkah 5: Konfigurasi .env Production

```bash
# Salin file .env
cp .env.example .env
nano .env
```

Ubah nilai berikut di file `.env`:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://[DOMAIN_ANDA]

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=[nama_database]
DB_USERNAME=[username_database]
DB_PASSWORD=[password_database]
```

```bash
# Generate application key
php artisan key:generate
```

### Langkah 6: Import Database

```bash
# Opsi A: Import dari file SQL backup
mysql -u [DB_USER] -p [DB_NAME] < geprek_rejo_backup.sql

# Opsi B: Jalankan migrasi dari awal
php artisan migrate --force
php artisan db:seed --force
```

### Langkah 7: Setup Storage & Permission

```bash
# Buat symlink storage
php artisan storage:link

# Atur permission folder
chmod -R 775 storage bootstrap/cache
```

### Langkah 8: Optimasi Cache Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Langkah 9: Verifikasi

Buka browser dan akses domain Anda. Pastikan:
- Halaman menu tampil dengan benar
- Login berfungsi dengan akun yang tersedia
- Gambar menu tampil (jika gambar tidak tampil, upload manual via File Manager)

---

## Struktur Database

Aplikasi menggunakan 14 tabel utama:

| Tabel                | Deskripsi                                    |
|---------------------|----------------------------------------------|
| `users`             | Data pengguna sistem (owner, kasir, kds)     |
| `categories`        | Kategori menu (Ayam Geprek, Minuman, dll)    |
| `menu_items`        | Data menu makanan dan minuman                |
| `stock_ingredients` | Data bahan baku dan stok                     |
| `recipes`           | Relasi menu dengan bahan baku                |
| `members`           | Data pelanggan member                        |
| `vouchers`          | Data voucher diskon                          |
| `orders`            | Data transaksi pesanan                       |
| `order_details`     | Detail item dalam setiap pesanan             |
| `expenses`          | Catatan pengeluaran operasional              |
| `voucher_uses`      | Riwayat penggunaan voucher                   |
| `point_logs`        | Riwayat poin reward member                   |
| `settings`          | Pengaturan aplikasi (QRIS, dll)              |
| `passkeys`          | Data passkey autentikasi                     |

---

## Akun Default Sistem

Akun-akun berikut tersedia setelah import database / menjalankan seeder:

| Role          | Email                     | Password   | Akses                         |
|--------------|---------------------------|------------|-------------------------------|
| Owner/Admin | `owner@geprekrejo.com`  | `owner123` | Seluruh fitur manajemen       |
| Kasir 1    | `kasir1@geprekrejo.com`  | `kasir123` | Dashboard kasir               |
| Kasir 2    | `kasir2@geprekrejo.com`  | `kasir123` | Dashboard kasir               |
| KDS Dapur  | `dapur@geprekrejo.com`   | `dapur123` | Kitchen Display System (KDS)  |

> **Penting:** Ubah semua password di atas setelah pertama kali login di production!

---

## 🗺 Alur Penggunaan Aplikasi

### Alur Customer (Pelanggan)
```
Buka /order → Pilih Menu → Tambah ke Keranjang
    → (Opsional: Input Voucher / Daftar Member)
    → Checkout → Konfirmasi Pesanan
    → Bayar via QRIS → Menunggu Konfirmasi Kasir
    → Pesanan Dikonfirmasi → Dapur Siapkan Pesanan
    → Pesanan Selesai 
```

### Alur Kasir
```
Login (/kasir) → Lihat Antrian Pesanan
    → Konfirmasi Pembayaran QRIS
    → Tandai Pesanan Lunas
```

### Alur Tim Dapur (KDS)
```
Login (/kds) → Lihat Display Pesanan Masuk
    → Update Status: "Sedang Dimasak"
    → Update Status: "Selesai" 
```

### Alur Owner/Admin
```
Login → Dashboard Analitik
    → Kelola Menu, Kategori, Stok
    → Pantau Pesanan & Transaksi
    → Lihat Laporan Laba Rugi (export PDF)
```

---

## Pengujian BDD (Behavior-Driven Development)

Aplikasi ini diuji menggunakan pendekatan **Behavior-Driven Development (BDD)** dengan framework **Pest PHP**.

### Apa itu BDD?

BDD adalah metode pengembangan perangkat lunak yang berfokus pada **perilaku sistem dari perspektif pengguna**. Setiap skenario pengujian ditulis dalam format **Given-When-Then**:

- **Given** (Diberikan) — Kondisi awal/prasyarat
- **When** (Ketika) — Aksi yang dilakukan
- **Then** (Maka) — Hasil yang diharapkan

### Cara Menjalankan Pengujian

```bash
# Jalankan semua test
php artisan test

# Jalankan dengan output verbose
php artisan test --verbose

# Jalankan dengan Pest langsung
./vendor/bin/pest

# Jalankan test spesifik
./vendor/bin/pest tests/Feature/AuthTest.php
```

### Skenario BDD yang Diuji

#### 1. Autentikasi Pengguna
```
Skenario: Owner berhasil login
  Given pengguna berada di halaman login
  When memasukkan email "owner@geprekrejo.com" dan password "owner123"
  Then pengguna diarahkan ke halaman dashboard
  And melihat menu manajemen owner

Skenario: Login dengan kredensial salah
  Given pengguna berada di halaman login
  When memasukkan email yang tidak terdaftar
  Then muncul pesan error "Kredensial tidak valid"
  And pengguna tetap di halaman login
```

#### 2. Pemesanan oleh Customer
```
Skenario: Customer berhasil memesan menu
  Given customer berada di halaman /order
  When memilih menu "Ayam Geprek Dada"
  And mengklik tombol tambah ke keranjang
  Then item tampil di keranjang belanja
  And total harga diperbarui secara otomatis

Skenario: Customer checkout dengan voucher
  Given customer memiliki item di keranjang
  When memasukkan kode voucher yang valid
  Then diskon diterapkan pada total harga
  And customer dapat melanjutkan ke pembayaran
```

#### 3. Manajemen Menu oleh Owner
```
Skenario: Owner berhasil menambah menu baru
  Given owner telah login dan berada di halaman Menu
  When mengisi form tambah menu dengan data lengkap
  And mengklik tombol Simpan
  Then menu baru tampil dalam daftar menu
  And jumlah menu bertambah satu

Skenario: Owner menghapus menu
  Given owner melihat daftar menu yang ada
  When mengklik tombol hapus pada menu tertentu
  And mengkonfirmasi penghapusan
  Then menu dihapus dari sistem
  And tidak tampil lagi dalam daftar
```

#### 4. Konfirmasi Pembayaran oleh Kasir
```
Skenario: Kasir mengkonfirmasi pesanan
  Given kasir telah login ke dashboard kasir
  When melihat pesanan yang menunggu konfirmasi
  And mengklik tombol "Konfirmasi Bayar"
  Then status pesanan berubah menjadi "Dikonfirmasi"
  And pesanan muncul di display dapur (KDS)
```

### Struktur Folder Pengujian

```
tests/
├── Feature/         # Pengujian fitur & integrasi
│   ├── Auth/        # Pengujian autentikasi
│   ├── Menu/        # Pengujian manajemen menu
│   ├── Order/       # Pengujian alur pemesanan
│   └── Settings/    # Pengujian pengaturan
├── Unit/            # Pengujian unit fungsi
└── Pest.php         # Konfigurasi Pest
```

---

## Tim Pengembang

**Kelompok 8 — Kelas B**  
Program Studi Informatika — UNJEDIR

| Nama               | Role              |
|--------------------|-------------------|
| *(Adjeng Mutiara Dewi (H1D024055))* | Backend Developer |
| *(Rahmadani Hafsari (H1D024057))* | Frontend Developer|
| *(Nayli Ghassaniy L. N. (H1D024058))* | UI/UX Designer    |
| *(Latifanika Nurafwi (H1D024099))* | Database Engineer |

---

## Catatan Penting

> File `.env` **tidak** disertakan dalam repository karena mengandung informasi sensitif (kredensial database, application key). Ikuti langkah instalasi untuk membuat file `.env` sendiri.

> Folder `storage/app/public/menu` berisi gambar menu. Jika gambar tidak tampil setelah deployment, upload manual gambar-gambar tersebut ke server via File Manager HestiaCP ke path: `public_html/storage/app/public/menu/`.

> Setelah setiap update code di server, jalankan: `git pull && php artisan optimize:clear && npm run build`

---

<p align="center">
  Dibuat dengan oleh Kelompok 8 — Kelas B Informatika Universitas Jenderal Soedirman<br>
  <em>SDLC: Agile | Testing: BDD (Behavior-Driven Development)</em>
</p>
