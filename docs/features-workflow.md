# 🔄 Features & Workflows - SA'BAWA

Dokumen ini mendokumentasikan fitur-fitur fungsional, alur kerja pengguna (Workflow) input 20x kesempatan, sinkronisasi multi-device via MySQL, serta logika rumus konversi skor yang digunakan dalam aplikasi **SA'BAWA**.

---

## 1. 🚶‍♂️ Alur Kerja Pengguna (User Workflow)

### A. Alur Kerja Penguji / Pelatih (Input 20x Kesempatan & Akumulasi Skor)
1. **Pilih Menu Form**: Penguji mengklik menu **Isi Data** (baik dari dashboard utama maupun navigasi bawah).
2. **Input Identitas**: Mengisi Nama, NIM/NISN, Gender, Kelas, Sekolah, Tanggal, dan Penguji.
3. **Input 20 Kesempatan Per Teknik**:
   - Penguji membuka panel teknik tes (Servis Pendek, Servis Panjang, Lob, Smash).
   - Terdapat **20 kotak input kesempatan** (skor 0–5 untuk setiap kesempatan).
   - Dilengkapi fitur **Auto-advance**: saat angka diketik, kursor otomatis melompat ke kesempatan berikutnya untuk mempercepat input di lapangan.
   - Dilengkapi tombol **Quick Fill** (Isi Semua 5, 4, 3, atau Reset) untuk mempermudah simulasi/koreksi massal.
4. **Akumulasi Skor Otomatis Real-Time**:
   - Aplikasi secara instan menjumlahkan skor dari ke-20 kesempatan (maksimal akumulasi 100).
   - Kategori norma (Sangat Kurang s.d. Sangat Tinggi) dan evaluasi gabungan langsung muncul saat penguji mengetik.
5. **Simpan ke Database MySQL (Multi-Device Sync)**:
   - Tombol **Simpan Data Penilaian** mengirimkan seluruh 20 kesempatan beserta skor akumulasi ke backend via endpoint `POST /api/assessments`.
   - Data langsung tersimpan di database MySQL terpusat (`sa_bawa`), sehingga dapat langsung dilihat/dipantau dari perangkat lain (ponsel, tablet, laptop) secara real-time.
   - Tersedia tombol **Sync MySQL** di header untuk me-refresh data terbaru dari perangkat lain kapan saja.
6. **Lihat Rincian & Cetak**:
   - Di tab **Tampil Data**, penguji dapat mengklik tombol **Detail** pada kartu siswa untuk melihat badge nilai masing-masing dari 20 kesempatan servis/pukulan.
   - Penguji dapat menyaring data berdasarkan sekolah/kategori, mencetak laporan (`Cetak Laporan`), atau ekspor ke file CSV (`Ekspor CSV`).

```mermaid
flowchart TD
    A[Mulai Tes Siswa] --> B[Input 20x Kesempatan Servis/Pukulan]
    B --> C[Live Akumulasi Skor: 0 - 100]
    C --> D{Kalkulasi Kategori Norma Otomatis}
    D -->|Servis Pendek| E[Skala Norma Baku]
    D -->|Servis Panjang| F[Skala Norma Baku]
    D -->|Lob| G[Skala Norma Baku]
    D -->|Smash| H[Skala Norma Baku]
    E & F & G & H --> I[Evaluasi Total Gabungan]
    I --> J[Kirim ke Database MySQL via API]
    J --> K[Tersinkronisasi ke Seluruh Perangkat]
    K --> L[Tampil Rekap & Rincian Tiap Kesempatan]
    L --> M[Opsi: Cetak PDF / Ekspor CSV]
```

### B. Alur Kerja Admin (Kelola Konten & Data Penilaian)
1. **Login Admin**: Masuk dengan mengeklik **Login Admin** (Password default: `admin` atau `sabawa2026`).
2. **Dashboard Admin**: Dashboard khusus admin memiliki sub-tab:
   - **Data Tes**: Mengedit skor, melihat rincian kesempatan, atau menghapus baris data langsung di database MySQL.
   - **Materi Tes**: CRUD panduan teks instruksi pelaksanaan tes.
   - **Video Tutorial**: CRUD link video demonstrasi YouTube.
   - **FAQ**: CRUD daftar tanya jawab seputar instrumen tes.
   - **Setelan App**: Kustomisasi Nama Aplikasi, Subtitle, Deskripsi, dan logo aplikasi.

---

## 2. 🧮 Logika & Rumus Konversi Norma Penilaian

Setiap item tes bulutangkis memiliki ambang batas (*threshold*) skor akumulasi (dari 20x percobaan) yang berbeda untuk menentukan klasifikasi norma. Konversi didasarkan pada standar baku norma ilmiah olahraga:

### A. Tes Servis Pendek (Short Serve)
```javascript
if (score > 82.2) return 'Sangat Tinggi';
else if (score >= 67) return 'Tinggi';
else if (score >= 51) return 'Sedang';
else if (score >= 36) return 'Kurang';
else return 'Sangat Kurang';
```

### B. Tes Servis Panjang (Long Serve)
```javascript
if (score > 60) return 'Sangat Tinggi';
else if (score >= 47) return 'Tinggi';
else if (score >= 34) return 'Sedang';
else if (score >= 21) return 'Kurang';
else return 'Sangat Kurang';
```

### C. Tes Pukulan Lob (High Clear)
```javascript
if (score > 91) return 'Sangat Tinggi';
else if (score >= 80) return 'Tinggi';
else if (score >= 70) return 'Sedang';
else if (score >= 59) return 'Kurang';
else return 'Sangat Kurang';
```

### D. Tes Pukulan Smash
```javascript
if (score > 33) return 'Sangat Tinggi';
else if (score >= 25) return 'Tinggi';
else if (score >= 17) return 'Sedang';
else if (score >= 8) return 'Kurang';
else return 'Sangat Kurang';
```

### E. Evaluasi Total (Overall Category)
Untuk menentukan kesimpulan evaluasi keseluruhan dari keempat tes tersebut:
1. Setiap kategori dikonversi menjadi bobot angka:
   - **Sangat Tinggi** = 5
   - **Tinggi** = 4
   - **Sedang** = 3
   - **Kurang** = 2
   - **Sangat Kurang** = 1
2. Rata-rata dari nilai numerik yang terkumpul dihitung:
   - `avg >= 4.5` ➡️ **Sangat Tinggi**
   - `avg >= 3.5` ➡️ **Tinggi**
   - `avg >= 2.5` ➡️ **Sedang**
   - `avg >= 1.5` ➡️ **Kurang**
   - `avg < 1.5` ➡️ **Sangat Kurang**
