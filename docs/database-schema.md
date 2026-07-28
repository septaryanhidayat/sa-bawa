# 💾 Database & Storage Schema - SA'BAWA

Aplikasi ini menggunakan pendekatan **Dual Storage Strategy** (SQLite di sisi server Laravel dan Local Storage di sisi browser client). Struktur skema data di bawah ini dirancang agar mudah direplikasi untuk proyek penilaian sejenis.

---

## 1. 🗄️ SQLite Database Schema (Server Side)

Tabel-tabel ini didefinisikan dalam file migrasi database Laravel [`database/migrations/2026_07_23_000000_create_sabawa_tables.php`](file:///c:/Users/RYAN/Herd/sa-bawa-main/database/migrations/2026_07_23_000000_create_sabawa_tables.php).

### A. Tabel `assessments` (Data Hasil Penilaian)
Menyimpan riwayat penilaian atlet/peserta beserta skor mentah dan kategori norma hasil konversi.

| Nama Kolom | Tipe Data | Atribut | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT | Primary Key, Auto Increment | ID unik penilaian |
| `nama` | VARCHAR(255) | NOT NULL | Nama lengkap peserta tes / atlet |
| `nim` | VARCHAR(255) | NOT NULL | NIM / Nomor Induk Siswa |
| `jenis_kelamin` | VARCHAR(10) | Default: 'L' | Jenis Kelamin ('L' / 'P') |
| `kelas` | VARCHAR(255) | Nullable | Nama kelas / angkatan peserta |
| `tanggal` | DATE | NOT NULL | Tanggal pelaksanaan penilaian |
| `penguji` | VARCHAR(255) | Default: 'Silvi Aryanti, M.Pd.' | Nama penguji / evaluator |
| `skor_servis_pendek` | INTEGER | Nullable | Skor total Servis Pendek (20x coba) |
| `norma_servis_pendek` | VARCHAR(50) | Nullable | Kategori norma Servis Pendek |
| `skor_servis_panjang`| INTEGER | Nullable | Skor total Servis Panjang (20x coba) |
| `norma_servis_panjang`| VARCHAR(50) | Nullable | Kategori norma Servis Panjang |
| `skor_lob` | INTEGER | Nullable | Skor total Pukulan Lob (20x coba) |
| `norma_lob` | VARCHAR(50) | Nullable | Kategori norma Pukulan Lob |
| `skor_smash` | INTEGER | Nullable | Skor total Pukulan Smash (20x coba) |
| `norma_smash` | VARCHAR(50) | Nullable | Kategori norma Pukulan Smash |
| `evaluasi_total` | VARCHAR(50) | Nullable | Kategori gabungan rata-rata seluruh tes |
| `created_at` | TIMESTAMP | Nullable | Waktu pembuatan data |
| `updated_at` | TIMESTAMP | Nullable | Waktu perubahan terakhir data |

### B. Tabel `materis` (Panduan Tes)
Menyimpan konten panduan cara pelaksanaan tes teknik dasar bulutangkis.

| Nama Kolom | Tipe Data | Deskripsi |
| :--- | :--- | :--- |
| `id` | BIGINT (PK) | ID unik materi |
| `judul` | VARCHAR(255) | Judul materi tes |
| `kategori` | VARCHAR(255) | Kategori (Overview, Servis Pendek, Servis Panjang, Lob, Smash) |
| `deskripsi` | TEXT | Penjelasan detail teknik dasar dan tujuan tes |
| `petunjuk` | TEXT | Langkah-langkah pelaksanaan tes bagi penguji |
| `urutan` | INTEGER | Prioritas pengurutan materi di antarmuka |

### C. Tabel `videos` (Demonstrasi Video)
Menyimpan data tautan video tutorial yang dapat diputar secara embedded.

| Nama Kolom | Tipe Data | Deskripsi |
| :--- | :--- | :--- |
| `id` | BIGINT (PK) | ID unik video |
| `judul` | VARCHAR(255) | Judul peragaan video |
| `youtube_url` | VARCHAR(255) | URL/Link video YouTube (embed link) |
| `deskripsi` | TEXT | Keterangan/penjelasan singkat video |
| `kategori` | VARCHAR(255) | Kategori teknik dasar |

### D. Tabel `faqs` (Pertanyaan Umum)
| Nama Kolom | Tipe Data | Deskripsi |
| :--- | :--- | :--- |
| `id` | BIGINT (PK) | ID unik FAQ |
| `pertanyaan` | TEXT | Pertanyaan seputar instrumen |
| `jawaban` | TEXT | Jawaban/Penjelasan admin |
| `urutan` | INTEGER | Nomor urutan tampil FAQ |

### E. Tabel `about_settings` (Kustomisasi General)
Menyimpan setelan global website seperti nama aplikasi, logo, deskripsi hero, dll dalam bentuk Key-Value.

| Nama Kolom | Tipe Data | Atribut | Deskripsi |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT (PK) | Auto Increment | ID unik setelan |
| `key` | VARCHAR(255) | Unique, NOT NULL | Nama key setelan (misal: `app_name`, `logo_path`) |
| `value` | TEXT | Nullable | Nilai isi setelan |

---

## 2. 💾 Browser Local Storage Schema (Client Side)

Untuk mengaktifkan performa instan dan fungsionalitas offline-first, client menyimpan data dalam format JSON pada Local Storage dengan keys berikut:

### Key: `sabawa_records`
Menyimpan list object hasil tes dalam struktur array JSON.
```json
[
  {
    "id": 1690463991200,
    "nama": "Ahmad Rizky Pratama",
    "nim": "06121001001",
    "jenisKelamin": "L",
    "kelas": "Palembang A 2024",
    "tanggal": "2026-07-20",
    "penguji": "Silvi Aryanti, M.Pd.",
    "skorServisPendek": 85,
    "normaServisPendek": "Sangat Tinggi",
    "skorServisPanjang": 62,
    "normaServisPanjang": "Sangat Tinggi",
    "skorLob": 92,
    "normaLob": "Sangat Tinggi",
    "skorSmash": 35,
    "normaSmash": "Sangat Tinggi",
    "evaluasiTotal": "Sangat Tinggi"
  }
]
```

### Key: `sabawa_settings`
Menyimpan konfigurasi nama, subtitle, dan logo utama aplikasi.
```json
{
  "appName": "SA'BAWA",
  "appSubtitle": "Silvi Aryanti' Badminton Assessment WebApp",
  "appLogo": "/images/logo1.png",
  "heroTitle": "Pengembangan Instrumen Penilaian Teknik Dasar Bulutangkis",
  "heroDescription": "Aplikasi SA'BAWA dirancang khusus untuk mempermudah penilaian dan pengolahan skor tes..."
}
```

### Key: `sabawa_researchers`
Menyimpan list data profil tim peneliti yang tampil di halaman Home dan About.
```json
[
  {
    "id": 1,
    "name": "Silvi Aryanti, M.Pd.",
    "role": "Ketua Peneliti • NIDN 0021079101",
    "photo": "images/Picture1.png",
    "isLeader": true
  }
]
```
*(Catatan: Keys lainnya seperti `sabawa_materis`, `sabawa_videos`, dan `sabawa_faqs` memiliki struktur JSON array serupa dengan tabel SQLite-nya).*
