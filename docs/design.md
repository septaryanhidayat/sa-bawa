# 🎨 UI/UX Design System Guide - SA'BAWA

Dokumen ini menjelaskan rancangan antarmuka (UI/UX) dan sistem desain aplikasi **SA'BAWA**. Panduan ini ditujukan sebagai cetak biru (*blueprint*) desain agar dapat diimplementasikan kembali pada aplikasi baru dengan nuansa visual dan struktur layout serupa.

---

## 1. 🎨 Palet Warna & Tipografi

### A. Palet Warna (Color System)
Menggunakan kombinasi warna bernuansa ungu (*purple/indigo*) yang elegan, dipadukan dengan warna aksen status normatif yang informatif:

- **Warna Latar Utama**: `bg-gradient-to-br from-slate-50 via-purple-50/40 to-indigo-50/30` (Gradien lembut, modern, dan tidak melelahkan mata).
- **Warna Aksen Aplikasi**:
  - Ungu Tua/Aksen Primer: `text-purple-950` / `bg-purple-700` (Untuk teks judul, header, tombol utama, dan fokus utama).
  - Indigo/Aksen Sekunder: `text-indigo-900` / `bg-indigo-600` (Untuk elemen dekoratif dan status pendukung).
- **Status Norma Penilaian (Color-coded Badges)**:
  - **Sangat Tinggi**: Hijau Zamrud (`bg-emerald-100 text-emerald-800 border-emerald-300`)
  - **Tinggi**: Hijau Daun (`bg-green-100 text-green-800 border-green-300`)
  - **Sedang**: Kuning Amber (`bg-amber-100 text-amber-800 border-amber-300`)
  - **Kurang**: Jingga Oranye (`bg-orange-100 text-orange-800 border-orange-300`)
  - **Sangat Kurang**: Merah Mawar (`bg-rose-100 text-rose-800 border-rose-300`)

### B. Tipografi
Menggunakan font sans-serif modern (`font-sans`) seperti **Inter** atau **Outfit** untuk keterbacaan yang tinggi pada data angka dan nama atlet.

---

## 2. 📱 Layout System (Dual-Mode Responsive Design)

Salah satu keunggulan utama desain SA'BAWA adalah **Dual-Mode Layout** yang beradaptasi secara dinamis sesuai resolusi layar perangkat:

```text
+-------------------------------------------------------------------------+
|                          DESKTOP LAYOUT (>= 1024px)                     |
|                                                                         |
|  [Header Bar: Logo, App Name, Login Button]                             |
|  +-----------------------------------+ +------------------------------+ |
|  | LEFT SIDEBAR:                      | | RIGHT SIDEBAR:               | |
|  | - Hero Banner                      | | - Phone Emulator Mockup      | |
|  | - Research Team Highlight          | |   - Mockup status bar        | |
|  | - Features Grid                    | |   - Scrollable App view      | |
|  | - Short Norm Info                  | |   - Fixed bottom navbar      | |
|  +-----------------------------------+ +------------------------------+ |
+-------------------------------------------------------------------------+

+-------------------------------------------------------------------------+
|                          MOBILE LAYOUT (< 1024px)                       |
|                                                                         |
|  [Actual Phone Screen]                                                  |
|  +-------------------------------------------------------------------+  |
|  | - 100% Fullscreen View (No Emulator Frame / Borders)              |  |
|  | - Native Status Bar Hidden (Uses real device status bar)          |  |
|  | - Scrollable App Content Page                                     |  |
|  | - Fixed Bottom Navigation Bar (Stays pinned at bottom)            |  |
|  +-------------------------------------------------------------------+  |
+-------------------------------------------------------------------------+
```

### A. Implementasi Desktop Mockup (`lg:` screen >= 1024px)
Pada desktop, aplikasi ditampilkan di dalam bingkai tiruan *smartphone* di sisi kanan.
- **Bingkai Mockup**: `lg:max-w-md lg:rounded-[36px] lg:border-[8px] lg:border-slate-200 lg:shadow-2xl`
- **Simulasi Status Bar**: `hidden lg:flex` (Menampilkan bar status tiruan dengan sinyal, wifi, baterai, dan jam).
- **Header Desktop**: `hidden lg:block` (Menampilkan navigasi atas khusus monitor desktop).

### B. Implementasi Mobile View (`< lg` screen < 1024px)
Pada HP/Tablet, bingkai tiruan dilepas sehingga halaman mengisi penuh seluruh layar.
- **Bingkai Mockup**: `w-full min-h-screen bg-white rounded-none border-0 shadow-none`
- **Simulasi Status Bar**: `hidden` (Disembunyikan sepenuhnya agar tidak tumpang tindih dengan status bar bawaan HP).
- **Header Desktop**: `hidden` (Dihilangkan untuk menghemat ruang vertikal layar HP).
- **Bottom Navigation Bar**: `fixed bottom-0 inset-x-0` (Menempel di dasar layar HP, memudahkan navigasi jempol tangan).

---

## 3. 🧩 UI Components & Patterns

### A. Bank-Card style Header
Header ringkasan skor/dashboard utama didesain menyerupai kartu ATM/Bank dengan latar gradien ungu-biru cerah (`bank-card-bright` style) untuk memberikan kesan premium dan modern.

### B. 8-Icon Grid Menu
Menu navigasi utama pada tab Home menggunakan format grid 4 kolom (`grid-cols-4`) dengan ikon yang terbungkus kotak bulat berwarna lembut (*pastel bg*) dan berukuran proporsional untuk akses layar sentuh (*touch target* minimal 40px).

### C. Print Media Optimization
Untuk cetak rekapitulasi data fisik atau ekspor ke PDF:
- Menggunakan kelas utility **`no-print`** pada elemen navigasi, tombol ekspor, tombol login, header desktop, dan bar navigasi bawah agar tidak ikut tercetak di atas kertas.
- Mengatur area tabel laporan agar melebar otomatis (`w-full`) saat dicetak.
