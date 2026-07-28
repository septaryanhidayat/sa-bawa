# 🔌 System Integration & Replit Guide - SA'BAWA

Dokumen ini memuat panduan teknis langkah demi langkah untuk menginstal, mendeploy, dan mereplikasi (*clone/replicate*) proyek web app sejenis di server cPanel atau lingkungan pengembangan baru.

---

## 1. 🛠️ Panduan Instalasi Lokal (Local Development Setup)

Ikuti langkah-langkah berikut untuk menjalankan project baru dari repositori hasil replikasi:

### A. Prasyarat Sistem
- PHP >= 8.2 (dengan ekstensi `pdo_sqlite` aktif)
- Composer (Dependency Manager untuk PHP)
- Node.js & NPM (untuk compile asset frontend jika menggunakan build tools)

### B. Langkah Instalasi
1. **Clone Repositori**:
   ```bash
   git clone <link-repo-anda> nama-proyek
   cd nama-proyek
   ```

2. **Instal Dependensi Backend (Composer)**:
   ```bash
   composer install
   ```

3. **Salin File Environment**:
   Salin file konfigurasi `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```

4. **Inisialisasi Database SQLite**:
   Secara default, Laravel menggunakan SQLite. Buat file database kosong di direktori database:
   - **Linux/Mac**: `touch database/database.sqlite`
   - **Windows (PowerShell)**: `New-Item database/database.sqlite -ItemType File`

5. **Generate Kunci Enkripsi Aplikasi**:
   Ini sangat krusial. Jika tidak dijalankan, aplikasi akan melempar *500 Server Error (MissingAppKeyException)*:
   ```bash
   php artisan key:generate --force
   ```

6. **Jalankan Migrasi Database**:
   ```bash
   php artisan migrate
   ```

7. **Jalankan Server Lokal**:
   ```bash
   php artisan serve
   ```
   Aplikasi siap diakses di `http://127.0.0.1:8000`.

---

## 2. 🌍 Panduan Deployment ke cPanel / Shared Hosting

Saat mendeploy aplikasi Laravel ke cPanel, ikuti langkah-langkah penyesuaian folder berikut agar aplikasi berjalan aman:

### A. Pengaturan Struktur Direktori
Sangat direkomendasikan untuk memisahkan folder inti Laravel dengan folder publik demi keamanan agar file konfigurasi `.env` tidak dapat diakses langsung oleh publik:
1. Buat folder baru di luar `public_html` (misal: `/home/username/sa-bawa-core/`). Upload seluruh file proyek ke folder tersebut kecuali folder `public`.
2. Upload seluruh isi folder `public` Laravel langsung ke dalam direktori `/home/username/public_html/` (atau direktori root domain Anda).
3. Edit file `/home/username/public_html/index.php` untuk mengubah path autoload dan bootstrap agar mengarah ke folder core Anda:
   ```php
   // Baris 14: Ubah path autoload
   require __DIR__.'/../sa-bawa-core/vendor/autoload.php';

   // Baris 28: Ubah path bootstrap
   $app = require_once __DIR__.'/../sa-bawa-core/bootstrap/app.php';
   ```

### B. Perizinan Write Akses Database SQLite
Pastikan file database SQLite memiliki hak akses tulis (*write permissions*) agar aplikasi dapat menyimpan rekaman data:
- Folder `/home/username/sa-bawa-core/database/` harus memiliki permission **775** atau **755**.
- File `/home/username/sa-bawa-core/database/database.sqlite` harus memiliki permission **664** atau **644**.

### C. Reset Cache setelah Upload
Setiap kali melakukan perubahan konfigurasi pada file `.env` di cPanel, jalankan perintah pembersihan cache melalui menu **Terminal cPanel** (atau Cron Jobs jika tidak ada Terminal):
```bash
php artisan config:clear
php artisan view:clear
php artisan cache:clear
```

---

## 🔌 3. Integrasi Pihak Ketiga (External CDNs)

Aplikasi SA'BAWA terintegrasi dengan pustaka eksternal berikut untuk performa maksimal tanpa install package lokal tambahan:
1. **Alpine.js (State Engine)**: 
   ```html
   <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
   ```
2. **SweetAlert2 (Pop-up Dialogs)**:
   ```html
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   ```
3. **FontAwesome 6 (Ikon)**:
   ```html
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   ```
