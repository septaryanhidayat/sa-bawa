<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SA'BAWA - Silvi Aryanti' Badminton Assessment WebApp</title>
    <link rel="icon" type="image/png" href="/images/logo1.png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Tailwind & App Styles -->
    <?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php else: ?>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        },
                        colors: {
                            purple: {
                                50: '#faf5ff',
                                100: '#f3e8ff',
                                200: '#e9d5ff',
                                300: '#d8b4fe',
                                400: '#c084fc',
                                500: '#a855f7',
                                600: '#9333ea',
                                700: '#7e22ce',
                                800: '#6b21a8',
                                900: '#581c87',
                                950: '#3b0764',
                            }
                        }
                    }
                }
            }
        </script>
        <style>
            .glass-card-light {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(168, 85, 247, 0.15);
                box-shadow: 0 10px 30px -5px rgba(126, 34, 206, 0.08);
            }
            .bank-card-bright {
                background: linear-gradient(135deg, #7e22ce 0%, #6b21a8 50%, #4c1d95 100%);
                box-shadow: 0 15px 35px -5px rgba(107, 33, 168, 0.35);
            }
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: #f8fafc; }
            ::-webkit-scrollbar-thumb { background: #c084fc; border-radius: 9999px; }
            @media print {
                .no-print { display: none !important; }
                .print-only { display: block !important; }
                body { background: white !important; color: black !important; }
            }
        </style>
    <?php endif; ?>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gradient-to-br from-slate-50 via-purple-50/40 to-indigo-50/30 text-slate-800 font-sans min-h-screen selection:bg-purple-600 selection:text-white" x-data="sabawaApp()">

    <!-- TOP HEADER / CONTROL BAR (DESKTOP) -->
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-purple-100/80 shadow-sm no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Brand Logo & Title -->
            <div class="flex items-center space-x-3 cursor-pointer" @click="activeTab = 'home'">
                <img src="/images/logo1.png" alt="SA'BAWA Logo" class="h-10 w-auto object-contain" onError="this.onerror=null; this.src='/images/logo1.png';">
                <div class="hidden sm:block">
                    <h1 class="font-extrabold text-lg text-purple-950 tracking-tight leading-none">SA'BAWA</h1>
                    <p class="text-[10px] text-purple-700 font-semibold">Silvi Aryanti' Badminton Assessment WebApp</p>
                </div>
            </div>

            <!-- Admin Badge & Controls -->
            <div class="flex items-center space-x-3">
                <template x-if="isAdmin">
                    <div class="flex items-center space-x-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-3.5 py-1.5 rounded-full text-xs font-bold shadow-md">
                        <span class="w-2 h-2 rounded-full bg-emerald-300 animate-ping"></span>
                        <span><i class="fa-solid fa-user-shield mr-1"></i> Admin Logged In</span>
                        <button @click="logoutAdmin()" class="ml-1 hover:text-emerald-200" title="Keluar Mode Admin">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </div>
                </template>

                <template x-if="!isAdmin">
                    <button @click="showLoginModal = true" class="flex items-center space-x-1.5 bg-gradient-to-r from-purple-700 to-indigo-700 hover:from-purple-800 hover:to-indigo-800 text-white px-4 py-1.5 rounded-full text-xs font-bold shadow-md transition-all">
                        <i class="fa-solid fa-lock"></i>
                        <span>Login Admin</span>
                    </button>
                </template>
            </div>
        </div>
    </header>

    <!-- MAIN PAGE CONTENT CONTAINER -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- DESKTOP LEFT SIDEBAR: BANNER WEBPAGE UMUM (Visible on Desktop LG screens) -->
            <div class="hidden lg:block lg:col-span-7 space-y-6">
                
                <!-- HERO BANNER CARD -->
                <div class="bg-white rounded-3xl p-8 border border-purple-100 shadow-xl shadow-purple-900/5 relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-purple-100/60 rounded-full blur-3xl pointer-events-none"></div>
                    
                    <div class="flex items-center space-x-3 mb-4">
                        <span class="bg-purple-100 text-purple-800 font-extrabold text-xs px-3 py-1 rounded-full uppercase tracking-wider">
                            Aplikasi Resmi Asesmen Bulutangkis
                        </span>
                        <span class="text-xs text-slate-500 font-medium">FKIP Penjaskes Sriwijaya</span>
                    </div>

                    <h2 class="text-3xl font-black text-slate-900 tracking-tight leading-tight">
                        Pengembangan Instrumen Penilaian Teknik Dasar Bulutangkis
                    </h2>

                    <p class="text-sm text-slate-600 mt-3 leading-relaxed">
                        Aplikasi <strong>SA'BAWA</strong> (Silvi Aryanti' Badminton Assessment WebApp) dirancang khusus untuk mempermudah penilaian dan pengolahan skor tes 4 teknik dasar bulutangkis secara otomatis berdasarkan standar norma ilmiah.
                    </p>

                    <!-- RESEARCH TEAM HIGHLIGHT -->
                    <div class="mt-6 pt-6 border-t border-slate-100">
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Tim Peneliti & Pengembang</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-purple-50/70 p-3 rounded-2xl border border-purple-100 flex items-center space-x-3">
    <!-- Foto profil otomatis ter-crop 1:1 -->
    <img 
        src="images/Picture1.png" 
        alt="Foto Silvi Aryanti" 
        class="w-10 h-10 aspect-square object-cover rounded-xl shadow shrink-0" 
    />
    
    <div>
        <h5 class="font-extrabold text-xs text-purple-950">Silvi Aryanti, M.Pd.</h5>
        <p class="text-[10px] text-purple-700 font-medium">Ketua Peneliti • NIDN 0021079101</p>
    </div>
</div>

                            <div class="bg-slate-50 p-3 rounded-2xl border border-slate-200/80 flex items-center space-x-3">
    <!-- Gambar otomatis di-crop rasio 1:1 -->
    <img 
        src="images/Picture2.png" 
        alt="Foto Destriana" 
        class="w-10 h-10 aspect-square object-cover rounded-xl shadow shrink-0" 
    />
    
    <div>
        <h5 class="font-extrabold text-xs text-slate-900">Destriana, M.Pd.</h5>
        <p class="text-[10px] text-slate-500">Anggota 1 • NIDN 0001128905</p>
    </div>
</div>

                            <!-- Anggota 2: Fitri Agung Nanda -->
<div class="bg-slate-50 p-3 rounded-2xl border border-slate-200/80 flex items-center space-x-3">
    <img 
        src="images/Picture3.png" 
        alt="Foto Fitri Agung Nanda" 
        class="w-10 h-10 aspect-square object-cover rounded-xl shadow shrink-0" 
    />
    <div>
        <h5 class="font-extrabold text-xs text-slate-900">Fitri Agung Nanda, M.Pd.</h5>
        <p class="text-[10px] text-slate-500">Anggota 2 • NIDN 0016039408</p>
    </div>
</div>

<!-- Anggota 3: Soleh Solahuddin -->
<div class="bg-slate-50 p-3 rounded-2xl border border-slate-200/80 flex items-center space-x-3">
    <img 
        src="images/Picture4.png" 
        alt="Foto Soleh Solahuddin" 
        class="w-10 h-10 aspect-square object-cover rounded-xl shadow shrink-0" 
    />
    <div>
        <h5 class="font-extrabold text-xs text-slate-900">Soleh Solahuddin, M.Pd.</h5>
        <p class="text-[10px] text-slate-500">Anggota 3 • NIDK 8898323419</p>
    </div>
</div>
                        </div>
                    </div>

                    <!-- FEATURE HIGHLIGHTS GRID -->
                    <div class="mt-6 grid grid-cols-4 gap-3 text-center">
                        <div class="p-3 rounded-2xl bg-purple-50/60 border border-purple-100" @click="activeTab = 'form'" class="cursor-pointer">
                            <i class="fa-solid fa-pen-to-square text-xl text-purple-700 mb-1"></i>
                            <h5 class="text-xs font-bold text-slate-800">Form Asesmen</h5>
                            <p class="text-[10px] text-slate-500">Input 20x Tes</p>
                        </div>

                        <div class="p-3 rounded-2xl bg-blue-50/60 border border-blue-100" @click="activeTab = 'data'" class="cursor-pointer">
                            <i class="fa-solid fa-calculator text-xl text-blue-700 mb-1"></i>
                            <h5 class="text-xs font-bold text-slate-800">Norma Otomatis</h5>
                            <p class="text-[10px] text-slate-500">5 Skala Kategori</p>
                        </div>

                        <div class="p-3 rounded-2xl bg-emerald-50/60 border border-emerald-100" @click="activeTab = 'materi'" class="cursor-pointer">
                            <i class="fa-solid fa-book-open text-xl text-emerald-700 mb-1"></i>
                            <h5 class="text-xs font-bold text-slate-800">Materi & Lapangan</h5>
                            <p class="text-[10px] text-slate-500">Pedoman Lengkap</p>
                        </div>

                        <div class="p-3 rounded-2xl bg-rose-50/60 border border-rose-100" @click="activeTab = 'video'" class="cursor-pointer">
                            <i class="fa-solid fa-circle-play text-xl text-rose-700 mb-1"></i>
                            <h5 class="text-xs font-bold text-slate-800">Video Tutorial</h5>
                            <p class="text-[10px] text-slate-500">Panduan Peragaan</p>
                        </div>
                    </div>
                </div>

                <!-- QUICK BANNER INFORMASI NORMA PENILAIAN -->
                <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-lg space-y-3">
                    <h3 class="font-extrabold text-sm text-slate-900 flex items-center">
                        <i class="fa-solid fa-award text-purple-600 mr-2"></i> Ringkasan Norma Penilaian Tes Bulutangkis
                    </h3>

                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200">
                            <h4 class="font-bold text-purple-900">1. Servis Pendek (Short Serve)</h4>
                            <p class="text-[11px] text-slate-600 mt-1">Target paling dalam = 5. Norma Sangat Tinggi: &gt; 82.2, Tinggi: 67-82, Sedang: 51-66.</p>
                        </div>

                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200">
                            <h4 class="font-bold text-indigo-900">2. Servis Panjang (Long Serve)</h4>
                            <p class="text-[11px] text-slate-600 mt-1">Servis melambung jauh. Norma Sangat Tinggi: &gt; 60, Tinggi: 47-60, Sedang: 34-46.</p>
                        </div>

                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200">
                            <h4 class="font-bold text-blue-900">3. Tes Lob (High Clear)</h4>
                            <p class="text-[11px] text-slate-600 mt-1">Melampaui tali setinggi 155 cm (8 kaki). Norma Sangat Tinggi: &gt; 91, Tinggi: 80-90.</p>
                        </div>

                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200">
                            <h4 class="font-bold text-rose-900">4. Tes Smash (Smash Test)</h4>
                            <p class="text-[11px] text-slate-600 mt-1">Umpan forehand panjang 20x. Norma Sangat Tinggi: &gt; 33, Tinggi: 25-32.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MOBILE APP FRAME CONTAINER (Sisi Kanan di Desktop LG / Full Width di Mobile MD & SM) -->
            <div class="w-full lg:col-span-5 flex justify-center sticky top-20">
                
                <!-- MOBILE BANK FRAME CONTAINER -->
                <div class="w-full max-w-md bg-white rounded-[36px] border-[8px] border-slate-200 shadow-2xl shadow-purple-900/10 overflow-hidden relative min-h-[760px] flex flex-col">

                    <!-- MOBILE STATUS BAR -->
                    <div class="bg-slate-900 text-white px-6 py-2 flex justify-between items-center text-[11px] font-semibold no-print">
                        <span x-text="currentTime">9:41</span>
                        <div class="flex items-center space-x-1.5">
                            <span class="text-[9px] bg-purple-600 text-white px-1.5 py-0.5 rounded font-mono">SA'BAWA MOBILE</span>
                            <i class="fa-solid fa-signal text-[10px]"></i>
                            <i class="fa-solid fa-wifi text-[10px]"></i>
                            <i class="fa-solid fa-battery-full text-emerald-400 text-[10px]"></i>
                        </div>
                    </div>

                    <!-- APP INNER SCREEN WRAPPER -->
                    <div class="flex-1 overflow-y-auto pb-20 no-scrollbar bg-slate-50">

                        <!-- 1. HOME TAB (MOBILE BANKING DASHBOARD STYLE) -->
                        <div x-show="activeTab === 'home'" x-transition:enter="transition ease-out duration-200" class="p-4 space-y-4">
                            
                            <!-- USER GREETING & APP BANK CARD HEADER -->
                            <div class="bank-card-bright rounded-2xl p-5 text-white shadow-xl relative overflow-hidden">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <span class="bg-white/20 backdrop-blur-md text-[11px] px-2.5 py-0.5 rounded-full font-medium text-purple-100">
                                                👋 Halo, Selamat Datang
                                            </span>
                                            <span class="text-[11px] text-purple-200 font-semibold" x-text="isAdmin ? 'Admin' : 'Guest'"></span>
                                        </div>
                                        <h2 class="text-xl font-extrabold mt-1 tracking-tight">SA'BAWA Assessment</h2>
                                        <p class="text-[11px] text-purple-200 mt-0.5">Badminton Assessment WebApp</p>
                                    </div>
                                    <img src="/images/logo1.png" alt="Logo" class="h-10 w-auto object-contain filter drop-shadow" onError="this.onerror=null; this.src='/images/logo1.png';">
                                </div>

                                <!-- BANKING-STYLE QUICK STATS / SALDO SKOR WIDGET -->
                                <div class="mt-4 pt-3 border-t border-purple-400/30 grid grid-cols-3 gap-2 text-center">
                                    <div class="bg-black/20 rounded-xl p-2 backdrop-blur-sm">
                                        <p class="text-[9px] text-purple-200 uppercase font-semibold">Total Testee</p>
                                        <p class="text-base font-extrabold text-white" x-text="records.length"></p>
                                    </div>
                                    <div class="bg-black/20 rounded-xl p-2 backdrop-blur-sm">
                                        <p class="text-[9px] text-purple-200 uppercase font-semibold">Rata-Rata</p>
                                        <p class="text-base font-extrabold text-amber-300" x-text="getAverageScore()"></p>
                                    </div>
                                    <div class="bg-black/20 rounded-xl p-2 backdrop-blur-sm">
                                        <p class="text-[9px] text-purple-200 uppercase font-semibold">Sangat Tinggi</p>
                                        <p class="text-base font-extrabold text-emerald-300" x-text="getCategoryCount('Sangat Tinggi')"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- QUICK ACTION GRID (MOBILE BANKING 8-ICON MENU) -->
                            <div>
                                <div class="flex justify-between items-center mb-2.5">
                                    <h3 class="font-bold text-xs text-slate-800 flex items-center">
                                        <i class="fa-solid fa-grid-2 text-purple-600 mr-1.5"></i> Menu Utama
                                    </h3>
                                    <span class="text-[10px] text-purple-700 font-semibold cursor-pointer" @click="activeTab = 'materi'">Panduan &rarr;</span>
                                </div>

                                <div class="grid grid-cols-4 gap-2.5">
                                    <!-- 1. Form Input -->
                                    <button @click="activeTab = 'form'" class="flex flex-col items-center justify-center p-2.5 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-purple-300 transition-all group">
                                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-pen-to-square text-base"></i>
                                        </div>
                                        <span class="text-[10px] font-semibold text-slate-700 mt-1.5 text-center">Isi Data</span>
                                    </button>

                                    <!-- 2. Tampil Data -->
                                    <button @click="activeTab = 'data'" class="flex flex-col items-center justify-center p-2.5 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-purple-300 transition-all group">
                                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-table-list text-base"></i>
                                        </div>
                                        <span class="text-[10px] font-semibold text-slate-700 mt-1.5 text-center">Tampil Data</span>
                                    </button>

                                    <!-- 3. Materi -->
                                    <button @click="activeTab = 'materi'" class="flex flex-col items-center justify-center p-2.5 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-purple-300 transition-all group">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-book-open text-base"></i>
                                        </div>
                                        <span class="text-[10px] font-semibold text-slate-700 mt-1.5 text-center">Materi Tes</span>
                                    </button>

                                    <!-- 4. Video -->
                                    <button @click="activeTab = 'video'" class="flex flex-col items-center justify-center p-2.5 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-purple-300 transition-all group">
                                        <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-circle-play text-base"></i>
                                        </div>
                                        <span class="text-[10px] font-semibold text-slate-700 mt-1.5 text-center">Video</span>
                                    </button>

                                    <!-- 5. FAQ -->
                                    <button @click="activeTab = 'faq'" class="flex flex-col items-center justify-center p-2.5 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-purple-300 transition-all group">
                                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-circle-question text-base"></i>
                                        </div>
                                        <span class="text-[10px] font-semibold text-slate-700 mt-1.5 text-center">FAQ</span>
                                    </button>

                                    <!-- 6. About / Tim Peneliti -->
                                    <button @click="activeTab = 'about'" class="flex flex-col items-center justify-center p-2.5 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-purple-300 transition-all group">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-users text-base"></i>
                                        </div>
                                        <span class="text-[10px] font-semibold text-slate-700 mt-1.5 text-center">About</span>
                                    </button>

                                    <!-- 7. Norma Penilaian -->
                                    <button @click="activeTab = 'materi'; activeMateriTab = 'overview'" class="flex flex-col items-center justify-center p-2.5 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-purple-300 transition-all group">
                                        <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-chart-pie text-base"></i>
                                        </div>
                                        <span class="text-[10px] font-semibold text-slate-700 mt-1.5 text-center">Norma Tes</span>
                                    </button>

                                    <!-- 8. Sample Generator -->
                                    <button @click="seedSampleData()" class="flex flex-col items-center justify-center p-2.5 rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:border-purple-300 transition-all group" title="Muat Contoh Data Tes">
                                        <div class="w-10 h-10 rounded-xl bg-fuchsia-100 text-fuchsia-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-wand-magic-sparkles text-base"></i>
                                        </div>
                                        <span class="text-[10px] font-semibold text-slate-700 mt-1.5 text-center">Isi Contoh</span>
                                    </button>
                                </div>
                            </div>

                            <!-- BANNER INFORMASI SINGKAT -->
                            <div class="bg-purple-50 rounded-2xl p-3.5 border border-purple-100 flex items-start space-x-3">
                                <div class="p-2 rounded-xl bg-purple-600 text-white shrink-0 text-xs">
                                    <i class="fa-solid fa-circle-info"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-xs text-purple-950">Instrumen Penilaian Bulutangkis</h4>
                                    <p class="text-[10px] text-slate-600 mt-0.5 leading-relaxed">
                                        Oleh <strong>Silvi Aryanti, M.Pd.</strong> & Tim. Mengukur 4 teknik dasar secara konversi norma otomatis.
                                    </p>
                                </div>
                            </div>

                            <!-- RECENT ASSESSMENTS LIST (MOBILE BANKING TRANSACTION HISTORY STYLE) -->
                            <div class="space-y-2.5">
                                <div class="flex justify-between items-center">
                                    <h3 class="font-bold text-xs text-slate-800 flex items-center">
                                        <i class="fa-solid fa-clock-rotate-left text-purple-600 mr-1.5"></i> Penilaian Terbaru
                                    </h3>
                                    <span class="text-[10px] text-purple-700 font-semibold cursor-pointer" @click="activeTab = 'data'">Semua (&plus;<span x-text="records.length"></span>)</span>
                                </div>

                                <div class="space-y-2">
                                    <template x-if="records.length === 0">
                                        <div class="text-center py-6 bg-white rounded-2xl border border-slate-200">
                                            <i class="fa-solid fa-folder-open text-xl text-slate-400 mb-1"></i>
                                            <p class="text-[11px] text-slate-500">Belum ada data penilaian.</p>
                                        </div>
                                    </template>

                                    <template x-for="(item, index) in records.slice(0, 3)" :key="index">
                                        <div class="bg-white rounded-2xl p-3 flex justify-between items-center border border-slate-200/80 shadow-sm hover:border-purple-300 transition-all cursor-pointer" @click="openDetailModal(item)">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-xs shadow"
                                                     :class="item.jenisKelamin === 'L' ? 'bg-gradient-to-tr from-blue-600 to-cyan-600' : 'bg-gradient-to-tr from-pink-600 to-rose-600'">
                                                    <span x-text="item.nama.charAt(0).toUpperCase()"></span>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-xs text-slate-900" x-text="item.nama"></h4>
                                                    <p class="text-[10px] text-slate-500">NIM: <span x-text="item.nim"></span> • <span x-text="item.kelas"></span></p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold"
                                                      :class="getCategoryBadgeClass(item.evaluasiTotal)">
                                                    <span x-text="item.evaluasiTotal"></span>
                                                </span>
                                                <p class="text-[9px] text-slate-400 mt-0.5" x-text="item.tanggal"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- 2. FORM ISI DATA TAB -->
                        <div x-show="activeTab === 'form'" x-transition:enter="transition ease-out duration-200" class="p-4 space-y-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-base font-extrabold text-purple-950">
                                        Form Input Penilaian
                                    </h2>
                                    <p class="text-[11px] text-slate-500">Masukkan identitas & hasil percobaan tes</p>
                                </div>
                                <span class="bg-purple-100 text-purple-800 text-[10px] px-2.5 py-0.5 rounded-full font-mono font-bold">
                                    FORM
                                </span>
                            </div>

                            <form @submit.prevent="saveRecord()" class="space-y-4">
                                <!-- IDENTITAS TESTEE -->
                                <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm space-y-3">
                                    <h3 class="text-xs font-bold text-purple-900 uppercase tracking-wider border-b border-slate-100 pb-2">
                                        <i class="fa-solid fa-user text-purple-600 mr-1"></i> Identitas Peserta Tes
                                    </h3>

                                    <div class="space-y-2.5">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-700 mb-1">Nama Lengkap Testee *</label>
                                            <input type="text" x-model="form.nama" required placeholder="Muhammad Farhan"
                                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-purple-600">
                                        </div>

                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-700 mb-1">NIM / NIS *</label>
                                                <input type="text" x-model="form.nim" required placeholder="06121001001"
                                                       class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-purple-600">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-700 mb-1">Jenis Kelamin *</label>
                                                <select x-model="form.gender" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-purple-600">
                                                    <option value="L">Laki-Laki (L)</option>
                                                    <option value="P">Perempuan (P)</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-700 mb-1">Kelas / Angkatan</label>
                                                <input type="text" x-model="form.kelas" placeholder="Palembang A 2024"
                                                       class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-purple-600">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-700 mb-1">Tanggal Tes</label>
                                                <input type="date" x-model="form.tanggal" required
                                                       class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-purple-600">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-700 mb-1">Dosen Penguji</label>
                                            <input type="text" x-model="form.penguji" placeholder="Silvi Aryanti, M.Pd."
                                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-purple-600">
                                        </div>
                                    </div>
                                </div>

                                <!-- INPUT SKOR HASIL TES -->
                                <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm space-y-3">
                                    <h3 class="text-xs font-bold text-purple-900 uppercase tracking-wider border-b border-slate-100 pb-2 flex justify-between items-center">
                                        <span><i class="fa-solid fa-list-check text-purple-600 mr-1"></i> Skor Tes & Konversi Norma</span>
                                        <span class="text-[9px] text-amber-700 font-bold">20x Percobaan</span>
                                    </h3>

                                    <!-- 1. SERVIS PENDEK -->
                                    <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-200 space-y-1.5">
                                        <div class="flex justify-between items-center">
                                            <label class="text-xs font-bold text-slate-800">1. Servis Pendek (Short Serve)</label>
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold" :class="getCategoryBadgeClass(calculateNormaServisPendek(form.skorServisPendek))">
                                                <span x-text="calculateNormaServisPendek(form.skorServisPendek)"></span>
                                            </span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="number" min="0" max="100" x-model.number="form.skorServisPendek" placeholder="Skor"
                                                   class="w-24 bg-white border border-slate-300 rounded-lg px-3 py-1 text-xs text-slate-900 font-bold text-center focus:outline-none focus:border-purple-600">
                                            <p class="text-[9px] text-slate-500">Norma: &gt;82.2 (Sangat Tinggi), 67-82 (Tinggi), 51-66 (Sedang)</p>
                                        </div>
                                    </div>

                                    <!-- 2. SERVIS PANJANG -->
                                    <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-200 space-y-1.5">
                                        <div class="flex justify-between items-center">
                                            <label class="text-xs font-bold text-slate-800">2. Servis Panjang (Long Serve)</label>
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold" :class="getCategoryBadgeClass(calculateNormaServisPanjang(form.skorServisPanjang))">
                                                <span x-text="calculateNormaServisPanjang(form.skorServisPanjang)"></span>
                                            </span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="number" min="0" max="100" x-model.number="form.skorServisPanjang" placeholder="Skor"
                                                   class="w-24 bg-white border border-slate-300 rounded-lg px-3 py-1 text-xs text-slate-900 font-bold text-center focus:outline-none focus:border-purple-600">
                                            <p class="text-[9px] text-slate-500">Norma: &gt;60 (Sangat Tinggi), 47-60 (Tinggi), 34-46 (Sedang)</p>
                                        </div>
                                    </div>

                                    <!-- 3. PUKULAN LOB -->
                                    <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-200 space-y-1.5">
                                        <div class="flex justify-between items-center">
                                            <label class="text-xs font-bold text-slate-800">3. Pukulan Lob (High Clear)</label>
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold" :class="getCategoryBadgeClass(calculateNormaLob(form.skorLob))">
                                                <span x-text="calculateNormaLob(form.skorLob)"></span>
                                            </span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="number" min="0" max="100" x-model.number="form.skorLob" placeholder="Skor"
                                                   class="w-24 bg-white border border-slate-300 rounded-lg px-3 py-1 text-xs text-slate-900 font-bold text-center focus:outline-none focus:border-purple-600">
                                            <p class="text-[9px] text-slate-500">Norma: &gt;91 (Sangat Tinggi), 80-90 (Tinggi), 70-79 (Sedang)</p>
                                        </div>
                                    </div>

                                    <!-- 4. PUKULAN SMASH -->
                                    <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-200 space-y-1.5">
                                        <div class="flex justify-between items-center">
                                            <label class="text-xs font-bold text-slate-800">4. Pukulan Smash (Smash Test)</label>
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold" :class="getCategoryBadgeClass(calculateNormaSmash(form.skorSmash))">
                                                <span x-text="calculateNormaSmash(form.skorSmash)"></span>
                                            </span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="number" min="0" max="100" x-model.number="form.skorSmash" placeholder="Skor"
                                                   class="w-24 bg-white border border-slate-300 rounded-lg px-3 py-1 text-xs text-slate-900 font-bold text-center focus:outline-none focus:border-purple-600">
                                            <p class="text-[9px] text-slate-500">Norma: &gt;33 (Sangat Tinggi), 25-32 (Tinggi), 17-24 (Sedang)</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- SUBMIT BUTTON -->
                                <div class="bank-card-bright rounded-2xl p-4 text-white flex justify-between items-center">
                                    <div>
                                        <p class="text-[9px] text-purple-200 font-semibold uppercase">Evaluasi Akhir</p>
                                        <h3 class="text-base font-extrabold" x-text="calculateOverallCategory(form.skorServisPendek, form.skorServisPanjang, form.skorLob, form.skorSmash)"></h3>
                                    </div>
                                    <button type="submit" class="bg-white text-purple-900 font-extrabold px-4 py-2 rounded-xl text-xs hover:bg-purple-50 shadow-md">
                                        <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Data
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- 3. TAMPIL DATA TAB -->
                        <div x-show="activeTab === 'data'" x-transition:enter="transition ease-out duration-200" class="p-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-base font-extrabold text-purple-950">Rekap Data Asesmen</h2>
                                    <p class="text-[11px] text-slate-500">Daftar hasil tes keterampilan siswa</p>
                                </div>
                                <div class="flex space-x-1">
                                    <button @click="exportToCSV()" class="px-2.5 py-1 bg-emerald-600 text-white rounded-lg text-[10px] font-bold">CSV</button>
                                    <button @click="printAllReport()" class="px-2.5 py-1 bg-purple-700 text-white rounded-lg text-[10px] font-bold">Cetak</button>
                                </div>
                            </div>

                            <!-- SEARCH -->
                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-xs text-slate-400"></i>
                                <input type="text" x-model="searchQuery" placeholder="Cari nama atau NIM..."
                                       class="w-full bg-white border border-slate-300 rounded-xl pl-8 pr-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-purple-600">
                            </div>

                            <!-- RECAP LIST -->
                            <div class="space-y-2">
                                <template x-if="filteredRecords.length === 0">
                                    <div class="text-center py-8 bg-white rounded-2xl border border-slate-200">
                                        <p class="text-xs text-slate-500">Tidak ada data ditemukan.</p>
                                    </div>
                                </template>

                                <template x-for="(item, index) in filteredRecords" :key="index">
                                    <div class="bg-white rounded-2xl p-3 border border-slate-200 shadow-sm space-y-2">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-extrabold text-xs text-slate-900" x-text="item.nama"></h4>
                                                <p class="text-[10px] text-slate-500">NIM: <span x-text="item.nim"></span> • <span x-text="item.kelas"></span></p>
                                            </div>
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold" :class="getCategoryBadgeClass(item.evaluasiTotal)" x-text="item.evaluasiTotal"></span>
                                        </div>

                                        <div class="grid grid-cols-4 gap-1 bg-slate-50 p-2 rounded-xl text-[9px] text-center font-semibold">
                                            <div>SP: <span class="font-extrabold text-purple-700" x-text="item.skorServisPendek ?? 0"></span></div>
                                            <div>SJ: <span class="font-extrabold text-indigo-700" x-text="item.skorServisPanjang ?? 0"></span></div>
                                            <div>Lob: <span class="font-extrabold text-blue-700" x-text="item.skorLob ?? 0"></span></div>
                                            <div>Smash: <span class="font-extrabold text-rose-700" x-text="item.skorSmash ?? 0"></span></div>
                                        </div>

                                        <div class="flex justify-between items-center text-[10px] pt-1">
                                            <span class="text-slate-400" x-text="item.tanggal"></span>
                                            <div class="flex space-x-1">
                                                <button @click="openDetailModal(item)" class="px-2 py-0.5 bg-purple-100 text-purple-800 font-bold rounded-lg">Rincian</button>
                                                <template x-if="isAdmin">
                                                    <button @click="deleteRecord(item.id)" class="px-2 py-0.5 bg-rose-100 text-rose-700 font-bold rounded-lg">Hapus</button>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- 4. MATERI TAB -->
                        <div x-show="activeTab === 'materi'" x-transition:enter="transition ease-out duration-200" class="p-4 space-y-3">
                            <h2 class="text-base font-extrabold text-purple-950">Materi Asesmen</h2>
                            <div class="flex space-x-1 overflow-x-auto pb-1 no-scrollbar text-xs">
                                <button @click="activeMateriTab = 'overview'" :class="activeMateriTab === 'overview' ? 'bg-purple-700 text-white font-bold' : 'bg-white text-slate-700 border border-slate-200'" class="px-2.5 py-1 rounded-xl shrink-0">Overview</button>
                                <button @click="activeMateriTab = 'pendek'" :class="activeMateriTab === 'pendek' ? 'bg-purple-700 text-white font-bold' : 'bg-white text-slate-700 border border-slate-200'" class="px-2.5 py-1 rounded-xl shrink-0">Servis Pendek</button>
                                <button @click="activeMateriTab = 'panjang'" :class="activeMateriTab === 'panjang' ? 'bg-purple-700 text-white font-bold' : 'bg-white text-slate-700 border border-slate-200'" class="px-2.5 py-1 rounded-xl shrink-0">Servis Panjang</button>
                                <button @click="activeMateriTab = 'lob'" :class="activeMateriTab === 'lob' ? 'bg-purple-700 text-white font-bold' : 'bg-white text-slate-700 border border-slate-200'" class="px-2.5 py-1 rounded-xl shrink-0">Tes Lob</button>
                                <button @click="activeMateriTab = 'smash'" :class="activeMateriTab === 'smash' ? 'bg-purple-700 text-white font-bold' : 'bg-white text-slate-700 border border-slate-200'" class="px-2.5 py-1 rounded-xl shrink-0">Tes Smash</button>
                            </div>

                            <div class="bg-white p-4 rounded-2xl border border-slate-200 text-xs leading-relaxed space-y-2">
                                <template x-if="activeMateriTab === 'overview'">
                                    <div>
                                        <h4 class="font-bold text-purple-900">Instrumen Penilaian Bulutangkis</h4>
                                        <p class="text-slate-600 mt-1">Dikembangkan oleh <strong>Silvi Aryanti, M.Pd.</strong> (Sugiyono, 2009: 148 & Suharsimi Arikunto, 2013: 193).</p>
                                    </div>
                                </template>
                                <template x-if="activeMateriTab === 'pendek'">
                                    <div>
                                        <h4 class="font-bold text-purple-900">1. Servis Pendek (Manurung 2018)</h4>
                                        <p class="text-slate-600 mt-1">Servis mengarahkan shuttlecock dekat net dengan konsentrasi tinggi. Kesempatan 20 kali.</p>
                                    </div>
                                </template>
                                <template x-if="activeMateriTab === 'panjang'">
                                    <div>
                                        <h4 class="font-bold text-purple-900">2. Servis Panjang (Bayu Tri Kurniawan 2018:54)</h4>
                                        <p class="text-slate-600 mt-1">Servis melambung jauh dekat garis belakang lawan. Kesempatan 20 kali.</p>
                                    </div>
                                </template>
                                <template x-if="activeMateriTab === 'lob'">
                                    <div>
                                        <h4 class="font-bold text-purple-900">3. Tes Lob (High Clear Test)</h4>
                                        <p class="text-slate-600 mt-1">Pukulan melampaui tali setinggi 155 cm (8 kaki). Skor sasaran: 5, 4, 3, 2.</p>
                                    </div>
                                </template>
                                <template x-if="activeMateriTab === 'smash'">
                                    <div>
                                        <h4 class="font-bold text-purple-900">4. Tes Smash (Smash Test)</h4>
                                        <p class="text-slate-600 mt-1">Smash lurus/silang dari umpan forehand panjang testor sebanyak 20 kali.</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- 5. VIDEO TAB -->
                        <div x-show="activeTab === 'video'" x-transition:enter="transition ease-out duration-200" class="p-4 space-y-3">
                            <h2 class="text-base font-extrabold text-purple-950">Video Tutorial</h2>
                            <div class="space-y-2">
                                <div class="bg-white p-3 rounded-2xl border border-slate-200 flex items-center space-x-3 cursor-pointer" @click="openVideoModal('https://www.youtube.com/embed/5D2Y8JtK11A', 'Servis Pendek')">
                                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-purple-700 font-bold shrink-0">
                                        <i class="fa-solid fa-play"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-xs text-slate-900">Teknik Servis Pendek Backhand</h4>
                                        <p class="text-[10px] text-slate-500">Panduan rincian gerakan</p>
                                    </div>
                                </div>

                                <div class="bg-white p-3 rounded-2xl border border-slate-200 flex items-center space-x-3 cursor-pointer" @click="openVideoModal('https://www.youtube.com/embed/sLd2vHnQO9k', 'Servis Panjang')">
                                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-700 font-bold shrink-0">
                                        <i class="fa-solid fa-play"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-xs text-slate-900">Teknik Servis Panjang Forehand</h4>
                                        <p class="text-[10px] text-slate-500">Panduan servis melambung tinggi</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 6. FAQ TAB -->
                        <div x-show="activeTab === 'faq'" x-transition:enter="transition ease-out duration-200" class="p-4 space-y-3">
                            <h2 class="text-base font-extrabold text-purple-950">FAQ & Bantuan</h2>
                            <div class="space-y-2">
                                <template x-for="(faq, idx) in faqs" :key="idx">
                                    <div class="bg-white p-3 rounded-2xl border border-slate-200 text-xs space-y-1">
                                        <h4 class="font-bold text-purple-900" x-text="faq.q"></h4>
                                        <p class="text-[11px] text-slate-600" x-text="faq.a"></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- 7. ABOUT TAB -->
                        <div x-show="activeTab === 'about'" x-transition:enter="transition ease-out duration-200" class="p-4 space-y-3 text-center">
                            <img src="/images/logo1.png" alt="SA'BAWA" class="h-20 mx-auto object-contain" onError="this.onerror=null; this.src='/images/logo1.png
                            g';">
                            <h2 class="text-lg font-black text-purple-950">SA'BAWA WebApp</h2>
                            <p class="text-xs text-slate-600">Pengembangan Instrumen Penilaian Teknik Dasar Bulutangkis Berbasis Aplikasi Web</p>
                            <div class="bg-white p-3 rounded-2xl border border-slate-200 text-left space-y-1 text-xs">
                                <p class="font-bold text-purple-900">Ketua: Silvi Aryanti, M.Pd.</p>
                                <p class="text-[11px] text-slate-500">Anggota: 1. Destriana, M.Pd. | 2. Fitri Agung Nanda, M.Pd. | 3. Soleh Solahuddin, M.Pd.</p>
                            </div>
                        </div>

                    </div>

                    <!-- BOTTOM MOBILE BANK NAVIGATION BAR -->
                    <nav class="absolute bottom-0 inset-x-0 bg-white/95 backdrop-blur-xl border-t border-slate-200 py-2 px-2 grid grid-cols-6 text-center no-print z-30 shadow-lg">
                        <button @click="activeTab = 'home'" :class="activeTab === 'home' ? 'text-purple-700 font-extrabold' : 'text-slate-400 hover:text-slate-600'" class="flex flex-col items-center space-y-0.5">
                            <i class="fa-solid fa-house text-base"></i>
                            <span class="text-[9px]">Home</span>
                        </button>

                        <button @click="activeTab = 'form'" :class="activeTab === 'form' ? 'text-purple-700 font-extrabold' : 'text-slate-400 hover:text-slate-600'" class="flex flex-col items-center space-y-0.5">
                            <i class="fa-solid fa-pen-to-square text-base"></i>
                            <span class="text-[9px]">Form</span>
                        </button>

                        <button @click="activeTab = 'data'" :class="activeTab === 'data' ? 'text-purple-700 font-extrabold' : 'text-slate-400 hover:text-slate-600'" class="flex flex-col items-center space-y-0.5">
                            <i class="fa-solid fa-table-list text-base"></i>
                            <span class="text-[9px]">Data</span>
                        </button>

                        <button @click="activeTab = 'materi'" :class="activeTab === 'materi' ? 'text-purple-700 font-extrabold' : 'text-slate-400 hover:text-slate-600'" class="flex flex-col items-center space-y-0.5">
                            <i class="fa-solid fa-book-open text-base"></i>
                            <span class="text-[9px]">Materi</span>
                        </button>

                        <button @click="activeTab = 'video'" :class="activeTab === 'video' ? 'text-purple-700 font-extrabold' : 'text-slate-400 hover:text-slate-600'" class="flex flex-col items-center space-y-0.5">
                            <i class="fa-solid fa-circle-play text-base"></i>
                            <span class="text-[9px]">Video</span>
                        </button>

                        <button @click="activeTab = 'about'" :class="activeTab === 'about' ? 'text-purple-700 font-extrabold' : 'text-slate-400 hover:text-slate-600'" class="flex flex-col items-center space-y-0.5">
                            <i class="fa-solid fa-users text-base"></i>
                            <span class="text-[9px]">About</span>
                        </button>
                    </nav>
                </div>

            </div>

        </div>
    </main>

    <!-- MODAL 1: ADMIN LOGIN MODAL -->
    <div x-show="showLoginModal" x-transition.opacity class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 no-print">
        <div class="bg-white rounded-3xl w-full max-w-sm p-6 space-y-4 shadow-2xl relative border border-purple-100">
            <button @click="showLoginModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <div class="text-center space-y-1">
                <div class="w-12 h-12 rounded-2xl bg-purple-700 text-white flex items-center justify-center text-xl mx-auto shadow-md">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Login Admin SA'BAWA</h3>
                <p class="text-xs text-slate-500">Masuk untuk kelola & edit data hasil tes</p>
            </div>

            <form @submit.prevent="loginAdmin()" class="space-y-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Username Admin</label>
                    <input type="text" x-model="loginForm.username" required placeholder="admin"
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-purple-600">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Password</label>
                    <input type="password" x-model="loginForm.password" required placeholder="••••••••"
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:border-purple-600">
                </div>

                <template x-if="loginError">
                    <p class="text-rose-600 text-xs font-bold text-center" x-text="loginError"></p>
                </template>

                <button type="submit" class="w-full py-2.5 bg-purple-700 hover:bg-purple-800 text-white rounded-xl text-xs font-bold shadow-md">
                    Masuk Admin
                </button>
                <p class="text-[10px] text-slate-400 text-center">Demo: Username <code>admin</code> / Password <code>admin</code></p>
            </form>
        </div>
    </div>

    <!-- MODAL 2: DETAIL CERTIFICATE / LAPORAN MODAL -->
    <div x-show="selectedRecord !== null" x-transition.opacity class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl w-full max-w-lg p-6 space-y-4 shadow-2xl relative border border-slate-200 max-h-[90vh] overflow-y-auto">
            <button @click="selectedRecord = null" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 no-print">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- CERTIFICATE HEADER -->
            <div class="text-center border-b border-slate-100 pb-3 space-y-1">
                <img src="/images/logo1.png" alt="Logo" class="h-10 mx-auto object-contain mb-1" onError="this.onerror=null; this.src='/images/logo1.png';">
                <h3 class="text-sm font-black text-slate-900">KARTU HASIL TES BULUTANGKIS</h3>
                <p class="text-[11px] text-purple-700 font-semibold">SA'BAWA (Silvi Aryanti' Badminton Assessment WebApp)</p>
            </div>

            <!-- STUDENT IDENTITIES -->
            <template x-if="selectedRecord">
                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-2 text-xs bg-slate-50 p-3 rounded-xl border border-slate-200">
                        <div><span class="text-slate-500">Nama:</span> <strong class="text-slate-900" x-text="selectedRecord.nama"></strong></div>
                        <div><span class="text-slate-500">NIM:</span> <strong class="text-slate-900" x-text="selectedRecord.nim"></strong></div>
                        <div><span class="text-slate-500">Kelas:</span> <span class="text-slate-700" x-text="selectedRecord.kelas"></span></div>
                        <div><span class="text-slate-500">Tanggal:</span> <span class="text-slate-700" x-text="selectedRecord.tanggal"></span></div>
                    </div>

                    <!-- SCORE BREAKDOWN TABLE -->
                    <table class="w-full text-xs text-left border-collapse border border-slate-200 rounded-xl overflow-hidden">
                        <thead class="bg-purple-100 text-purple-900 font-bold">
                            <tr>
                                <th class="p-2 border border-slate-200">Jenis Tes</th>
                                <th class="p-2 border border-slate-200 text-center">Skor (20x)</th>
                                <th class="p-2 border border-slate-200 text-center">Kategori Norma</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-800">
                            <tr>
                                <td class="p-2 border border-slate-200">1. Servis Pendek</td>
                                <td class="p-2 border border-slate-200 text-center font-bold" x-text="selectedRecord.skorServisPendek"></td>
                                <td class="p-2 border border-slate-200 text-center font-bold" x-text="selectedRecord.normaServisPendek"></td>
                            </tr>
                            <tr>
                                <td class="p-2 border border-slate-200">2. Servis Panjang</td>
                                <td class="p-2 border border-slate-200 text-center font-bold" x-text="selectedRecord.skorServisPanjang"></td>
                                <td class="p-2 border border-slate-200 text-center font-bold" x-text="selectedRecord.normaServisPanjang"></td>
                            </tr>
                            <tr>
                                <td class="p-2 border border-slate-200">3. Pukulan Lob</td>
                                <td class="p-2 border border-slate-200 text-center font-bold" x-text="selectedRecord.skorLob"></td>
                                <td class="p-2 border border-slate-200 text-center font-bold" x-text="selectedRecord.normaLob"></td>
                            </tr>
                            <tr>
                                <td class="p-2 border border-slate-200">4. Pukulan Smash</td>
                                <td class="p-2 border border-slate-200 text-center font-bold" x-text="selectedRecord.skorSmash"></td>
                                <td class="p-2 border border-slate-200 text-center font-bold" x-text="selectedRecord.normaSmash"></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="bank-card-bright p-4 rounded-2xl text-white flex justify-between items-center">
                        <div>
                            <p class="text-[9px] text-purple-200 font-semibold uppercase">Evaluasi Keseluruhan</p>
                            <h3 class="text-lg font-extrabold" x-text="selectedRecord.evaluasiTotal"></h3>
                        </div>
                        <div class="text-right text-xs">
                            <p class="text-purple-200 text-[10px]">Penguji:</p>
                            <p class="font-bold underline" x-text="selectedRecord.penguji || 'Silvi Aryanti, M.Pd.'"></p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 pt-2 no-print">
                        <button @click="window.print()" class="px-4 py-2 bg-purple-700 text-white rounded-xl text-xs font-bold shadow flex items-center space-x-1">
                            <i class="fa-solid fa-print"></i>
                            <span>Cetak Hasil Tes</span>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- MODAL 3: VIDEO PLAYER MODAL -->
    <div x-show="activeVideo !== null" x-transition.opacity class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 no-print">
        <div class="bg-white rounded-3xl w-full max-w-2xl p-4 space-y-3 relative">
            <div class="flex justify-between items-center">
                <h3 class="font-bold text-sm text-slate-900" x-text="activeVideoTitle"></h3>
                <button @click="activeVideo = null" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <div class="aspect-video w-full rounded-2xl overflow-hidden bg-black">
                <iframe x-bind:src="activeVideo" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT APP LOGIC (ALPINE.JS CONTROLLER) -->
    <script>
        function sabawaApp() {
            return {
                activeTab: 'home',
                activeMateriTab: 'overview',
                isAdmin: localStorage.getItem('sabawa_admin') === 'true',
                showLoginModal: false,
                currentTime: '',
                loginForm: { username: 'admin', password: '' },
                loginError: '',

                searchQuery: '',
                filterCategory: '',
                selectedRecord: null,
                activeVideo: null,
                activeVideoTitle: '',
                editingIndex: null,

                form: {
                    nama: '',
                    nim: '',
                    gender: 'L',
                    kelas: 'Palembang A 2024',
                    tanggal: new Date().toISOString().split('T')[0],
                    penguji: 'Silvi Aryanti, M.Pd.',
                    skorServisPendek: null,
                    skorServisPanjang: null,
                    skorLob: null,
                    skorSmash: null
                },

                records: [],

                faqs: [
                    { q: "Apa itu aplikasi SA'BAWA?", a: "SA'BAWA (Silvi Aryanti' Badminton Assessment WebApp) adalah aplikasi web yang dikembangkan oleh tim Silvi Aryanti, M.Pd. untuk mengukur dan mengonversi hasil tes 4 teknik dasar bulutangkis secara otomatis berdasarkan standar norma ilmiah.", open: false },
                    { q: "Siapa saja tim peneliti pengembang instrumen ini?", a: "Ketua: Silvi Aryanti, M.Pd. (NIDN 0021079101), Anggota: 1. Destriana, M.Pd., 2. Fitri Agung Nanda, M.Pd., 3. Soleh Solahuddin, M.Pd.", open: false },
                    { q: "Berapa kali kesempatan servis/pukulan yang diberikan?", a: "Setiap teste mendapatkan 20 kali kesempatan percobaan untuk masing-masing tes (Servis Pendek, Servis Panjang, Lob, dan Smash).", open: false },
                    { q: "Bagaimana cara penilaian Servis Pendek & Panjang?", a: "Shuttlecock diarahkan ke zona sasaran bernilai 5, 4, 3, 2, dan 1. Skor dikonversi ke norma nilai otomatis.", open: false },
                    { q: "Bagaimana cara login Admin?", a: "Gunakan username: 'admin' dan password: 'admin' untuk mengelola dan menghapus data tes.", open: false }
                ],

                init() {
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);
                    this.loadRecords();
                },

                updateTime() {
                    const now = new Date();
                    this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                },

                loadRecords() {
                    const stored = localStorage.getItem('sabawa_records');
                    if (stored) {
                        this.records = JSON.parse(stored);
                    } else {
                        this.seedSampleData();
                    }
                },

                saveRecordsToStorage() {
                    localStorage.setItem('sabawa_records', JSON.stringify(this.records));
                },

                seedSampleData() {
                    this.records = [
                        {
                            id: 1,
                            nama: 'Ahmad Rizky Pratama',
                            nim: '06121001001',
                            jenisKelamin: 'L',
                            kelas: 'Palembang A 2024',
                            tanggal: '2026-07-20',
                            penguji: 'Silvi Aryanti, M.Pd.',
                            skorServisPendek: 85,
                            normaServisPendek: 'Sangat Tinggi',
                            skorServisPanjang: 62,
                            normaServisPanjang: 'Sangat Tinggi',
                            skorLob: 92,
                            normaLob: 'Sangat Tinggi',
                            skorSmash: 35,
                            normaSmash: 'Sangat Tinggi',
                            evaluasiTotal: 'Sangat Tinggi'
                        },
                        {
                            id: 2,
                            nama: 'Siti Nurhaliza',
                            nim: '06121001015',
                            jenisKelamin: 'P',
                            kelas: 'Indralaya B 2024',
                            tanggal: '2026-07-21',
                            penguji: 'Silvi Aryanti, M.Pd.',
                            skorServisPendek: 72,
                            normaServisPendek: 'Tinggi',
                            skorServisPanjang: 50,
                            normaServisPanjang: 'Tinggi',
                            skorLob: 83,
                            normaLob: 'Tinggi',
                            skorSmash: 28,
                            normaSmash: 'Tinggi',
                            evaluasiTotal: 'Tinggi'
                        },
                        {
                            id: 3,
                            nama: 'Budi Santoso',
                            nim: '06121001024',
                            jenisKelamin: 'L',
                            kelas: 'Palembang A 2024',
                            tanggal: '2026-07-22',
                            penguji: 'Destriana, M.Pd.',
                            skorServisPendek: 58,
                            normaServisPendek: 'Sedang',
                            skorServisPanjang: 40,
                            normaServisPanjang: 'Sedang',
                            skorLob: 75,
                            normaLob: 'Sedang',
                            skorSmash: 20,
                            normaSmash: 'Sedang',
                            evaluasiTotal: 'Sedang'
                        }
                    ];
                    this.saveRecordsToStorage();
                },

                loginAdmin() {
                    if (this.loginForm.username === 'admin' && (this.loginForm.password === 'admin' || this.loginForm.password === 'sabawa2026')) {
                        this.isAdmin = true;
                        localStorage.setItem('sabawa_admin', 'true');
                        this.showLoginModal = false;
                        this.loginError = '';
                        alert('Berhasil login sebagai Admin SA\'BAWA!');
                    } else {
                        this.loginError = 'Username atau Password salah!';
                    }
                },

                logoutAdmin() {
                    this.isAdmin = false;
                    localStorage.removeItem('sabawa_admin');
                    alert('Anda telah keluar dari Mode Admin.');
                },

                calculateNormaServisPendek(score) {
                    if (score === null || score === undefined || score === '') return '-';
                    if (score > 82.2) return 'Sangat Tinggi';
                    if (score >= 67) return 'Tinggi';
                    if (score >= 51) return 'Sedang';
                    if (score >= 36) return 'Kurang';
                    return 'Sangat Kurang';
                },

                calculateNormaServisPanjang(score) {
                    if (score === null || score === undefined || score === '') return '-';
                    if (score > 60) return 'Sangat Tinggi';
                    if (score >= 47) return 'Tinggi';
                    if (score >= 34) return 'Sedang';
                    if (score >= 21) return 'Kurang';
                    return 'Sangat Kurang';
                },

                calculateNormaLob(score) {
                    if (score === null || score === undefined || score === '') return '-';
                    if (score > 91) return 'Sangat Tinggi';
                    if (score >= 80) return 'Tinggi';
                    if (score >= 70) return 'Sedang';
                    if (score >= 59) return 'Kurang';
                    return 'Sangat Kurang';
                },

                calculateNormaSmash(score) {
                    if (score === null || score === undefined || score === '') return '-';
                    if (score > 33) return 'Sangat Tinggi';
                    if (score >= 25) return 'Tinggi';
                    if (score >= 17) return 'Sedang';
                    if (score >= 8) return 'Kurang';
                    return 'Sangat Kurang';
                },

                calculateOverallCategory(sp, sj, lob, smash) {
                    const nSp = this.calculateNormaServisPendek(sp);
                    const nSj = this.calculateNormaServisPanjang(sj);
                    const nLob = this.calculateNormaLob(lob);
                    const nSmash = this.calculateNormaSmash(smash);

                    const mapNorma = { 'Sangat Tinggi': 5, 'Tinggi': 4, 'Sedang': 3, 'Kurang': 2, 'Sangat Kurang': 1 };
                    const scores = [mapNorma[nSp] || 0, mapNorma[nSj] || 0, mapNorma[nLob] || 0, mapNorma[nSmash] || 0].filter(s => s > 0);
                    
                    if (scores.length === 0) return '-';
                    const avg = scores.reduce((a, b) => a + b, 0) / scores.length;

                    if (avg >= 4.5) return 'Sangat Tinggi';
                    if (avg >= 3.5) return 'Tinggi';
                    if (avg >= 2.5) return 'Sedang';
                    if (avg >= 1.5) return 'Kurang';
                    return 'Sangat Kurang';
                },

                saveRecord() {
                    const normaSp = this.calculateNormaServisPendek(this.form.skorServisPendek);
                    const normaSj = this.calculateNormaServisPanjang(this.form.skorServisPanjang);
                    const normaLob = this.calculateNormaLob(this.form.skorLob);
                    const normaSmash = this.calculateNormaSmash(this.form.skorSmash);
                    const evalTotal = this.calculateOverallCategory(this.form.skorServisPendek, this.form.skorServisPanjang, this.form.skorLob, this.form.skorSmash);

                    const recordData = {
                        id: this.editingIndex !== null ? this.records[this.editingIndex].id : Date.now(),
                        nama: this.form.nama,
                        nim: this.form.nim,
                        jenisKelamin: this.form.gender,
                        kelas: this.form.kelas,
                        tanggal: this.form.tanggal,
                        penguji: this.form.penguji,
                        skorServisPendek: this.form.skorServisPendek,
                        normaServisPendek: normaSp,
                        skorServisPanjang: this.form.skorServisPanjang,
                        normaServisPanjang: normaSj,
                        skorLob: this.form.skorLob,
                        normaLob: normaLob,
                        skorSmash: this.form.skorSmash,
                        normaSmash: normaSmash,
                        evaluasiTotal: evalTotal
                    };

                    if (this.editingIndex !== null) {
                        this.records[this.editingIndex] = recordData;
                        this.editingIndex = null;
                    } else {
                        this.records.unshift(recordData);
                    }

                    this.saveRecordsToStorage();
                    alert('Data penilaian berhasil disimpan!');

                    this.form.nama = '';
                    this.form.nim = '';
                    this.form.skorServisPendek = null;
                    this.form.skorServisPanjang = null;
                    this.form.skorLob = null;
                    this.form.skorSmash = null;

                    this.activeTab = 'data';
                },

                deleteRecord(id) {
                    if (confirm('Apakah Anda yakin ingin menghapus data tes ini?')) {
                        this.records = this.records.filter(r => r.id !== id);
                        this.saveRecordsToStorage();
                    }
                },

                get filteredRecords() {
                    return this.records.filter(r => {
                        const matchQuery = !this.searchQuery || r.nama.toLowerCase().includes(this.searchQuery.toLowerCase()) || r.nim.includes(this.searchQuery);
                        const matchCat = !this.filterCategory || r.evaluasiTotal === this.filterCategory;
                        return matchQuery && matchCat;
                    });
                },

                getAverageScore() {
                    if (this.records.length === 0) return '0';
                    const sum = this.records.reduce((acc, r) => acc + (r.skorServisPendek || 0) + (r.skorServisPanjang || 0) + (r.skorLob || 0) + (r.skorSmash || 0), 0);
                    return (sum / (this.records.length * 4)).toFixed(1);
                },

                getCategoryCount(cat) {
                    return this.records.filter(r => r.evaluasiTotal === cat).length;
                },

                getCategoryBadgeClass(category) {
                    switch (category) {
                        case 'Sangat Tinggi': return 'bg-emerald-100 text-emerald-800 border border-emerald-300';
                        case 'Tinggi': return 'bg-green-100 text-green-800 border border-green-300';
                        case 'Sedang': return 'bg-amber-100 text-amber-800 border border-amber-300';
                        case 'Kurang': return 'bg-orange-100 text-orange-800 border border-orange-300';
                        case 'Sangat Kurang': return 'bg-rose-100 text-rose-800 border border-rose-300';
                        default: return 'bg-slate-100 text-slate-700';
                    }
                },

                openDetailModal(item) {
                    this.selectedRecord = item;
                },

                openVideoModal(url, title) {
                    this.activeVideo = url;
                    this.activeVideoTitle = title;
                },

                exportToCSV() {
                    if (this.records.length === 0) {
                        alert('Tidak ada data untuk diekspor.');
                        return;
                    }
                    let csv = 'Nama,NIM,Jenis Kelamin,Kelas,Tanggal,Servis Pendek,Norma SP,Servis Panjang,Norma SJ,Lob,Norma Lob,Smash,Norma Smash,Evaluasi Total\n';
                    this.records.forEach(r => {
                        csv += `"${r.nama}","${r.nim}","${r.jenisKelamin}","${r.kelas}","${r.tanggal}",${r.skorServisPendek || 0},"${r.normaServisPendek}",${r.skorServisPanjang || 0},"${r.normaServisPanjang}",${r.skorLob || 0},"${r.normaLob}",${r.skorSmash || 0},"${r.normaSmash}","${r.evaluasiTotal}"\n`;
                    });
                    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.setAttribute('download', 'SA_BAWA_Rekap_Penilaian.csv');
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },

                printAllReport() {
                    window.print();
                }
            };
        }
    </script>
</body>
</html>
<?php /**PATH C:\Users\RYAN\Herd\sa-bawa\resources\views/welcome.blade.php ENDPATH**/ ?>