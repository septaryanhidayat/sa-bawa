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
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
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
    @endif

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gradient-to-br from-slate-50 via-purple-50/40 to-indigo-50/30 text-slate-800 font-sans min-h-screen selection:bg-purple-600 selection:text-white" x-data="sabawaApp()">

    <!-- TOP HEADER / CONTROL BAR (DESKTOP ONLY) -->
    <header class="hidden lg:block sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-purple-100/80 shadow-sm no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Brand Logo & Title -->
            <div class="flex items-center space-x-3 cursor-pointer" @click="activeTab = 'home'">
                <img :src="appSettings.appLogo" :alt="appSettings.appName" class="h-10 w-auto object-contain" onError="this.onerror=null; this.src='/images/logo1.png';">
                <div class="hidden sm:block">
                    <h1 class="font-extrabold text-lg text-purple-950 tracking-tight leading-none" x-text="appSettings.appName"></h1>
                    <p class="text-[10px] text-purple-700 font-semibold" x-text="appSettings.appSubtitle"></p>
                </div>
            </div>

            <!-- Admin Badge & Controls -->
            <div class="flex items-center space-x-3">
                <template x-if="isAdmin">
                    <div class="flex items-center space-x-2">
                        <button @click="activeTab = 'admin'" class="flex items-center space-x-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-3.5 py-1.5 rounded-full text-xs font-bold shadow-md hover:from-emerald-700 hover:to-teal-700 transition-all">
                            <span class="w-2 h-2 rounded-full bg-emerald-300 animate-ping"></span>
                            <span><i class="fa-solid fa-user-shield mr-1"></i> Dashboard Admin</span>
                        </button>
                        <button @click="logoutAdmin()" class="bg-rose-100 hover:bg-rose-200 text-rose-700 px-3 py-1.5 rounded-full text-xs font-bold transition-all" title="Logout Mode Admin">
                            <i class="fa-solid fa-right-from-bracket mr-1"></i> Logout
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
    <main class="max-w-7xl mx-auto p-0 lg:px-8 lg:py-6">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-0 lg:gap-8 items-start">
            
            <!-- DESKTOP LEFT SIDEBAR: BANNER WEBPAGE UMUM (Visible on Desktop LG screens) -->
            <div class="hidden lg:block lg:col-span-7 space-y-6">
                
                <!-- HERO BANNER CARD -->
                <div class="bg-white rounded-3xl p-8 border border-purple-100 shadow-xl shadow-purple-900/5 relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-purple-100/60 rounded-full blur-3xl pointer-events-none"></div>
                    
                    <div class="flex items-center space-x-3 mb-4">
                        <span class="bg-purple-100 text-purple-800 font-extrabold text-xs px-3 py-1 rounded-full uppercase tracking-wider">
                            Aplikasi Asesmen Bulutangkis
                        </span>
                        <span class="text-xs text-slate-500 font-medium">FKIP Penjaskes Universitas Sriwijaya</span>
                    </div>

                    <h2 class="text-3xl font-black text-slate-900 tracking-tight leading-tight" x-text="appSettings.heroTitle"></h2>

                    <p class="text-sm text-slate-600 mt-3 leading-relaxed" x-text="appSettings.heroDescription"></p>

                    <!-- RESEARCH TEAM HIGHLIGHT -->
                    <div class="mt-6 pt-6 border-t border-slate-100">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tim Peneliti & Pengembang</h4>
                            <template x-if="isAdmin">
                                <button @click="activeTab = 'admin'; adminSubTab = 'about'" class="text-[11px] text-purple-700 hover:underline font-bold">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit Tim
                                </button>
                            </template>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <template x-for="person in researchers" :key="person.id">
                                <div :class="person.isLeader ? 'bg-purple-50/70 border-purple-100' : 'bg-slate-50 border-slate-200/80'" class="p-3 rounded-2xl border flex items-center space-x-3">
                                    <img :src="person.photo || '/images/logo1.png'" :alt="person.name" class="w-10 h-10 aspect-square object-cover rounded-xl shadow shrink-0" onError="this.onerror=null; this.src='/images/logo1.png';">
                                    <div class="overflow-hidden">
                                        <h5 class="font-extrabold text-xs truncate" :class="person.isLeader ? 'text-purple-950' : 'text-slate-900'" x-text="person.name"></h5>
                                        <p class="text-[10px] truncate" :class="person.isLeader ? 'text-purple-700 font-medium' : 'text-slate-500'" x-text="person.role"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- FEATURE HIGHLIGHTS GRID -->
                    <div class="mt-6 grid grid-cols-4 gap-3 text-center">
                        <div class="p-3 rounded-2xl bg-purple-50/60 border border-purple-100 cursor-pointer" @click="activeTab = 'form'">
                            <i class="fa-solid fa-pen-to-square text-xl text-purple-700 mb-1"></i>
                            <h5 class="text-xs font-bold text-slate-800">Form Asesmen</h5>
                            <p class="text-[10px] text-slate-500">Input 20x Tes</p>
                        </div>

                        <div class="p-3 rounded-2xl bg-blue-50/60 border border-blue-100 cursor-pointer" @click="activeTab = 'data'">
                            <i class="fa-solid fa-calculator text-xl text-blue-700 mb-1"></i>
                            <h5 class="text-xs font-bold text-slate-800">Norma Otomatis</h5>
                            <p class="text-[10px] text-slate-500">5 Skala Kategori</p>
                        </div>

                        <div class="p-3 rounded-2xl bg-emerald-50/60 border border-emerald-100 cursor-pointer" @click="activeTab = 'materi'">
                            <i class="fa-solid fa-book-open text-xl text-emerald-700 mb-1"></i>
                            <h5 class="text-xs font-bold text-slate-800">Materi & Lapangan</h5>
                            <p class="text-[10px] text-slate-500">Pedoman Lengkap</p>
                        </div>

                        <div class="p-3 rounded-2xl bg-rose-50/60 border border-rose-100 cursor-pointer" @click="activeTab = 'video'">
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

            <!-- MOBILE APP FRAME CONTAINER (Sisi Kanan di Desktop LG / Full Width di Mobile & Tablet) -->
            <div class="w-full lg:col-span-5 flex justify-center lg:sticky lg:top-20">
                
                <!-- MOBILE BANK FRAME CONTAINER -->
                <div class="w-full min-h-screen bg-white lg:max-w-md lg:rounded-[36px] lg:border-[8px] lg:border-slate-200 lg:shadow-2xl lg:shadow-purple-900/10 overflow-hidden relative lg:min-h-[760px] flex flex-col">

                    <!-- MOBILE STATUS BAR (DESKTOP ONLY) -->
                    <div class="hidden lg:flex bg-slate-900 text-white px-6 py-2 justify-between items-center text-[11px] font-semibold no-print">
                        <span x-text="currentTime">9:41</span>
                        <div class="flex items-center space-x-1.5">
                            <span class="text-[9px] bg-purple-600 text-white px-1.5 py-0.5 rounded font-mono" x-text="appSettings.appName + ' MOBILE'"></span>
                            <i class="fa-solid fa-signal text-[10px]"></i>
                            <i class="fa-solid fa-wifi text-[10px]"></i>
                            <i class="fa-solid fa-battery-full text-emerald-400 text-[10px]"></i>
                        </div>
                    </div>

                    <!-- APP INNER SCREEN WRAPPER -->
                    <div class="flex-1 overflow-y-auto pb-24 lg:pb-20 no-scrollbar bg-slate-50">

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
                                        <h2 class="text-xl font-extrabold mt-1 tracking-tight" x-text="appSettings.appName + ' Assessment'"></h2>
                                        <p class="text-[11px] text-purple-200 mt-0.5" x-text="appSettings.appSubtitle"></p>
                                    </div>
                                    <img :src="appSettings.appLogo" :alt="appSettings.appName" class="h-10 w-auto object-contain filter drop-shadow" onError="this.onerror=null; this.src='/images/logo1.png';">
                                </div>

                                <!-- BANKING-STYLE QUICK STATS / SALDO SKOR WIDGET -->
                                <div class="mt-4 pt-3 border-t border-purple-400/30 grid grid-cols-3 gap-2 text-center">
                                    <div class="bg-black/20 rounded-xl p-2 backdrop-blur-sm">
                                        <p class="text-[9px] text-purple-200 uppercase font-semibold">Total Test</p>
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

                                    <!-- 7. Dashboard Admin (Pengganti Norma Tes) -->
                                    <button @click="isAdmin ? activeTab = 'admin' : showLoginModal = true" class="flex flex-col items-center justify-center p-2.5 rounded-2xl bg-white border border-purple-200 shadow-sm hover:border-purple-400 transition-all group">
                                        <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md">
                                            <i class="fa-solid fa-user-gear text-base"></i>
                                        </div>
                                        <span class="text-[10px] font-bold text-purple-900 mt-1.5 text-center" x-text="isAdmin ? 'Dashboard' : 'Admin'"></span>
                                    </button>

                                    <!-- 8. Logout (Pengganti Isi Contoh) -->
                                    <button @click="isAdmin ? logoutAdmin() : showLoginModal = true" class="flex flex-col items-center justify-center p-2.5 rounded-2xl bg-white border border-rose-200 shadow-sm hover:border-rose-300 transition-all group">
                                        <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-right-from-bracket text-base"></i>
                                        </div>
                                        <span class="text-[10px] font-bold text-rose-700 mt-1.5 text-center" x-text="isAdmin ? 'Logout' : 'Login'"></span>
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
                                        Oleh <strong x-text="researchers[0] ? researchers[0].name : 'Silvi Aryanti, M.Pd.'"></strong> & Tim. Mengukur 4 teknik dasar secara konversi norma otomatis.
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

                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-700 mb-1">Asal Sekolah *</label>
                                            <select x-model="form.sekolah" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-purple-600">
                                                <option value="">-- Pilih Asal Sekolah --</option>
                                                <template x-for="sch in schools" :key="sch.id">
                                                    <option :value="sch.nama" x-text="sch.nama"></option>
                                                </template>
                                            </select>
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
                                    <button type="submit" class="bg-white text-purple-900 font-extrabold px-4 py-2 rounded-xl text-xs hover:bg-purple-50 shadow-md transition-all">
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
                                    <button @click="exportToCSV()" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[10px] font-bold shadow-sm">CSV</button>
                                    <button @click="printAllReport()" class="px-2.5 py-1 bg-purple-700 hover:bg-purple-800 text-white rounded-lg text-[10px] font-bold shadow-sm">Cetak</button>
                                </div>
                            </div>

                            <!-- SEARCH & FILTERS -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                <div class="relative">
                                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-xs text-slate-400"></i>
                                    <input type="text" x-model="searchQuery" placeholder="Cari nama/NIM..."
                                           class="w-full bg-white border border-slate-300 rounded-xl pl-8 pr-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-purple-600">
                                </div>
                                <div class="relative">
                                    <select x-model="filterSchool" class="w-full bg-white border border-slate-300 rounded-xl pl-3 pr-8 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-purple-600 appearance-none">
                                        <option value="">Semua Sekolah</option>
                                        <template x-for="sch in schools" :key="sch.id">
                                            <option :value="sch.nama" x-text="sch.nama"></option>
                                        </template>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                        <i class="fa-solid fa-chevron-down text-[10px]"></i>
                                    </div>
                                </div>
                                <div class="relative">
                                    <select x-model="filterCategory" class="w-full bg-white border border-slate-300 rounded-xl pl-3 pr-8 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-purple-600 appearance-none">
                                        <option value="">Semua Kategori</option>
                                        <option value="Sangat Tinggi">Sangat Tinggi</option>
                                        <option value="Tinggi">Tinggi</option>
                                        <option value="Sedang">Sedang</option>
                                        <option value="Kurang">Kurang</option>
                                        <option value="Sangat Kurang">Sangat Kurang</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                        <i class="fa-solid fa-chevron-down text-[10px]"></i>
                                    </div>
                                </div>
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
                                                <p class="text-[10px] text-slate-500">NIM: <span x-text="item.nim"></span> • <span x-text="item.kelas"></span> <template x-if="item.sekolah"><span>• <span class="text-purple-700 font-semibold" x-text="item.sekolah"></span></span></template></p>
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
                                                <button @click="openDetailModal(item)" class="px-2 py-0.5 bg-purple-100 text-purple-800 font-bold rounded-lg hover:bg-purple-200">Rincian</button>
                                                <template x-if="isAdmin">
                                                    <div class="flex space-x-1">
                                                        <button @click="editRecord(item)" class="px-2 py-0.5 bg-blue-100 text-blue-700 font-bold rounded-lg hover:bg-blue-200">Edit</button>
                                                        <button @click="deleteRecord(item.id)" class="px-2 py-0.5 bg-rose-100 text-rose-700 font-bold rounded-lg hover:bg-rose-200">Hapus</button>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- 4. MATERI TAB (CARD VIEW STACKED KE BAWAH WITH PHOTO & TEXT) -->
                        <div x-show="activeTab === 'materi'" x-transition:enter="transition ease-out duration-200" class="p-4 space-y-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-base font-extrabold text-purple-950">Materi & Pedoman Tes</h2>
                                    <p class="text-[11px] text-slate-500">Panduan lengkap pelaksanaan tes bulutangkis</p>
                                </div>
                                <template x-if="isAdmin">
                                    <button @click="openAddMateriModal()" class="px-2.5 py-1.5 bg-purple-700 text-white rounded-xl text-xs font-bold shadow-md hover:bg-purple-800 flex items-center space-x-1">
                                        <i class="fa-solid fa-plus"></i>
                                        <span>Tambah</span>
                                    </button>
                                </template>
                            </div>

                            <!-- CARDS STACKED DOWNWARD -->
                            <div class="space-y-4">
                                <template x-for="item in materis" :key="item.id">
                                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md overflow-hidden space-y-3 transition-all hover:shadow-lg">
                                        <!-- Photo Header -->
                                        <div class="relative h-44 w-full bg-slate-900 overflow-hidden">
                                            <img :src="item.photo || '/images/logo1.png'" :alt="item.judul" class="w-full h-full object-cover opacity-90 hover:scale-105 transition-transform duration-300" onError="this.onerror=null; this.src='/images/logo1.png';">
                                            <span class="absolute top-3 left-3 bg-purple-900/80 backdrop-blur-md text-white text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider shadow" x-text="item.kategori"></span>
                                            
                                            <template x-if="isAdmin">
                                                <div class="absolute top-3 right-3 flex space-x-1 bg-white/90 backdrop-blur-md p-1 rounded-xl shadow">
                                                    <button @click="openEditMateriModal(item)" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg text-xs" title="Edit Materi">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                    <button @click="deleteMateri(item.id)" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg text-xs" title="Hapus Materi">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Card Text Details -->
                                        <div class="p-4 pt-1 space-y-2">
                                            <h3 class="font-extrabold text-sm text-slate-900 leading-snug" x-text="item.judul"></h3>
                                            <p class="text-xs text-slate-600 leading-relaxed" x-text="item.deskripsi"></p>
                                            
                                            <template x-if="item.petunjuk">
                                                <div class="mt-3 bg-purple-50/80 p-3 rounded-xl border border-purple-100 text-xs text-purple-950 flex items-start space-x-2">
                                                    <i class="fa-solid fa-list-check text-purple-700 mt-0.5 shrink-0"></i>
                                                    <div>
                                                        <span class="font-bold text-purple-900 block text-[11px] mb-0.5">Petunjuk Tes:</span>
                                                        <p class="text-[11px] text-slate-700 leading-normal" x-text="item.petunjuk"></p>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- 5. VIDEO TAB -->
                        <div x-show="activeTab === 'video'" x-transition:enter="transition ease-out duration-200" class="p-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-base font-extrabold text-purple-950">Video Tutorial</h2>
                                    <p class="text-[11px] text-slate-500">Panduan peragaan gerakan teknik</p>
                                </div>
                                <template x-if="isAdmin">
                                    <button @click="openAddVideoModal()" class="px-2.5 py-1.5 bg-purple-700 text-white rounded-xl text-xs font-bold shadow-md hover:bg-purple-800 flex items-center space-x-1">
                                        <i class="fa-solid fa-plus"></i>
                                        <span>Tambah</span>
                                    </button>
                                </template>
                            </div>

                            <div class="space-y-3">
                                <template x-for="v in videos" :key="v.id">
                                    <div class="bg-white p-3 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between space-x-3">
                                        <div class="flex items-center space-x-3 cursor-pointer flex-1" @click="openVideoModal(v.url, v.judul)">
                                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-purple-700 font-bold shrink-0 shadow-sm">
                                                <i class="fa-solid fa-play text-lg"></i>
                                            </div>
                                            <div>
                                                <span class="text-[9px] bg-purple-50 text-purple-700 font-bold px-2 py-0.5 rounded-full uppercase" x-text="v.kategori"></span>
                                                <h4 class="font-bold text-xs text-slate-900 mt-0.5" x-text="v.judul"></h4>
                                                <p class="text-[10px] text-slate-500 line-clamp-1" x-text="v.deskripsi"></p>
                                            </div>
                                        </div>

                                        <template x-if="isAdmin">
                                            <div class="flex space-x-1 shrink-0">
                                                <button @click="openEditVideoModal(v)" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg text-xs" title="Edit Video">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <button @click="deleteVideo(v.id)" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg text-xs" title="Hapus Video">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- 6. FAQ TAB -->
                        <div x-show="activeTab === 'faq'" x-transition:enter="transition ease-out duration-200" class="p-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-base font-extrabold text-purple-950">FAQ & Bantuan</h2>
                                    <p class="text-[11px] text-slate-500">Pertanyaan umum seputar penggunaan</p>
                                </div>
                                <template x-if="isAdmin">
                                    <button @click="openAddFaqModal()" class="px-2.5 py-1.5 bg-purple-700 text-white rounded-xl text-xs font-bold shadow-md hover:bg-purple-800 flex items-center space-x-1">
                                        <i class="fa-solid fa-plus"></i>
                                        <span>Tambah</span>
                                    </button>
                                </template>
                            </div>

                            <div class="space-y-2.5">
                                <template x-for="faq in faqs" :key="faq.id">
                                    <div class="bg-white p-3.5 rounded-2xl border border-slate-200 text-xs space-y-1.5 shadow-sm">
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-bold text-purple-950 text-xs pr-2" x-text="faq.q"></h4>
                                            <template x-if="isAdmin">
                                                <div class="flex space-x-1 shrink-0">
                                                    <button @click="openEditFaqModal(faq)" class="p-1 text-blue-600 hover:bg-blue-50 rounded text-xs">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                    <button @click="deleteFaq(faq.id)" class="p-1 text-rose-600 hover:bg-rose-50 rounded text-xs">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                        <p class="text-[11px] text-slate-600 leading-relaxed" x-text="faq.a"></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- 7. ABOUT TAB -->
                        <div x-show="activeTab === 'about'" x-transition:enter="transition ease-out duration-200" class="p-4 space-y-4 text-center">
                            <div class="bg-white p-5 rounded-3xl border border-purple-100 shadow-sm space-y-3">
                                <img :src="appSettings.appLogo" :alt="appSettings.appName" class="h-20 mx-auto object-contain" onError="this.onerror=null; this.src='/images/logo1.png';">
                                <div>
                                    <h2 class="text-xl font-black text-purple-950" x-text="appSettings.appName"></h2>
                                    <p class="text-xs text-purple-700 font-semibold mt-0.5" x-text="appSettings.appSubtitle"></p>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed pt-2 border-t border-slate-100" x-text="appSettings.heroDescription"></p>
                            </div>

                            <div class="bg-white p-4 rounded-3xl border border-slate-200 text-left space-y-3 shadow-sm">
                                <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                                    <h3 class="font-extrabold text-xs text-slate-900 uppercase tracking-wider">Tim Peneliti & Pengembang</h3>
                                    <template x-if="isAdmin">
                                        <button @click="openAddResearcherModal()" class="text-[10px] bg-purple-700 text-white font-bold px-2.5 py-1 rounded-lg">
                                            + Peneliti
                                        </button>
                                    </template>
                                </div>

                                <div class="space-y-2.5">
                                    <template x-for="person in researchers" :key="person.id">
                                        <div class="flex items-center justify-between p-2.5 rounded-2xl bg-slate-50 border border-slate-200/80">
                                            <div class="flex items-center space-x-3">
                                                <img :src="person.photo || '/images/logo1.png'" :alt="person.name" class="w-10 h-10 aspect-square object-cover rounded-xl shadow shrink-0" onError="this.onerror=null; this.src='/images/logo1.png';">
                                                <div>
                                                    <h4 class="font-extrabold text-xs text-purple-950" x-text="person.name"></h4>
                                                    <p class="text-[10px] text-slate-500" x-text="person.role"></p>
                                                </div>
                                            </div>
                                            <template x-if="isAdmin">
                                                <div class="flex space-x-1 shrink-0">
                                                    <button @click="openEditResearcherModal(person)" class="p-1 text-blue-600 hover:bg-blue-100 rounded">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                    <button @click="deleteResearcher(person.id)" class="p-1 text-rose-600 hover:bg-rose-100 rounded">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- 8. DASHBOARD KHUSUS ADMIN TAB -->
                        <div x-show="activeTab === 'admin'" x-transition:enter="transition ease-out duration-200" class="p-4 space-y-4">
                            
                            <!-- ADMIN PANEL HEADER -->
                            <div class="bg-gradient-to-r from-purple-900 via-indigo-900 to-slate-900 p-5 rounded-3xl text-white shadow-xl space-y-2">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="bg-emerald-500/20 border border-emerald-400/40 text-emerald-300 text-[10px] font-bold px-2.5 py-0.5 rounded-full">
                                            <i class="fa-solid fa-user-shield mr-1"></i> Control Panel Admin
                                        </span>
                                        <h2 class="text-xl font-extrabold mt-1.5 tracking-tight">Dashboard Admin</h2>
                                        <p class="text-[11px] text-purple-200">Kelola semua fitur & konten aplikasi dalam satu tempat</p>
                                    </div>
                                    <button @click="logoutAdmin()" class="bg-rose-500/30 hover:bg-rose-500/50 text-rose-200 border border-rose-400/30 px-3 py-1.5 rounded-xl text-xs font-bold transition-all">
                                        <i class="fa-solid fa-right-from-bracket mr-1"></i> Logout
                                    </button>
                                </div>
                            </div>

                            <!-- SUB-TAB NAV PILLS ADMIN -->
                            <div class="flex space-x-1.5 overflow-x-auto pb-1 no-scrollbar text-xs">
                                <button @click="adminSubTab = 'form'" :class="adminSubTab === 'form' ? 'bg-purple-700 text-white font-bold shadow' : 'bg-white text-slate-700 border border-slate-200'" class="px-3 py-1.5 rounded-xl shrink-0">
                                    <i class="fa-solid fa-list-check mr-1"></i> Form & Data
                                </button>
                                <button @click="adminSubTab = 'sekolah'" :class="adminSubTab === 'sekolah' ? 'bg-purple-700 text-white font-bold shadow' : 'bg-white text-slate-700 border border-slate-200'" class="px-3 py-1.5 rounded-xl shrink-0">
                                    <i class="fa-solid fa-school mr-1"></i> Sekolah
                                </button>
                                <button @click="adminSubTab = 'materi'" :class="adminSubTab === 'materi' ? 'bg-purple-700 text-white font-bold shadow' : 'bg-white text-slate-700 border border-slate-200'" class="px-3 py-1.5 rounded-xl shrink-0">
                                    <i class="fa-solid fa-book-open mr-1"></i> Materi
                                </button>
                                <button @click="adminSubTab = 'video'" :class="adminSubTab === 'video' ? 'bg-purple-700 text-white font-bold shadow' : 'bg-white text-slate-700 border border-slate-200'" class="px-3 py-1.5 rounded-xl shrink-0">
                                    <i class="fa-solid fa-circle-play mr-1"></i> Video
                                </button>
                                <button @click="adminSubTab = 'faq'" :class="adminSubTab === 'faq' ? 'bg-purple-700 text-white font-bold shadow' : 'bg-white text-slate-700 border border-slate-200'" class="px-3 py-1.5 rounded-xl shrink-0">
                                    <i class="fa-solid fa-circle-question mr-1"></i> FAQ
                                </button>
                                <button @click="adminSubTab = 'about'" :class="adminSubTab === 'about' ? 'bg-purple-700 text-white font-bold shadow' : 'bg-white text-slate-700 border border-slate-200'" class="px-3 py-1.5 rounded-xl shrink-0">
                                    <i class="fa-solid fa-users mr-1"></i> Tim Peneliti
                                </button>
                                <button @click="adminSubTab = 'settings'" :class="adminSubTab === 'settings' ? 'bg-purple-700 text-white font-bold shadow' : 'bg-white text-slate-700 border border-slate-200'" class="px-3 py-1.5 rounded-xl shrink-0">
                                    <i class="fa-solid fa-gear mr-1"></i> Icon & Nama App
                                </button>
                            </div>

                            <!-- SUB-SECTION 1: KELOLA FORM & REKAP DATA TES -->
                            <div x-show="adminSubTab === 'form'" class="space-y-3">
                                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                                    <div class="flex justify-between items-center">
                                        <h3 class="font-extrabold text-xs text-purple-950 uppercase tracking-wider">Kelola Rekap Penilaian</h3>
                                        <div class="flex space-x-1.5">
                                            <button @click="activeTab = 'form'" class="px-2.5 py-1 bg-purple-700 text-white rounded-xl text-xs font-bold shadow-sm">
                                                + Tambah Data
                                            </button>
                                            <button @click="seedSampleData()" class="px-2.5 py-1 bg-amber-600 text-white rounded-xl text-xs font-bold shadow-sm" title="Muat Contoh Data Tes">
                                                Reset Demo Data
                                            </button>
                                        </div>
                                    </div>

                                    <div class="space-y-2 max-h-96 overflow-y-auto pr-1">
                                        <template x-for="item in records" :key="item.id">
                                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 flex justify-between items-center text-xs">
                                                <div>
                                                    <h4 class="font-bold text-slate-900" x-text="item.nama"></h4>
                                                    <p class="text-[10px] text-slate-500">NIM: <span x-text="item.nim"></span> • Total: <span class="font-bold text-purple-700" x-text="item.evaluasiTotal"></span></p>
                                                </div>
                                                <div class="flex space-x-1">
                                                    <button @click="editRecord(item)" class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-[10px] font-bold">Edit</button>
                                                    <button @click="deleteRecord(item.id)" class="px-2 py-1 bg-rose-100 text-rose-700 rounded-lg text-[10px] font-bold">Hapus</button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- SUB-SECTION: KELOLA SEKOLAH -->
                            <div x-show="adminSubTab === 'sekolah'" class="space-y-3">
                                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                                    <div class="flex justify-between items-center">
                                        <h3 class="font-extrabold text-xs text-purple-950 uppercase tracking-wider">Kelola Daftar Sekolah</h3>
                                        <button @click="schoolForm.id = null; schoolForm.nama = ''; showSchoolModal = true;" class="px-2.5 py-1.5 bg-purple-700 hover:bg-purple-800 text-white rounded-xl text-xs font-bold shadow-sm flex items-center space-x-1">
                                            <i class="fa-solid fa-plus"></i>
                                            <span>Tambah Sekolah</span>
                                        </button>
                                    </div>

                                    <div class="space-y-2">
                                        <template x-if="schools.length === 0">
                                            <div class="text-center py-6 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                                                <p class="text-xs text-slate-500">Belum ada data sekolah.</p>
                                            </div>
                                        </template>
                                        
                                        <template x-for="sch in schools" :key="sch.id">
                                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 flex justify-between items-center text-xs">
                                                <div class="flex items-center space-x-2">
                                                    <i class="fa-solid fa-school text-purple-600"></i>
                                                    <span class="font-bold text-slate-800" x-text="sch.nama"></span>
                                                </div>
                                                <div class="flex space-x-1 shrink-0">
                                                    <button @click="editSchool(sch)" class="px-2.5 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg text-[10px] font-bold transition-all">Edit</button>
                                                    <button @click="deleteSchool(sch.id)" class="px-2.5 py-1 bg-rose-100 hover:bg-rose-200 text-rose-700 rounded-lg text-[10px] font-bold transition-all">Hapus</button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- SUB-SECTION 2: KELOLA MATERI -->
                            <div x-show="adminSubTab === 'materi'" class="space-y-3">
                                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                                    <div class="flex justify-between items-center">
                                        <h3 class="font-extrabold text-xs text-purple-950 uppercase tracking-wider">Kelola Cards Materi Asesmen</h3>
                                        <button @click="openAddMateriModal()" class="px-2.5 py-1 bg-purple-700 text-white rounded-xl text-xs font-bold shadow-sm">
                                            + Tambah Materi
                                        </button>
                                    </div>

                                    <div class="space-y-2">
                                        <template x-for="m in materis" :key="m.id">
                                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 flex items-center justify-between space-x-3 text-xs">
                                                <div class="flex items-center space-x-3 overflow-hidden">
                                                    <img :src="m.photo || '/images/logo1.png'" class="w-12 h-12 object-cover rounded-lg shrink-0 border" onError="this.onerror=null; this.src='/images/logo1.png';">
                                                    <div class="overflow-hidden">
                                                        <span class="text-[9px] bg-purple-100 text-purple-800 font-bold px-2 py-0.5 rounded-full" x-text="m.kategori"></span>
                                                        <h4 class="font-bold text-slate-900 truncate mt-0.5" x-text="m.judul"></h4>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-1 shrink-0">
                                                    <button @click="openEditMateriModal(m)" class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-[10px] font-bold">Edit</button>
                                                    <button @click="deleteMateri(m.id)" class="px-2 py-1 bg-rose-100 text-rose-700 rounded-lg text-[10px] font-bold">Hapus</button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- SUB-SECTION 3: KELOLA VIDEO -->
                            <div x-show="adminSubTab === 'video'" class="space-y-3">
                                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                                    <div class="flex justify-between items-center">
                                        <h3 class="font-extrabold text-xs text-purple-950 uppercase tracking-wider">Kelola Video Tutorial</h3>
                                        <button @click="openAddVideoModal()" class="px-2.5 py-1 bg-purple-700 text-white rounded-xl text-xs font-bold shadow-sm">
                                            + Tambah Video
                                        </button>
                                    </div>

                                    <div class="space-y-2">
                                        <template x-for="v in videos" :key="v.id">
                                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 flex justify-between items-center text-xs">
                                                <div>
                                                    <span class="text-[9px] bg-purple-100 text-purple-800 font-bold px-2 py-0.5 rounded-full" x-text="v.kategori"></span>
                                                    <h4 class="font-bold text-slate-900 mt-0.5" x-text="v.judul"></h4>
                                                    <p class="text-[10px] text-slate-500 truncate max-w-xs" x-text="v.url"></p>
                                                </div>
                                                <div class="flex space-x-1 shrink-0">
                                                    <button @click="openEditVideoModal(v)" class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-[10px] font-bold">Edit</button>
                                                    <button @click="deleteVideo(v.id)" class="px-2 py-1 bg-rose-100 text-rose-700 rounded-lg text-[10px] font-bold">Hapus</button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- SUB-SECTION 4: KELOLA FAQ -->
                            <div x-show="adminSubTab === 'faq'" class="space-y-3">
                                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                                    <div class="flex justify-between items-center">
                                        <h3 class="font-extrabold text-xs text-purple-950 uppercase tracking-wider">Kelola Pertanyaan FAQ</h3>
                                        <button @click="openAddFaqModal()" class="px-2.5 py-1 bg-purple-700 text-white rounded-xl text-xs font-bold shadow-sm">
                                            + Tambah FAQ
                                        </button>
                                    </div>

                                    <div class="space-y-2">
                                        <template x-for="f in faqs" :key="f.id">
                                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 flex justify-between items-center text-xs">
                                                <div class="pr-2">
                                                    <h4 class="font-bold text-slate-900" x-text="f.q"></h4>
                                                    <p class="text-[10px] text-slate-500 line-clamp-1" x-text="f.a"></p>
                                                </div>
                                                <div class="flex space-x-1 shrink-0">
                                                    <button @click="openEditFaqModal(f)" class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-[10px] font-bold">Edit</button>
                                                    <button @click="deleteFaq(f.id)" class="px-2 py-1 bg-rose-100 text-rose-700 rounded-lg text-[10px] font-bold">Hapus</button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- SUB-SECTION 5: KELOLA TIM PENELITI -->
                            <div x-show="adminSubTab === 'about'" class="space-y-3">
                                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                                    <div class="flex justify-between items-center">
                                        <h3 class="font-extrabold text-xs text-purple-950 uppercase tracking-wider">Kelola Nama & Foto Peneliti</h3>
                                        <button @click="openAddResearcherModal()" class="px-2.5 py-1 bg-purple-700 text-white rounded-xl text-xs font-bold shadow-sm">
                                            + Tambah Peneliti
                                        </button>
                                    </div>

                                    <div class="space-y-2">
                                        <template x-for="person in researchers" :key="person.id">
                                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 flex items-center justify-between space-x-3 text-xs">
                                                <div class="flex items-center space-x-3 overflow-hidden">
                                                    <img :src="person.photo || '/images/logo1.png'" class="w-10 h-10 aspect-square object-cover rounded-xl shadow shrink-0" onError="this.onerror=null; this.src='/images/logo1.png';">
                                                    <div class="overflow-hidden">
                                                        <h4 class="font-extrabold text-purple-950 truncate" x-text="person.name"></h4>
                                                        <p class="text-[10px] text-slate-500 truncate" x-text="person.role"></p>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-1 shrink-0">
                                                    <button @click="openEditResearcherModal(person)" class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-[10px] font-bold">Edit</button>
                                                    <button @click="deleteResearcher(person.id)" class="px-2 py-1 bg-rose-100 text-rose-700 rounded-lg text-[10px] font-bold">Hapus</button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- SUB-SECTION 6: PENGATURAN ICON & NAMA APLIKASI -->
                            <div x-show="adminSubTab === 'settings'" class="space-y-3">
                                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                                    <h3 class="font-extrabold text-xs text-purple-950 uppercase tracking-wider border-b border-slate-100 pb-2">
                                        <i class="fa-solid fa-gears text-purple-600 mr-1"></i> Ganti Icon & Identitas Aplikasi
                                    </h3>

                                    <form @submit.prevent="saveAppSettings()" class="space-y-3 text-xs">
                                        <!-- Preview Icon current -->
                                        <div class="flex items-center space-x-4 p-3 bg-purple-50 rounded-2xl border border-purple-100">
                                            <img :src="appSettings.appLogo" class="h-14 w-auto object-contain bg-white p-2 rounded-xl border shadow-sm" onError="this.onerror=null; this.src='/images/logo1.png';">
                                            <div>
                                                <h4 class="font-bold text-purple-950">Preview Logo Aplikasi</h4>
                                                <p class="text-[10px] text-slate-500">Logo saat ini digunakan di header & kartu hasil tes</p>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1">Nama Aplikasi *</label>
                                            <input type="text" x-model="appSettings.appName" required placeholder="SA'BAWA"
                                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 font-bold focus:outline-none focus:border-purple-600">
                                        </div>

                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1">Subtitle / Deskripsi Pendek *</label>
                                            <input type="text" x-model="appSettings.appSubtitle" required placeholder="Silvi Aryanti' Badminton Assessment WebApp"
                                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-purple-600">
                                        </div>

                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1">URL / Path Logo Icon *</label>
                                            <input type="text" x-model="appSettings.appLogo" required placeholder="/images/logo1.png"
                                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-purple-600">
                                            <p class="text-[10px] text-slate-400 mt-0.5">Bisa gunakan path lokal seperti <code>/images/logo1.png</code> atau link image online HTTPS.</p>
                                        </div>

                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1">Judul Banner Utama (Hero Title)</label>
                                            <textarea x-model="appSettings.heroTitle" rows="2"
                                                      class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-purple-600"></textarea>
                                        </div>

                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1">Deskripsi Banner Utama (Hero Description)</label>
                                            <textarea x-model="appSettings.heroDescription" rows="3"
                                                      class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-purple-600"></textarea>
                                        </div>

                                        <button type="submit" class="w-full py-2.5 bg-purple-700 hover:bg-purple-800 text-white rounded-xl font-bold shadow-md transition-all">
                                            <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Perubahan Pengaturan
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>

                    </div>

                    <!-- BOTTOM MOBILE BANK NAVIGATION BAR -->
                    <nav class="fixed lg:absolute bottom-0 inset-x-0 bg-white/95 backdrop-blur-xl border-t border-slate-200 py-2 px-2 grid grid-cols-6 text-center no-print z-30 shadow-lg">
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
                <img :src="appSettings.appLogo" :alt="appSettings.appName" class="h-14 mx-auto object-contain mb-2" onError="this.onerror=null; this.src='/images/logo1.png';">
                <h3 class="text-base font-extrabold text-slate-900">Login Admin <span x-text="appSettings.appName"></span></h3>
                <p class="text-xs text-slate-500">Masuk untuk kelola & edit semua data aplikasi</p>
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

                <button type="submit" class="w-full py-2.5 bg-purple-700 hover:bg-purple-800 text-white rounded-xl text-xs font-bold shadow-md transition-all">
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
                <img :src="appSettings.appLogo" :alt="appSettings.appName" class="h-10 mx-auto object-contain mb-1" onError="this.onerror=null; this.src='/images/logo1.png';">
                <h3 class="text-sm font-black text-slate-900">KARTU HASIL TES BULUTANGKIS</h3>
                <p class="text-[11px] text-purple-700 font-semibold" x-text="appSettings.appName + ' (' + appSettings.appSubtitle + ')'"></p>
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
                            <p class="font-bold underline" x-text="selectedRecord.penguji || (researchers[0] ? researchers[0].name : 'Silvi Aryanti, M.Pd.')"></p>
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

    <!-- MODAL 4: MATERI CRUD MODAL -->
    <div x-show="showMateriModal" x-transition.opacity class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 no-print">
        <div class="bg-white rounded-3xl w-full max-w-md p-6 space-y-4 shadow-2xl relative border border-purple-100 max-h-[90vh] overflow-y-auto">
            <button @click="showMateriModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <h3 class="text-base font-extrabold text-purple-950" x-text="materiForm.id ? 'Edit Materi Tes' : 'Tambah Materi Tes'"></h3>

            <form @submit.prevent="saveMateri()" class="space-y-3 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Materi *</label>
                    <input type="text" x-model="materiForm.judul" required placeholder="Judul Tes / Keterampilan"
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-purple-600">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kategori *</label>
                    <input type="text" x-model="materiForm.kategori" required placeholder="Servis Pendek, Servis Panjang, Lob, Smash, dll"
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-purple-600">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL / Link Foto Cover</label>
                    <input type="text" x-model="materiForm.photo" placeholder="https://images.unsplash.com/..."
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-purple-600">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi Materi *</label>
                    <textarea x-model="materiForm.deskripsi" required rows="3" placeholder="Penjelasan rincian materi tes..."
                              class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-purple-600"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Petunjuk Pelaksanaan Tes</label>
                    <textarea x-model="materiForm.petunjuk" rows="2" placeholder="Petunjuk khusus bagi teste / penguji..."
                              class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-purple-600"></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 bg-purple-700 hover:bg-purple-800 text-white rounded-xl font-bold shadow-md transition-all">
                    Simpan Materi
                </button>
            </form>
        </div>
    </div>

    <!-- MODAL 5: VIDEO CRUD MODAL -->
    <div x-show="showVideoModal" x-transition.opacity class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 no-print">
        <div class="bg-white rounded-3xl w-full max-w-md p-6 space-y-4 shadow-2xl relative border border-purple-100 max-h-[90vh] overflow-y-auto">
            <button @click="showVideoModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <h3 class="text-base font-extrabold text-purple-950" x-text="videoForm.id ? 'Edit Video Tutorial' : 'Tambah Video Tutorial'"></h3>

            <form @submit.prevent="saveVideo()" class="space-y-3 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Video *</label>
                    <input type="text" x-model="videoForm.judul" required placeholder="Judul Video Tutorial"
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-purple-600">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kategori *</label>
                    <input type="text" x-model="videoForm.kategori" required placeholder="Teknik Dasar, Servis, Lob, Smash"
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-purple-600">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Link Embed YouTube URL *</label>
                    <input type="text" x-model="videoForm.url" required placeholder="https://www.youtube.com/embed/..."
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-purple-600">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi Singkat</label>
                    <textarea x-model="videoForm.deskripsi" rows="2" placeholder="Keterangan isi video..."
                              class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-purple-600"></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 bg-purple-700 hover:bg-purple-800 text-white rounded-xl font-bold shadow-md transition-all">
                    Simpan Video
                </button>
            </form>
        </div>
    </div>

    <!-- MODAL 6: FAQ CRUD MODAL -->
    <div x-show="showFaqModal" x-transition.opacity class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 no-print">
        <div class="bg-white rounded-3xl w-full max-w-md p-6 space-y-4 shadow-2xl relative border border-purple-100 max-h-[90vh] overflow-y-auto">
            <button @click="showFaqModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <h3 class="text-base font-extrabold text-purple-950" x-text="faqForm.id ? 'Edit Pertanyaan FAQ' : 'Tambah Pertanyaan FAQ'"></h3>

            <form @submit.prevent="saveFaq()" class="space-y-3 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Pertanyaan *</label>
                    <input type="text" x-model="faqForm.q" required placeholder="Apa itu aplikasi SA'BAWA?"
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-purple-600">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Jawaban *</label>
                    <textarea x-model="faqForm.a" required rows="3" placeholder="Penjelasan lengkap jawaban..."
                              class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-purple-600"></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 bg-purple-700 hover:bg-purple-800 text-white rounded-xl font-bold shadow-md transition-all">
                    Simpan FAQ
                </button>
            </form>
        </div>
    </div>

    <!-- MODAL 7: RESEARCHER / TIM PENELITI CRUD MODAL -->
    <div x-show="showResearcherModal" x-transition.opacity class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 no-print">
        <div class="bg-white rounded-3xl w-full max-w-md p-6 space-y-4 shadow-2xl relative border border-purple-100 max-h-[90vh] overflow-y-auto">
            <button @click="showResearcherModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <h3 class="text-base font-extrabold text-purple-950" x-text="researcherForm.id ? 'Edit Data Peneliti' : 'Tambah Peneliti Baru'"></h3>

            <form @submit.prevent="saveResearcher()" class="space-y-3 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Lengkap & Gelar *</label>
                    <input type="text" x-model="researcherForm.name" required placeholder="Silvi Aryanti, M.Pd."
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-purple-600">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Peran / NIDN / Jabatan *</label>
                    <input type="text" x-model="researcherForm.role" required placeholder="Ketua Peneliti • NIDN 0021079101"
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-purple-600">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL / Path Foto Peneliti *</label>
                    <input type="text" x-model="researcherForm.photo" required placeholder="images/Picture1.png"
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-purple-600">
                    <p class="text-[10px] text-slate-400 mt-0.5">Bisa gunakan path gambar seperti <code>images/Picture1.png</code> atau link foto online.</p>
                </div>

                <div class="flex items-center space-x-2 pt-1">
                    <input type="checkbox" id="isLeader" x-model="researcherForm.isLeader" class="rounded text-purple-700 focus:ring-purple-600">
                    <label for="isLeader" class="text-slate-700 font-bold">Jadikan Ketua Peneliti Utama</label>
                </div>

                <button type="submit" class="w-full py-2.5 bg-purple-700 hover:bg-purple-800 text-white rounded-xl font-bold shadow-md transition-all">
                    Simpan Data Peneliti
                </button>
            </form>
        </div>
    </div>

    <!-- MODAL 8: SCHOOL / SEKOLAH CRUD MODAL -->
    <div x-show="showSchoolModal" x-transition.opacity class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 no-print">
        <div class="bg-white rounded-3xl w-full max-w-md p-6 space-y-4 shadow-2xl relative border border-purple-100 max-h-[90vh] overflow-y-auto">
            <button @click="showSchoolModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <h3 class="text-base font-extrabold text-purple-950" x-text="schoolForm.id ? 'Edit Data Sekolah' : 'Tambah Sekolah Baru'"></h3>

            <form @submit.prevent="saveSchool()" class="space-y-3 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Sekolah *</label>
                    <input type="text" x-model="schoolForm.nama" required placeholder="SMP 4 Palembang"
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-purple-600">
                </div>

                <button type="submit" class="w-full py-2.5 bg-purple-700 hover:bg-purple-800 text-white rounded-xl font-bold shadow-md transition-all">
                    Simpan Sekolah
                </button>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT APP LOGIC (ALPINE.JS CONTROLLER) -->
    <script>
        function sabawaApp() {
            return {
                activeTab: 'home',
                adminSubTab: 'form',
                isAdmin: localStorage.getItem('sabawa_admin') === 'true',
                showLoginModal: false,
                currentTime: '',
                loginForm: { username: 'admin', password: '' },
                loginError: '',

                searchQuery: '',
                filterCategory: '',
                filterSchool: '',
                selectedRecord: null,
                activeVideo: null,
                activeVideoTitle: '',
                editingIndex: null,

                // Modal Toggle States
                showMateriModal: false,
                showVideoModal: false,
                showFaqModal: false,
                showResearcherModal: false,
                showSchoolModal: false,

                // Schools State
                schools: [],
                schoolForm: { id: null, nama: '' },

                // App Settings & Customization
                appSettings: {
                    appName: "SA'BAWA",
                    appSubtitle: "Silvi Aryanti' Badminton Assessment WebApp",
                    appLogo: "/images/logo1.png",
                    heroTitle: "Pengembangan Instrumen Penilaian Teknik Dasar Bulutangkis",
                    heroDescription: "Aplikasi SA'BAWA (Silvi Aryanti' Badminton Assessment WebApp) dirancang khusus untuk mempermudah penilaian dan pengolahan skor tes 4 teknik dasar bulutangkis secara otomatis berdasarkan standar norma ilmiah."
                },

                // Researchers / Tim Peneliti State
                researchers: [
                    { id: 1, name: "Silvi Aryanti, M.Pd.", role: "Ketua Peneliti • NIDN 0021079101", photo: "images/Picture1.png", isLeader: true },
                    { id: 2, name: "Destriana, M.Pd.", role: "Anggota 1 • NIDN 0001128905", photo: "images/Picture2.png", isLeader: false },
                    { id: 3, name: "Fitri Agung Nanda, M.Pd.", role: "Anggota 2 • NIDN 0016039408", photo: "images/Picture3.png", isLeader: false },
                    { id: 4, name: "Soleh Solahuddin, M.Pd.", role: "Anggota 3 • NIDK 8898323419", photo: "images/Picture4.png", isLeader: false }
                ],

                // Materis List State
                materis: [
                    {
                        id: 1,
                        judul: "Overview Instrumen Penilaian Bulutangkis",
                        kategori: "Umum",
                        photo: "https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?auto=format&fit=crop&w=800&q=80",
                        deskripsi: "Instrumen Penilaian Bulutangkis ini dikembangkan oleh Silvi Aryanti, M.Pd. dan tim mengacu pada metode penelitian R&D (Sugiyono, 2009: 148 & Suharsimi Arikunto, 2013: 193). Mengukur 4 keterampilan utama: Servis Pendek, Servis Panjang, Pukulan Lob, dan Pukulan Smash.",
                        petunjuk: "Setiap teste melakukan 20 kali percobaan pada masing-masing item tes. Penguji mencatat skor per percobaan pada form."
                    },
                    {
                        id: 2,
                        judul: "1. Servis Pendek (Short Serve Test)",
                        kategori: "Servis Pendek",
                        photo: "https://images.unsplash.com/photo-1521537634581-0ddea2efe2b6?auto=format&fit=crop&w=800&q=80",
                        deskripsi: "Tes Servis Pendek (Manurung 2018) bertujuan mengukur akurasi dan ketepatan servis backhand/forehand tipis di atas net menuju area sasaran bernilai 5, 4, 3, 2, dan 1.",
                        petunjuk: "Subjek berdiri di petak servis dan melakukan 20 kali servis pendek berurutan. Bola yang menyangkut di net mendapat skor 0."
                    },
                    {
                        id: 3,
                        judul: "2. Servis Panjang (Long Serve Test)",
                        kategori: "Servis Panjang",
                        photo: "https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?auto=format&fit=crop&w=800&q=80",
                        deskripsi: "Tes Servis Panjang (Bayu Tri Kurniawan 2018:54) mengukur kemampuan melambungkan shuttlecock jauh dan tinggi menuju garis belakang batas lapangan lawan.",
                        petunjuk: "Subjek diberi kesempatan 20 kali melakukan servis melambung tinggi. Nilai dicatat sesuai angka pada target garis belakang."
                    },
                    {
                        id: 4,
                        judul: "3. Tes Pukulan Lob (High Clear Test)",
                        kategori: "Tes Lob",
                        photo: "https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?auto=format&fit=crop&w=800&q=80",
                        deskripsi: "Pukulan Lob mengukur kemampuan mengembalikan shuttlecock melambung tinggi melampaui tali batas setinggi 155 cm (8 kaki) menuju lapangan belakang lawan.",
                        petunjuk: "Shuttlecock diumpan oleh penguji, subjek melakukan pukulan lob 20 kali. Bola yang melewati bawah tali dianggap tidak sah (skor 0)."
                    },
                    {
                        id: 5,
                        judul: "4. Tes Pukulan Smash (Smash Test)",
                        kategori: "Tes Smash",
                        photo: "https://images.unsplash.com/photo-1613918108466-292b78a8ef95?auto=format&fit=crop&w=800&q=80",
                        deskripsi: "Tes Smash mengukur kecepatan, ketepatan, dan menukiknya pukulan smash forehand ke area sasaran bernilai pada lapangan lawan.",
                        petunjuk: "Subjek menerima 20 umpan lob tinggi dari penguji dan wajib melakukan smash keras menukik ke area target lawan."
                    }
                ],

                // Videos List State
                videos: [
                    { id: 1, judul: "Teknik Servis Pendek Backhand", kategori: "Servis Pendek", url: "https://www.youtube.com/embed/5D2Y8JtK11A", deskripsi: "Panduan rincian gerakan dan posisi pegangan raket untuk servis pendek." },
                    { id: 2, judul: "Teknik Servis Panjang Forehand", kategori: "Servis Panjang", url: "https://www.youtube.com/embed/sLd2vHnQO9k", deskripsi: "Panduan servis melambung tinggi jauh ke belakang lapangan lawan." }
                ],

                // FAQs List State
                faqs: [
                    { id: 1, q: "Apa itu aplikasi SA'BAWA?", a: "SA'BAWA (Silvi Aryanti' Badminton Assessment WebApp) adalah aplikasi web yang dikembangkan oleh tim Silvi Aryanti, M.Pd. untuk mengukur dan mengonversi hasil tes 4 teknik dasar bulutangkis secara otomatis berdasarkan standar norma ilmiah." },
                    { id: 2, q: "Siapa saja tim peneliti pengembang instrumen ini?", a: "Ketua: Silvi Aryanti, M.Pd. (NIDN 0021079101), Anggota: 1. Destriana, M.Pd., 2. Fitri Agung Nanda, M.Pd., 3. Soleh Solahuddin, M.Pd." },
                    { id: 3, q: "Berapa kali kesempatan servis/pukulan yang diberikan?", a: "Setiap teste mendapatkan 20 kali kesempatan percobaan untuk masing-masing tes (Servis Pendek, Servis Panjang, Lob, dan Smash)." },
                    { id: 4, q: "Bagaimana cara penilaian Servis Pendek & Panjang?", a: "Shuttlecock diarahkan ke zona sasaran bernilai 5, 4, 3, 2, dan 1. Skor dikonversi ke norma nilai otomatis." },
                    { id: 5, q: "Bagaimana cara login Admin?", a: "Silakan login dengan akun admin." }
                ],

                // Forms CRUD Objects
                form: {
                    nama: '',
                    nim: '',
                    gender: 'L',
                    kelas: 'Palembang A 2024',
                    sekolah: '',
                    tanggal: new Date().toISOString().split('T')[0],
                    penguji: 'Silvi Aryanti, M.Pd.',
                    skorServisPendek: null,
                    skorServisPanjang: null,
                    skorLob: null,
                    skorSmash: null
                },

                materiForm: { id: null, judul: '', kategori: '', photo: '', deskripsi: '', petunjuk: '' },
                videoForm: { id: null, judul: '', kategori: '', url: '', deskripsi: '' },
                faqForm: { id: null, q: '', a: '' },
                researcherForm: { id: null, name: '', role: '', photo: '', isLeader: false },

                records: [],

                init() {
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);
                    this.loadAllData();
                },

                updateTime() {
                    const now = new Date();
                    this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                },

                loadAllData() {
                    // Load Records
                    const storedRecords = localStorage.getItem('sabawa_records');
                    if (storedRecords) {
                        this.records = JSON.parse(storedRecords);
                    } else {
                        this.seedSampleData();
                    }

                    // Load Settings
                    const storedSettings = localStorage.getItem('sabawa_settings');
                    if (storedSettings) this.appSettings = JSON.parse(storedSettings);

                    // Load Researchers
                    const storedResearchers = localStorage.getItem('sabawa_researchers');
                    if (storedResearchers) this.researchers = JSON.parse(storedResearchers);

                    // Load Materis
                    const storedMateris = localStorage.getItem('sabawa_materis');
                    if (storedMateris) this.materis = JSON.parse(storedMateris);

                    // Load Videos
                    const storedVideos = localStorage.getItem('sabawa_videos');
                    if (storedVideos) this.videos = JSON.parse(storedVideos);

                    // Load FAQs
                    const storedFaqs = localStorage.getItem('sabawa_faqs');
                    if (storedFaqs) this.faqs = JSON.parse(storedFaqs);

                    // Load Schools
                    const storedSchools = localStorage.getItem('sabawa_schools');
                    if (storedSchools) {
                        this.schools = JSON.parse(storedSchools);
                    } else {
                        this.schools = [
                            { id: 1, nama: 'SMP 4' },
                            { id: 2, nama: 'SMP 5' }
                        ];
                        localStorage.setItem('sabawa_schools', JSON.stringify(this.schools));
                    }
                },

                saveRecordsToStorage() {
                    localStorage.setItem('sabawa_records', JSON.stringify(this.records));
                },

                saveSettingsToStorage() {
                    localStorage.setItem('sabawa_settings', JSON.stringify(this.appSettings));
                    localStorage.setItem('sabawa_researchers', JSON.stringify(this.researchers));
                    localStorage.setItem('sabawa_materis', JSON.stringify(this.materis));
                    localStorage.setItem('sabawa_videos', JSON.stringify(this.videos));
                    localStorage.setItem('sabawa_faqs', JSON.stringify(this.faqs));
                    localStorage.setItem('sabawa_schools', JSON.stringify(this.schools));
                },

                seedSampleData() {
                    this.records = [
                        {
                            id: 1,
                            nama: 'Ahmad Rizky Pratama',
                            nim: '06121001001',
                            jenisKelamin: 'L',
                            kelas: 'Palembang A 2024',
                            sekolah: 'SMP 4',
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
                            sekolah: 'SMP 5',
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
                            sekolah: 'SMP 4',
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
                        this.activeTab = 'admin';
                        Swal.fire({
                            icon: 'success',
                            title: 'Login Admin Berhasil',
                            text: "Selamat datang kembali di Dashboard Admin " + this.appSettings.appName + "!",
                            confirmButtonColor: '#7e22ce',
                            timer: 2000,
                            timerProgressBar: true,
                            customClass: { popup: 'rounded-3xl' }
                        });
                    } else {
                        this.loginError = 'Username atau Password salah!';
                    }
                },

                logoutAdmin() {
                    this.isAdmin = false;
                    localStorage.removeItem('sabawa_admin');
                    if (this.activeTab === 'admin') this.activeTab = 'home';
                    Swal.fire({
                        icon: 'info',
                        title: 'Logout Admin',
                        text: 'Anda telah keluar dari Dashboard Admin.',
                        confirmButtonColor: '#7e22ce',
                        timer: 2000,
                        timerProgressBar: true,
                        customClass: { popup: 'rounded-3xl' }
                    });
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
                        sekolah: this.form.sekolah,
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Simpan!',
                        text: 'Data penilaian berhasil disimpan.',
                        confirmButtonColor: '#7e22ce',
                        timer: 2000,
                        timerProgressBar: true,
                        customClass: { popup: 'rounded-3xl' }
                    });

                    this.form.nama = '';
                    this.form.nim = '';
                    this.form.sekolah = '';
                    this.form.skorServisPendek = null;
                    this.form.skorServisPanjang = null;
                    this.form.skorLob = null;
                    this.form.skorSmash = null;

                    this.activeTab = 'data';
                },

                editRecord(item) {
                    const idx = this.records.findIndex(r => r.id === item.id);
                    if (idx !== -1) {
                        this.editingIndex = idx;
                        this.form = {
                            nama: item.nama,
                            nim: item.nim,
                            gender: item.jenisKelamin,
                            kelas: item.kelas,
                            sekolah: item.sekolah || '',
                            tanggal: item.tanggal,
                            penguji: item.penguji,
                            skorServisPendek: item.skorServisPendek,
                            skorServisPanjang: item.skorServisPanjang,
                            skorLob: item.skorLob,
                            skorSmash: item.skorSmash
                        };
                        this.activeTab = 'form';
                    }
                },

                deleteRecord(id) {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: 'Data tes ini akan dihapus secara permanen!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7e22ce',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal',
                        customClass: { popup: 'rounded-3xl' }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.records = this.records.filter(r => r.id !== id);
                            this.saveRecordsToStorage();
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: 'Data tes telah berhasil dihapus.',
                                confirmButtonColor: '#7e22ce',
                                timer: 2000,
                                timerProgressBar: true,
                                customClass: { popup: 'rounded-3xl' }
                            });
                        }
                    });
                },

                // CRUD MATERI
                openAddMateriModal() {
                    this.materiForm = { id: null, judul: '', kategori: 'Servis Pendek', photo: '', deskripsi: '', petunjuk: '' };
                    this.showMateriModal = true;
                },
                openEditMateriModal(item) {
                    this.materiForm = { ...item };
                    this.showMateriModal = true;
                },
                saveMateri() {
                    if (this.materiForm.id) {
                        const idx = this.materis.findIndex(m => m.id === this.materiForm.id);
                        if (idx !== -1) this.materis[idx] = { ...this.materiForm };
                    } else {
                        this.materis.push({ ...this.materiForm, id: Date.now() });
                    }
                    this.saveSettingsToStorage();
                    this.showMateriModal = false;
                    Swal.fire({ icon: 'success', title: 'Materi Tersimpan', confirmButtonColor: '#7e22ce', timer: 1500, customClass: { popup: 'rounded-3xl' } });
                },
                deleteMateri(id) {
                    Swal.fire({
                        title: 'Hapus Materi ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7e22ce',
                        confirmButtonText: 'Ya, hapus'
                    }).then(res => {
                        if (res.isConfirmed) {
                            this.materis = this.materis.filter(m => m.id !== id);
                            this.saveSettingsToStorage();
                            Swal.fire({ icon: 'success', title: 'Materi Terhapus', confirmButtonColor: '#7e22ce', timer: 1500 });
                        }
                    });
                },

                // CRUD VIDEO
                openAddVideoModal() {
                    this.videoForm = { id: null, judul: '', kategori: 'Teknik Dasar', url: '', deskripsi: '' };
                    this.showVideoModal = true;
                },
                openEditVideoModal(item) {
                    this.videoForm = { ...item };
                    this.showVideoModal = true;
                },
                saveVideo() {
                    if (this.videoForm.id) {
                        const idx = this.videos.findIndex(v => v.id === this.videoForm.id);
                        if (idx !== -1) this.videos[idx] = { ...this.videoForm };
                    } else {
                        this.videos.push({ ...this.videoForm, id: Date.now() });
                    }
                    this.saveSettingsToStorage();
                    this.showVideoModal = false;
                    Swal.fire({ icon: 'success', title: 'Video Tersimpan', confirmButtonColor: '#7e22ce', timer: 1500, customClass: { popup: 'rounded-3xl' } });
                },
                deleteVideo(id) {
                    Swal.fire({
                        title: 'Hapus Video ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7e22ce',
                        confirmButtonText: 'Ya, hapus'
                    }).then(res => {
                        if (res.isConfirmed) {
                            this.videos = this.videos.filter(v => v.id !== id);
                            this.saveSettingsToStorage();
                            Swal.fire({ icon: 'success', title: 'Video Terhapus', confirmButtonColor: '#7e22ce', timer: 1500 });
                        }
                    });
                },

                // CRUD FAQ
                openAddFaqModal() {
                    this.faqForm = { id: null, q: '', a: '' };
                    this.showFaqModal = true;
                },
                openEditFaqModal(item) {
                    this.faqForm = { ...item };
                    this.showFaqModal = true;
                },
                saveFaq() {
                    if (this.faqForm.id) {
                        const idx = this.faqs.findIndex(f => f.id === this.faqForm.id);
                        if (idx !== -1) this.faqs[idx] = { ...this.faqForm };
                    } else {
                        this.faqs.push({ ...this.faqForm, id: Date.now() });
                    }
                    this.saveSettingsToStorage();
                    this.showFaqModal = false;
                    Swal.fire({ icon: 'success', title: 'FAQ Tersimpan', confirmButtonColor: '#7e22ce', timer: 1500, customClass: { popup: 'rounded-3xl' } });
                },
                deleteFaq(id) {
                    Swal.fire({
                        title: 'Hapus FAQ ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7e22ce',
                        confirmButtonText: 'Ya, hapus'
                    }).then(res => {
                        if (res.isConfirmed) {
                            this.faqs = this.faqs.filter(f => f.id !== id);
                            this.saveSettingsToStorage();
                            Swal.fire({ icon: 'success', title: 'FAQ Terhapus', confirmButtonColor: '#7e22ce', timer: 1500 });
                        }
                    });
                },

                // CRUD RESEARCHERS
                openAddResearcherModal() {
                    this.researcherForm = { id: null, name: '', role: '', photo: 'images/Picture1.png', isLeader: false };
                    this.showResearcherModal = true;
                },
                openEditResearcherModal(person) {
                    this.researcherForm = { ...person };
                    this.showResearcherModal = true;
                },
                saveResearcher() {
                    if (this.researcherForm.id) {
                        const idx = this.researchers.findIndex(r => r.id === this.researcherForm.id);
                        if (idx !== -1) this.researchers[idx] = { ...this.researcherForm };
                    } else {
                        this.researchers.push({ ...this.researcherForm, id: Date.now() });
                    }
                    this.saveSettingsToStorage();
                    this.showResearcherModal = false;
                    Swal.fire({ icon: 'success', title: 'Data Peneliti Tersimpan', confirmButtonColor: '#7e22ce', timer: 1500, customClass: { popup: 'rounded-3xl' } });
                },
                deleteResearcher(id) {
                    Swal.fire({
                        title: 'Hapus Peneliti ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7e22ce',
                        confirmButtonText: 'Ya, hapus'
                    }).then(res => {
                        if (res.isConfirmed) {
                            this.researchers = this.researchers.filter(r => r.id !== id);
                            this.saveSettingsToStorage();
                            Swal.fire({ icon: 'success', title: 'Data Peneliti Terhapus', confirmButtonColor: '#7e22ce', timer: 1500 });
                        }
                    });
                },

                // SCHOOLS CRUD
                saveSchool() {
                    if (!this.schoolForm.nama.trim()) return;

                    const schoolData = {
                        id: this.schoolForm.id !== null ? this.schoolForm.id : Date.now(),
                        nama: this.schoolForm.nama.trim()
                    };

                    const idx = this.schoolForm.id !== null 
                        ? this.schools.findIndex(s => s.id === this.schoolForm.id)
                        : -1;

                    if (idx !== -1) {
                        this.schools[idx] = schoolData;
                    } else {
                        this.schools.push(schoolData);
                    }

                    this.saveSettingsToStorage();
                    this.schoolForm.id = null;
                    this.schoolForm.nama = '';
                    this.showSchoolModal = false;

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Simpan Sekolah!',
                        confirmButtonColor: '#7e22ce',
                        timer: 1500
                    });
                },

                editSchool(school) {
                    this.schoolForm = { id: school.id, nama: school.nama };
                    this.showSchoolModal = true;
                },

                deleteSchool(id) {
                    Swal.fire({
                        title: 'Hapus Sekolah ini?',
                        text: 'Semua atlet yang terhubung dengan sekolah ini tidak akan terhapus, tetapi rincian sekolah mereka akan kosong.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7e22ce',
                        confirmButtonText: 'Ya, hapus'
                    }).then(res => {
                        if (res.isConfirmed) {
                            this.schools = this.schools.filter(s => s.id !== id);
                            this.saveSettingsToStorage();
                            Swal.fire({ icon: 'success', title: 'Sekolah Terhapus', confirmButtonColor: '#7e22ce', timer: 1500 });
                        }
                    });
                },

                // APP SETTINGS
                saveAppSettings() {
                    this.saveSettingsToStorage();
                    Swal.fire({
                        icon: 'success',
                        title: 'Pengaturan Tersimpan!',
                        text: 'Icon logo & nama aplikasi berhasil diperbarui.',
                        confirmButtonColor: '#7e22ce',
                        timer: 2000,
                        customClass: { popup: 'rounded-3xl' }
                    });
                },

                get filteredRecords() {
                    return this.records.filter(r => {
                        const matchQuery = !this.searchQuery || r.nama.toLowerCase().includes(this.searchQuery.toLowerCase()) || r.nim.includes(this.searchQuery);
                        const matchCat = !this.filterCategory || r.evaluasiTotal === this.filterCategory;
                        const matchSchool = !this.filterSchool || r.sekolah === this.filterSchool;
                        return matchQuery && matchCat && matchSchool;
                    });
                },

                getAverageScore() {
                    const items = this.filteredRecords;
                    if (items.length === 0) return '0';
                    const sum = items.reduce((acc, r) => acc + (r.skorServisPendek || 0) + (r.skorServisPanjang || 0) + (r.skorLob || 0) + (r.skorSmash || 0), 0);
                    return (sum / (items.length * 4)).toFixed(1);
                },

                getCategoryCount(cat) {
                    return this.filteredRecords.filter(r => r.evaluasiTotal === cat).length;
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
                    const items = this.filteredRecords;
                    if (items.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: 'Tidak ada data untuk diekspor.',
                            confirmButtonColor: '#7e22ce',
                            timer: 2000,
                            customClass: { popup: 'rounded-3xl' }
                        });
                        return;
                    }
                    let csv = 'Nama,NIM,Jenis Kelamin,Kelas,Sekolah,Tanggal,Servis Pendek,Norma SP,Servis Panjang,Norma SJ,Lob,Norma Lob,Smash,Norma Smash,Evaluasi Total\n';
                    items.forEach(r => {
                        csv += `"${r.nama}","${r.nim}","${r.jenisKelamin}","${r.kelas}","${r.sekolah || '-'}","${r.tanggal}",${r.skorServisPendek || 0},"${r.normaServisPendek}",${r.skorServisPanjang || 0},"${r.normaServisPanjang}",${r.skorLob || 0},"${r.normaLob}",${r.skorSmash || 0},"${r.normaSmash}","${r.evaluasiTotal}"\n`;
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
