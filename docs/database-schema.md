# 💾 Database & Storage Schema - SA'BAWA

Aplikasi ini menggunakan pendekatan **Multi-Device Centralized Storage Strategy** dengan **MySQL Database** di sisi server Laravel dan **Local Storage** di sisi browser sebagai offline fallback cache. Struktur skema data di bawah ini mencakup pencatatan rincian 20x percobaan untuk setiap teknik dasar bulutangkis.

---

## 1. 🗄️ MySQL Database Schema (Server Side)

Tabel-tabel ini didefinisikan dalam file migrasi database Laravel [`database/migrations/2026_07_23_000000_create_sabawa_tables.php`](file:///c:/Users/RYAN/Herd/sa-bawa-main/database/migrations/2026_07_23_000000_create_sabawa_tables.php).

### A. Tabel `assessments` (Data Hasil Penilaian)
Menyimpan riwayat penilaian atlet/peserta didik beserta rincian nilai 20x kesempatan setiap teknik (dalam format JSON), skor akumulasi total (0–100), dan kategori norma hasil konversi.

| Nama Kolom | Tipe Data | Atribut | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT | Primary Key, Auto Increment | ID unik penilaian di database |
| `nama` | VARCHAR(255) | NOT NULL | Nama lengkap peserta tes / siswa |
| `nim` | VARCHAR(255) | NOT NULL | NIM / NISN peserta tes |
| `jenis_kelamin` | VARCHAR(10) | Default: 'L' | Jenis Kelamin ('L' / 'P') |
| `kelas` | VARCHAR(255) | Nullable | Nama kelas / angkatan peserta (misal: 'VIII-A') |
| `sekolah` | VARCHAR(255) | Nullable | Nama instansi sekolah (misal: 'SMP Negeri 1 Palembang') |
| `tanggal` | DATE | NOT NULL | Tanggal pelaksanaan penilaian |
| `penguji` | VARCHAR(255) | Default: 'Silvi Aryanti, M.Pd.' | Nama penguji / evaluator |
| `trials_servis_pendek` | JSON | Nullable | Array 20 nilai kesempatan Servis Pendek (contoh: `[5,4,4,5,...]`) |
| `skor_servis_pendek` | INTEGER | Nullable | Akumulasi nilai 20x servis pendek (0–100) |
| `norma_servis_pendek` | VARCHAR(50) | Nullable | Kategori norma Servis Pendek |
| `trials_servis_panjang`| JSON | Nullable | Array 20 nilai kesempatan Servis Panjang |
| `skor_servis_panjang`| INTEGER | Nullable | Akumulasi nilai 20x servis panjang (0–100) |
| `norma_servis_panjang`| VARCHAR(50) | Nullable | Kategori norma Servis Panjang |
| `trials_lob` | JSON | Nullable | Array 20 nilai kesempatan Pukulan Lob |
| `skor_lob` | INTEGER | Nullable | Akumulasi nilai 20x pukulan lob (0–100) |
| `norma_lob` | VARCHAR(50) | Nullable | Kategori norma Pukulan Lob |
| `trials_smash` | JSON | Nullable | Array 20 nilai kesempatan Pukulan Smash |
| `skor_smash` | INTEGER | Nullable | Akumulasi nilai 20x pukulan smash (0–100) |
| `norma_smash` | VARCHAR(50) | Nullable | Kategori norma Pukulan Smash |
| `evaluasi_total` | VARCHAR(50) | Nullable | Kategori gabungan rata-rata seluruh tes |
| `created_at` | TIMESTAMP | Nullable | Waktu pencatatan data di MySQL |
| `updated_at` | TIMESTAMP | Nullable | Waktu perubahan terakhir data |

### B. Tabel `schools` (Daftar Sekolah Mitra)
Menyimpan daftar sekolah tempat pelaksanaan tes untuk kemudahan dropdown filter dan grouping.

| Nama Kolom | Tipe Data | Deskripsi |
| :--- | :--- | :--- |
| `id` | BIGINT (PK) | ID unik sekolah |
| `nama` | VARCHAR(255) | Nama sekolah (contoh: SMP Negeri 1 Palembang) |
| `alamat` | TEXT (Nullable)| Alamat sekolah |

### C. Tabel `materis` (Panduan Tes)
Menyimpan konten panduan cara pelaksanaan tes teknik dasar bulutangkis.

| Nama Kolom | Tipe Data | Deskripsi |
| :--- | :--- | :--- |
| `id` | BIGINT (PK) | ID unik materi |
| `judul` | VARCHAR(255) | Judul materi tes |
| `kategori` | VARCHAR(255) | Kategori (Overview, Servis Pendek, Servis Panjang, Lob, Smash) |
| `deskripsi` | TEXT | Penjelasan detail teknik dasar dan tujuan tes |
| `petunjuk` | TEXT | Langkah-langkah pelaksanaan tes bagi penguji |
| `urutan` | INTEGER | Prioritas pengurutan materi di antarmuka |

### D. Tabel `videos` (Demonstrasi Video)
Menyimpan data tautan video tutorial yang dapat diputar secara embedded.

| Nama Kolom | Tipe Data | Deskripsi |
| :--- | :--- | :--- |
| `id` | BIGINT (PK) | ID unik video |
| `judul` | VARCHAR(255) | Judul peragaan video |
| `youtube_url` | VARCHAR(255) | URL/Link video YouTube (embed link) |
| `deskripsi` | TEXT | Keterangan/penjelasan singkat video |
| `kategori` | VARCHAR(255) | Kategori teknik dasar |

### E. Tabel `faqs` (Pertanyaan Umum)
| Nama Kolom | Tipe Data | Deskripsi |
| :--- | :--- | :--- |
| `id` | BIGINT (PK) | ID unik FAQ |
| `pertanyaan` | TEXT | Pertanyaan seputar instrumen |
| `jawaban` | TEXT | Jawaban/Penjelasan admin |
| `urutan` | INTEGER | Nomor urutan tampil FAQ |

### F. Tabel `researchers` (Tim Peneliti)
Menyimpan data profil tim peneliti yang tampil di beranda dan halaman profil.

| Nama Kolom | Tipe Data | Deskripsi |
| :--- | :--- | :--- |
| `id` | BIGINT (PK) | ID unik profil peneliti |
| `name` | VARCHAR(255) | Nama lengkap beserta gelar |
| `role` | VARCHAR(255) | Peran / Jabatan akademis & NIDN |
| `photo` | VARCHAR(255) | Path berkas foto |
| `is_leader` | BOOLEAN | Penanda ketua tim peneliti |
| `urutan` | INTEGER | Urutan tampilan profil |

### G. Tabel `about_settings` (Kustomisasi Global)
Menyimpan setelan global website seperti nama aplikasi, logo, deskripsi hero dalam format Key-Value.

| Nama Kolom | Tipe Data | Atribut | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT (PK) | Auto Increment | ID unik setelan |
| `key` | VARCHAR(255) | Unique, NOT NULL | Nama key setelan (misal: `app_name`, `logo_path`) |
| `value` | TEXT | Nullable | Nilai isi setelan |

---

## 2. 🌐 REST API Endpoints (Multi-Device Sync)

Seluruh perangkat (smartphone, tablet, laptop, PC penguji) tersambung ke backend Laravel melalui REST API:

- `GET /api/app-data` : Mengambil seluruh data awal (records, schools, materis, videos, faqs, researchers, appSettings).
- `POST /api/assessments` : Menyimpan / memperbarui penilaian (termasuk 20 trials dan skor akumulasi).
- `DELETE /api/assessments/{id}` : Menghapus data penilaian dari MySQL.
- `POST /api/schools` : Tambah sekolah baru.
- `DELETE /api/schools/{id}` : Hapus sekolah.
- `POST /api/materis`, `POST /api/videos`, `POST /api/faqs`, `POST /api/researchers`, `POST /api/settings` : Manajemen konten oleh penguji/admin.
