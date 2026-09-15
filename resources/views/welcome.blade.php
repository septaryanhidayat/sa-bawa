<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            input[type=number]::-webkit-inner-spin-button, 
            input[type=number]::-webkit-outer-spin-button { 
                -webkit-appearance: none; 
                margin: 0; 
            }
            input[type=number] { 
                -moz-appearance: textfield; 
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

            <!-- Status Sync & Admin Badge & Controls -->
            <div class="flex items-center space-x-3">
                <!-- MySQL Status & Sync Button -->
                <div class="flex items-center space-x-2 mr-1">
                    <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-[11px] font-bold border transition-colors shadow-sm"
                          :class="dbConnected ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-amber-50 text-amber-800 border-amber-200'">
                        <span class="w-2 h-2 rounded-full" :class="dbConnected ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500'"></span>
                        <span x-text="dbConnected ? ((dbDriver === 'sqlite' ? 'SQLite' : 'MySQL') + ' Terhubung') : 'Offline Cache'"></span>
                    </span>
                    <button @click="loadAllData(true)" :disabled="isSyncing"
                            class="flex items-center space-x-1 px-2.5 py-1 rounded-xl bg-purple-100/70 hover:bg-purple-200/80 text-purple-800 text-[11px] font-bold transition-all disabled:opacity-50"
                            title="Sinkronkan data dari MySQL">
                        <i class="fa-solid fa-arrows-rotate" :class="isSyncing ? 'animate-spin' : ''"></i>
                        <span class="hidden md:inline">Sinkron</span>
                    </button>
                </div>

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
                                        <div class="flex items-center space-x-1.5 flex-wrap gap-y-1">
                                            <span class="bg-white/20 backdrop-blur-md text-[11px] px-2.5 py-0.5 rounded-full font-medium text-purple-100">
                                                👋 Halo, Selamat Datang
                                            </span>
                                            <span class="text-[11px] text-purple-200 font-semibold" x-text="isAdmin ? 'Admin' : 'Guest'"></span>
                                            <button @click="loadAllData(true)" :disabled="isSyncing" class="bg-white/25 hover:bg-white/35 text-white px-2 py-0.5 rounded-full text-[9px] font-bold flex items-center space-x-1 transition-all" title="Sinkronkan Database">
                                                <i class="fa-solid fa-arrows-rotate text-[8px]" :class="isSyncing ? 'animate-spin' : ''"></i>
                                                <span x-text="(dbDriver === 'sqlite' ? 'SQLite' : 'MySQL') + ' Sync'"></span>
                                            </button>
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

                                <!-- INPUT SKOR HASIL TES (20x PERCOBAAN PER TEKNIK) -->
                                <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm space-y-3.5">
                                    <div class="border-b border-slate-100 pb-2 flex justify-between items-center">
                                        <div>
                                            <h3 class="text-xs font-bold text-purple-900 uppercase tracking-wider flex items-center">
                                                <i class="fa-solid fa-list-check text-purple-600 mr-1.5"></i> Input 20x Percobaan Tes
                                            </h3>
                                            <p class="text-[10px] text-slate-500">Ketik nilai (0 - 5) pada tiap kesempatan. Skor akumulasi & norma terhitung otomatis.</p>
                                        </div>
                                        <span class="bg-purple-100 text-purple-800 text-[10px] px-2.5 py-0.5 rounded-full font-bold">
                                            20x Coba
                                        </span>
                                    </div>

                                    <!-- 1. SERVIS PENDEK (SHORT SERVE) -->
                                    <div class="bg-slate-50/80 rounded-2xl p-3 border border-slate-200 space-y-2.5">
                                        <div class="flex justify-between items-center cursor-pointer select-none" @click="toggleTechniqueExpand('sp')">
                                            <div class="flex items-center space-x-2">
                                                <span class="w-6 h-6 rounded-lg bg-purple-600 text-white flex items-center justify-center font-extrabold text-[11px] shadow-sm">1</span>
                                                <div>
                                                    <h4 class="text-xs font-extrabold text-slate-900">Servis Pendek (Short Serve)</h4>
                                                    <p class="text-[10px] text-slate-500">
                                                        Akumulasi: <strong class="text-purple-700 font-extrabold text-xs" x-text="form.skorServisPendek ?? 0"></strong><span class="text-slate-400">/100</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-extrabold" :class="getCategoryBadgeClass(calculateNormaServisPendek(form.skorServisPendek))">
                                                    <span x-text="calculateNormaServisPendek(form.skorServisPendek)"></span>
                                                </span>
                                                <i class="fa-solid text-xs text-slate-400 transition-transform duration-200" :class="expandedTechniques.sp ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                            </div>
                                        </div>

                                        <!-- Grid 20 Percobaan SP -->
                                        <div x-show="expandedTechniques.sp" x-transition class="space-y-2 pt-2 border-t border-slate-200/70">
                                            <div class="flex justify-between items-center text-[10px]">
                                                <span class="text-slate-500 font-medium">Nilai per kesempatan (0 - 5):</span>
                                                <div class="flex items-center space-x-1">
                                                    <span class="text-[9px] text-slate-400 mr-1">Cepat:</span>
                                                    <button type="button" @click="quickFillTrials('sp', 5)" class="px-1.5 py-0.5 bg-purple-100 hover:bg-purple-200 text-purple-700 font-bold rounded text-[9px] transition-all">5</button>
                                                    <button type="button" @click="quickFillTrials('sp', 4)" class="px-1.5 py-0.5 bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold rounded text-[9px] transition-all">4</button>
                                                    <button type="button" @click="quickFillTrials('sp', 3)" class="px-1.5 py-0.5 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-bold rounded text-[9px] transition-all">3</button>
                                                    <button type="button" @click="resetTrials('sp')" class="px-1.5 py-0.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded text-[9px] transition-all">Reset</button>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-5 sm:grid-cols-10 gap-1.5">
                                                <template x-for="i in 20" :key="'sp-'+i">
                                                    <div class="flex flex-col items-center bg-white p-1 rounded-xl border border-slate-200 shadow-sm focus-within:border-purple-600 focus-within:ring-2 focus-within:ring-purple-200 transition-all">
                                                        <span class="text-[8px] font-bold text-slate-400" x-text="'#'+i"></span>
                                                        <input type="number" min="0" max="5"
                                                               :id="'input-sp-'+(i-1)"
                                                               x-model.number="form.trialsServisPendek[i-1]"
                                                               @input="onTrialInput('sp', i-1, $event)"
                                                               class="w-full text-center text-xs font-extrabold text-slate-900 bg-transparent focus:outline-none p-0.5">
                                                    </div>
                                                </template>
                                            </div>
                                            <div class="flex justify-between items-center text-[9px] text-slate-400 pt-1">
                                                <span>Norma: &gt;82.2 (Sangat Tinggi), 67-82 (Tinggi), 51-66 (Sedang)</span>
                                                <span class="font-bold text-purple-700">Total: <span x-text="form.skorServisPendek ?? 0"></span></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 2. SERVIS PANJANG (LONG SERVE) -->
                                    <div class="bg-slate-50/80 rounded-2xl p-3 border border-slate-200 space-y-2.5">
                                        <div class="flex justify-between items-center cursor-pointer select-none" @click="toggleTechniqueExpand('sj')">
                                            <div class="flex items-center space-x-2">
                                                <span class="w-6 h-6 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-extrabold text-[11px] shadow-sm">2</span>
                                                <div>
                                                    <h4 class="text-xs font-extrabold text-slate-900">Servis Panjang (Long Serve)</h4>
                                                    <p class="text-[10px] text-slate-500">
                                                        Akumulasi: <strong class="text-indigo-700 font-extrabold text-xs" x-text="form.skorServisPanjang ?? 0"></strong><span class="text-slate-400">/100</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-extrabold" :class="getCategoryBadgeClass(calculateNormaServisPanjang(form.skorServisPanjang))">
                                                    <span x-text="calculateNormaServisPanjang(form.skorServisPanjang)"></span>
                                                </span>
                                                <i class="fa-solid text-xs text-slate-400 transition-transform duration-200" :class="expandedTechniques.sj ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                            </div>
                                        </div>

                                        <!-- Grid 20 Percobaan SJ -->
                                        <div x-show="expandedTechniques.sj" x-transition class="space-y-2 pt-2 border-t border-slate-200/70">
                                            <div class="flex justify-between items-center text-[10px]">
                                                <span class="text-slate-500 font-medium">Nilai per kesempatan (0 - 5):</span>
                                                <div class="flex items-center space-x-1">
                                                    <span class="text-[9px] text-slate-400 mr-1">Cepat:</span>
                                                    <button type="button" @click="quickFillTrials('sj', 5)" class="px-1.5 py-0.5 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-bold rounded text-[9px] transition-all">5</button>
                                                    <button type="button" @click="quickFillTrials('sj', 4)" class="px-1.5 py-0.5 bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold rounded text-[9px] transition-all">4</button>
                                                    <button type="button" @click="quickFillTrials('sj', 3)" class="px-1.5 py-0.5 bg-purple-100 hover:bg-purple-200 text-purple-700 font-bold rounded text-[9px] transition-all">3</button>
                                                    <button type="button" @click="resetTrials('sj')" class="px-1.5 py-0.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded text-[9px] transition-all">Reset</button>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-5 sm:grid-cols-10 gap-1.5">
                                                <template x-for="i in 20" :key="'sj-'+i">
                                                    <div class="flex flex-col items-center bg-white p-1 rounded-xl border border-slate-200 shadow-sm focus-within:border-indigo-600 focus-within:ring-2 focus-within:ring-indigo-200 transition-all">
                                                        <span class="text-[8px] font-bold text-slate-400" x-text="'#'+i"></span>
                                                        <input type="number" min="0" max="5"
                                                               :id="'input-sj-'+(i-1)"
                                                               x-model.number="form.trialsServisPanjang[i-1]"
                                                               @input="onTrialInput('sj', i-1, $event)"
                                                               class="w-full text-center text-xs font-extrabold text-slate-900 bg-transparent focus:outline-none p-0.5">
                                                    </div>
                                                </template>
                                            </div>
                                            <div class="flex justify-between items-center text-[9px] text-slate-400 pt-1">
                                                <span>Norma: &gt;60 (Sangat Tinggi), 47-60 (Tinggi), 34-46 (Sedang)</span>
                                                <span class="font-bold text-indigo-700">Total: <span x-text="form.skorServisPanjang ?? 0"></span></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 3. PUKULAN LOB (HIGH CLEAR) -->
                                    <div class="bg-slate-50/80 rounded-2xl p-3 border border-slate-200 space-y-2.5">
                                        <div class="flex justify-between items-center cursor-pointer select-none" @click="toggleTechniqueExpand('lob')">
                                            <div class="flex items-center space-x-2">
                                                <span class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center font-extrabold text-[11px] shadow-sm">3</span>
                                                <div>
                                                    <h4 class="text-xs font-extrabold text-slate-900">Pukulan Lob (High Clear)</h4>
                                                    <p class="text-[10px] text-slate-500">
                                                        Akumulasi: <strong class="text-blue-700 font-extrabold text-xs" x-text="form.skorLob ?? 0"></strong><span class="text-slate-400">/100</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-extrabold" :class="getCategoryBadgeClass(calculateNormaLob(form.skorLob))">
                                                    <span x-text="calculateNormaLob(form.skorLob)"></span>
                                                </span>
                                                <i class="fa-solid text-xs text-slate-400 transition-transform duration-200" :class="expandedTechniques.lob ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                            </div>
                                        </div>

                                        <!-- Grid 20 Percobaan Lob -->
                                        <div x-show="expandedTechniques.lob" x-transition class="space-y-2 pt-2 border-t border-slate-200/70">
                                            <div class="flex justify-between items-center text-[10px]">
                                                <span class="text-slate-500 font-medium">Nilai per kesempatan (0 - 5):</span>
                                                <div class="flex items-center space-x-1">
                                                    <span class="text-[9px] text-slate-400 mr-1">Cepat:</span>
                                                    <button type="button" @click="quickFillTrials('lob', 5)" class="px-1.5 py-0.5 bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold rounded text-[9px] transition-all">5</button>
                                                    <button type="button" @click="quickFillTrials('lob', 4)" class="px-1.5 py-0.5 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-bold rounded text-[9px] transition-all">4</button>
                                                    <button type="button" @click="quickFillTrials('lob', 3)" class="px-1.5 py-0.5 bg-purple-100 hover:bg-purple-200 text-purple-700 font-bold rounded text-[9px] transition-all">3</button>
                                                    <button type="button" @click="resetTrials('lob')" class="px-1.5 py-0.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded text-[9px] transition-all">Reset</button>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-5 sm:grid-cols-10 gap-1.5">
                                                <template x-for="i in 20" :key="'lob-'+i">
                                                    <div class="flex flex-col items-center bg-white p-1 rounded-xl border border-slate-200 shadow-sm focus-within:border-blue-600 focus-within:ring-2 focus-within:ring-blue-200 transition-all">
                                                        <span class="text-[8px] font-bold text-slate-400" x-text="'#'+i"></span>
                                                        <input type="number" min="0" max="5"
                                                               :id="'input-lob-'+(i-1)"
                                                               x-model.number="form.trialsLob[i-1]"
                                                               @input="onTrialInput('lob', i-1, $event)"
                                                               class="w-full text-center text-xs font-extrabold text-slate-900 bg-transparent focus:outline-none p-0.5">
                                                    </div>
                                                </template>
                                            </div>
                                            <div class="flex justify-between items-center text-[9px] text-slate-400 pt-1">
                                                <span>Norma: &gt;91 (Sangat Tinggi), 80-90 (Tinggi), 70-79 (Sedang)</span>
                                                <span class="font-bold text-blue-700">Total: <span x-text="form.skorLob ?? 0"></span></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 4. PUKULAN SMASH (SMASH TEST) -->
                                    <div class="bg-slate-50/80 rounded-2xl p-3 border border-slate-200 space-y-2.5">
                                        <div class="flex justify-between items-center cursor-pointer select-none" @click="toggleTechniqueExpand('smash')">
                                            <div class="flex items-center space-x-2">
                                                <span class="w-6 h-6 rounded-lg bg-rose-600 text-white flex items-center justify-center font-extrabold text-[11px] shadow-sm">4</span>
                                                <div>
                                                    <h4 class="text-xs font-extrabold text-slate-900">Pukulan Smash (Smash Test)</h4>
                                                    <p class="text-[10px] text-slate-500">
                                                        Akumulasi: <strong class="text-rose-700 font-extrabold text-xs" x-text="form.skorSmash ?? 0"></strong><span class="text-slate-400">/100</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-extrabold" :class="getCategoryBadgeClass(calculateNormaSmash(form.skorSmash))">
                                                    <span x-text="calculateNormaSmash(form.skorSmash)"></span>
                                                </span>
                                                <i class="fa-solid text-xs text-slate-400 transition-transform duration-200" :class="expandedTechniques.smash ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                            </div>
                                        </div>

                                        <!-- Grid 20 Percobaan Smash -->
                                        <div x-show="expandedTechniques.smash" x-transition class="space-y-2 pt-2 border-t border-slate-200/70">
                                            <div class="flex justify-between items-center text-[10px]">
                                                <span class="text-slate-500 font-medium">Nilai per kesempatan (0 - 5):</span>
                                                <div class="flex items-center space-x-1">
                                                    <span class="text-[9px] text-slate-400 mr-1">Cepat:</span>
                                                    <button type="button" @click="quickFillTrials('smash', 5)" class="px-1.5 py-0.5 bg-rose-100 hover:bg-rose-200 text-rose-700 font-bold rounded text-[9px] transition-all">5</button>
                                                    <button type="button" @click="quickFillTrials('smash', 4)" class="px-1.5 py-0.5 bg-orange-100 hover:bg-orange-200 text-orange-700 font-bold rounded text-[9px] transition-all">4</button>
                                                    <button type="button" @click="quickFillTrials('smash', 2)" class="px-1.5 py-0.5 bg-purple-100 hover:bg-purple-200 text-purple-700 font-bold rounded text-[9px] transition-all">2</button>
                                                    <button type="button" @click="resetTrials('smash')" class="px-1.5 py-0.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded text-[9px] transition-all">Reset</button>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-5 sm:grid-cols-10 gap-1.5">
                                                <template x-for="i in 20" :key="'smash-'+i">
                                                    <div class="flex flex-col items-center bg-white p-1 rounded-xl border border-slate-200 shadow-sm focus-within:border-rose-600 focus-within:ring-2 focus-within:ring-rose-200 transition-all">
                                                        <span class="text-[8px] font-bold text-slate-400" x-text="'#'+i"></span>
                                                        <input type="number" min="0" max="5"
                                                               :id="'input-smash-'+(i-1)"
                                                               x-model.number="form.trialsSmash[i-1]"
                                                               @input="onTrialInput('smash', i-1, $event)"
                                                               class="w-full text-center text-xs font-extrabold text-slate-900 bg-transparent focus:outline-none p-0.5">
                                                    </div>
                                                </template>
                                            </div>
                                            <div class="flex justify-between items-center text-[9px] text-slate-400 pt-1">
                                                <span>Norma: &gt;33 (Sangat Tinggi), 25-32 (Tinggi), 17-24 (Sedang)</span>
                                                <span class="font-bold text-rose-700">Total: <span x-text="form.skorSmash ?? 0"></span></span>
                                            </div>
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
                            <!-- 1. Servis Pendek -->
                            <tr>
                                <td class="p-2.5 border border-slate-200">
                                    <div class="font-extrabold text-slate-900">1. Servis Pendek</div>
                                    <template x-if="selectedRecord.trialsServisPendek && selectedRecord.trialsServisPendek.length > 0">
                                        <div class="mt-1.5 flex flex-wrap gap-1 items-center">
                                            <span class="text-[9px] text-slate-400 font-semibold mr-0.5">20 Coba:</span>
                                            <template x-for="(tr, tri) in selectedRecord.trialsServisPendek" :key="'spt-'+tri">
                                                <span class="inline-flex items-center justify-center w-4 h-4 rounded-md bg-purple-50 text-purple-800 text-[8px] font-extrabold border border-purple-200 shadow-2xs" :title="'Kesempatan #' + (tri+1) + ': ' + tr" x-text="tr"></span>
                                            </template>
                                        </div>
                                    </template>
                                </td>
                                <td class="p-2 border border-slate-200 text-center font-black text-purple-900 text-sm align-middle" x-text="selectedRecord.skorServisPendek"></td>
                                <td class="p-2 border border-slate-200 text-center align-middle">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold" :class="getCategoryBadgeClass(selectedRecord.normaServisPendek)" x-text="selectedRecord.normaServisPendek"></span>
                                </td>
                            </tr>
                            <!-- 2. Servis Panjang -->
                            <tr>
                                <td class="p-2.5 border border-slate-200">
                                    <div class="font-extrabold text-slate-900">2. Servis Panjang</div>
                                    <template x-if="selectedRecord.trialsServisPanjang && selectedRecord.trialsServisPanjang.length > 0">
                                        <div class="mt-1.5 flex flex-wrap gap-1 items-center">
                                            <span class="text-[9px] text-slate-400 font-semibold mr-0.5">20 Coba:</span>
                                            <template x-for="(tr, tri) in selectedRecord.trialsServisPanjang" :key="'sjt-'+tri">
                                                <span class="inline-flex items-center justify-center w-4 h-4 rounded-md bg-indigo-50 text-indigo-800 text-[8px] font-extrabold border border-indigo-200 shadow-2xs" :title="'Kesempatan #' + (tri+1) + ': ' + tr" x-text="tr"></span>
                                            </template>
                                        </div>
                                    </template>
                                </td>
                                <td class="p-2 border border-slate-200 text-center font-black text-indigo-900 text-sm align-middle" x-text="selectedRecord.skorServisPanjang"></td>
                                <td class="p-2 border border-slate-200 text-center align-middle">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold" :class="getCategoryBadgeClass(selectedRecord.normaServisPanjang)" x-text="selectedRecord.normaServisPanjang"></span>
                                </td>
                            </tr>
                            <!-- 3. Pukulan Lob -->
                            <tr>
                                <td class="p-2.5 border border-slate-200">
                                    <div class="font-extrabold text-slate-900">3. Pukulan Lob</div>
                                    <template x-if="selectedRecord.trialsLob && selectedRecord.trialsLob.length > 0">
                                        <div class="mt-1.5 flex flex-wrap gap-1 items-center">
                                            <span class="text-[9px] text-slate-400 font-semibold mr-0.5">20 Coba:</span>
                                            <template x-for="(tr, tri) in selectedRecord.trialsLob" :key="'lobt-'+tri">
                                                <span class="inline-flex items-center justify-center w-4 h-4 rounded-md bg-blue-50 text-blue-800 text-[8px] font-extrabold border border-blue-200 shadow-2xs" :title="'Kesempatan #' + (tri+1) + ': ' + tr" x-text="tr"></span>
                                            </template>
                                        </div>
                                    </template>
                                </td>
                                <td class="p-2 border border-slate-200 text-center font-black text-blue-900 text-sm align-middle" x-text="selectedRecord.skorLob"></td>
                                <td class="p-2 border border-slate-200 text-center align-middle">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold" :class="getCategoryBadgeClass(selectedRecord.normaLob)" x-text="selectedRecord.normaLob"></span>
                                </td>
                            </tr>
                            <!-- 4. Pukulan Smash -->
                            <tr>
                                <td class="p-2.5 border border-slate-200">
                                    <div class="font-extrabold text-slate-900">4. Pukulan Smash</div>
                                    <template x-if="selectedRecord.trialsSmash && selectedRecord.trialsSmash.length > 0">
                                        <div class="mt-1.5 flex flex-wrap gap-1 items-center">
                                            <span class="text-[9px] text-slate-400 font-semibold mr-0.5">20 Coba:</span>
                                            <template x-for="(tr, tri) in selectedRecord.trialsSmash" :key="'smasht-'+tri">
                                                <span class="inline-flex items-center justify-center w-4 h-4 rounded-md bg-rose-50 text-rose-800 text-[8px] font-extrabold border border-rose-200 shadow-2xs" :title="'Kesempatan #' + (tri+1) + ': ' + tr" x-text="tr"></span>
                                            </template>
                                        </div>
                                    </template>
                                </td>
                                <td class="p-2 border border-slate-200 text-center font-black text-rose-900 text-sm align-middle" x-text="selectedRecord.skorSmash"></td>
                                <td class="p-2 border border-slate-200 text-center align-middle">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold" :class="getCategoryBadgeClass(selectedRecord.normaSmash)" x-text="selectedRecord.normaSmash"></span>
                                </td>
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

    <!-- JAVASCRIPT APP LOGIC (ALPINE.JS CONTROLLER WITH MYSQL BACKEND SYNC) -->
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

                // Database & Multi-Device Sync States
                dbConnected: true,
                dbDriver: '{{ config('database.default', 'mysql') }}',
                isSyncing: false,
                isSaving: false,

                // Accordion Expand States for 20-attempts grids
                expandedTechniques: {
                    sp: true,
                    sj: true,
                    lob: true,
                    smash: true
                },

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

                // Researchers State
                researchers: [],

                // Materis State
                materis: [],

                // Videos State
                videos: [],

                // FAQs State
                faqs: [],

                // Form Object with 20 individual trial attempts per technique
                form: {
                    id: null,
                    nama: '',
                    nim: '',
                    gender: 'L',
                    kelas: 'Palembang A 2024',
                    sekolah: '',
                    tanggal: new Date().toISOString().split('T')[0],
                    penguji: 'Silvi Aryanti, M.Pd.',
                    trialsServisPendek: Array(20).fill(0),
                    skorServisPendek: 0,
                    trialsServisPanjang: Array(20).fill(0),
                    skorServisPanjang: 0,
                    trialsLob: Array(20).fill(0),
                    skorLob: 0,
                    trialsSmash: Array(20).fill(0),
                    skorSmash: 0
                },

                materiForm: { id: null, judul: '', kategori: 'Servis Pendek', photo: '', deskripsi: '', petunjuk: '' },
                videoForm: { id: null, judul: '', kategori: 'Teknik Dasar', url: '', deskripsi: '' },
                faqForm: { id: null, q: '', a: '' },
                researcherForm: { id: null, name: '', role: '', photo: '/images/logo1.png', isLeader: false },

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

                // TOGGLE & TRIAL INPUT HELPERS
                toggleTechniqueExpand(tech) {
                    this.expandedTechniques[tech] = !this.expandedTechniques[tech];
                },

                onTrialInput(tech, index, event) {
                    let val = parseInt(event.target.value);
                    if (isNaN(val) || val < 0) val = 0;
                    if (val > 5) val = 5;

                    const arrayKey = tech === 'sp' ? 'trialsServisPendek' : (tech === 'sj' ? 'trialsServisPanjang' : (tech === 'lob' ? 'trialsLob' : 'trialsSmash'));
                    const scoreKey = tech === 'sp' ? 'skorServisPendek' : (tech === 'sj' ? 'skorServisPanjang' : (tech === 'lob' ? 'skorLob' : 'skorSmash'));

                    if (!Array.isArray(this.form[arrayKey])) {
                        this.form[arrayKey] = Array(20).fill(0);
                    }

                    this.form[arrayKey][index] = val;
                    this.form[scoreKey] = this.form[arrayKey].reduce((sum, item) => sum + (parseInt(item) || 0), 0);

                    // Auto-advance to next input field if single character typed
                    if (event.data && index < 19) {
                        const nextId = 'input-' + tech + '-' + (index + 1);
                        const nextEl = document.getElementById(nextId);
                        if (nextEl) {
                            nextEl.focus();
                            nextEl.select();
                        }
                    }
                },

                quickFillTrials(tech, val) {
                    const arrayKey = tech === 'sp' ? 'trialsServisPendek' : (tech === 'sj' ? 'trialsServisPanjang' : (tech === 'lob' ? 'trialsLob' : 'trialsSmash'));
                    const scoreKey = tech === 'sp' ? 'skorServisPendek' : (tech === 'sj' ? 'skorServisPanjang' : (tech === 'lob' ? 'skorLob' : 'skorSmash'));

                    this.form[arrayKey] = Array(20).fill(val);
                    this.form[scoreKey] = val * 20;
                },

                resetTrials(tech) {
                    this.quickFillTrials(tech, 0);
                },

                // LOAD ALL DATA (MYSQL WITH LOCAL STORAGE FALLBACK)
                async loadAllData(manual = false) {
                    this.isSyncing = true;
                    try {
                        const res = await fetch('/api/app-data', {
                            headers: { 'Accept': 'application/json' }
                        });
                        if (!res.ok) throw new Error('Koneksi server gagal');
                        const data = await res.json();

                        if (data.records) this.records = data.records;
                        if (data.schools) this.schools = data.schools;
                        if (data.materis) this.materis = data.materis;
                        if (data.videos) this.videos = data.videos;
                        if (data.faqs) this.faqs = data.faqs;
                        if (data.researchers) this.researchers = data.researchers;
                        if (data.appSettings && Object.keys(data.appSettings).length > 0) {
                            this.appSettings = { ...this.appSettings, ...data.appSettings };
                        }
                        if (data.dbDriver) this.dbDriver = data.dbDriver;

                        this.dbConnected = true;
                        this.saveToLocalStorageFallback();

                        if (manual) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sinkronisasi Berhasil!',
                                text: 'Data terbaru berhasil dimuat dari database ' + (this.dbDriver === 'sqlite' ? 'SQLite' : 'MySQL') + '.',
                                confirmButtonColor: '#7e22ce',
                                timer: 1500,
                                customClass: { popup: 'rounded-3xl' }
                            });
                        }
                    } catch (e) {
                        console.warn('Gagal memuat dari MySQL, menggunakan cache lokal:', e);
                        this.dbConnected = false;
                        this.loadFromLocalStorageFallback();
                    } finally {
                        this.isSyncing = false;
                    }
                },

                saveToLocalStorageFallback() {
                    try {
                        localStorage.setItem('sabawa_records', JSON.stringify(this.records));
                        localStorage.setItem('sabawa_settings', JSON.stringify(this.appSettings));
                        localStorage.setItem('sabawa_researchers', JSON.stringify(this.researchers));
                        localStorage.setItem('sabawa_materis', JSON.stringify(this.materis));
                        localStorage.setItem('sabawa_videos', JSON.stringify(this.videos));
                        localStorage.setItem('sabawa_faqs', JSON.stringify(this.faqs));
                        localStorage.setItem('sabawa_schools', JSON.stringify(this.schools));
                    } catch (e) {}
                },

                loadFromLocalStorageFallback() {
                    const r = localStorage.getItem('sabawa_records');
                    if (r) this.records = JSON.parse(r);
                    const s = localStorage.getItem('sabawa_settings');
                    if (s) this.appSettings = JSON.parse(s);
                    const sch = localStorage.getItem('sabawa_schools');
                    if (sch) this.schools = JSON.parse(sch);
                    const m = localStorage.getItem('sabawa_materis');
                    if (m) this.materis = JSON.parse(m);
                    const v = localStorage.getItem('sabawa_videos');
                    if (v) this.videos = JSON.parse(v);
                    const f = localStorage.getItem('sabawa_faqs');
                    if (f) this.faqs = JSON.parse(f);
                    const res = localStorage.getItem('sabawa_researchers');
                    if (res) this.researchers = JSON.parse(res);
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

                // NORMA CALCULATION FUNCTIONS
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

                // RECORD CRUD (SAVE TO MYSQL)
                async saveRecord() {
                    this.isSaving = true;

                    // Compute accumulated totals from the 20-attempts arrays
                    const spSum = (this.form.trialsServisPendek || []).reduce((a, b) => (a || 0) + (parseInt(b) || 0), 0);
                    const sjSum = (this.form.trialsServisPanjang || []).reduce((a, b) => (a || 0) + (parseInt(b) || 0), 0);
                    const lobSum = (this.form.trialsLob || []).reduce((a, b) => (a || 0) + (parseInt(b) || 0), 0);
                    const smashSum = (this.form.trialsSmash || []).reduce((a, b) => (a || 0) + (parseInt(b) || 0), 0);

                    this.form.skorServisPendek = spSum;
                    this.form.skorServisPanjang = sjSum;
                    this.form.skorLob = lobSum;
                    this.form.skorSmash = smashSum;

                    const payload = {
                        id: this.form.id,
                        nama: this.form.nama,
                        nim: this.form.nim,
                        jenisKelamin: this.form.gender,
                        kelas: this.form.kelas,
                        sekolah: this.form.sekolah,
                        tanggal: this.form.tanggal,
                        penguji: this.form.penguji,
                        trialsServisPendek: this.form.trialsServisPendek,
                        skorServisPendek: spSum,
                        trialsServisPanjang: this.form.trialsServisPanjang,
                        skorServisPanjang: sjSum,
                        trialsLob: this.form.trialsLob,
                        skorLob: lobSum,
                        trialsSmash: this.form.trialsSmash,
                        skorSmash: smashSum
                    };

                    try {
                        const res = await fetch('/api/assessments', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        if (!res.ok) throw new Error('Gagal menyimpan data ke MySQL server.');
                        const result = await res.json();

                        if (result.success && result.record) {
                            const idx = this.records.findIndex(r => r.id === result.record.id);
                            if (idx !== -1) {
                                this.records[idx] = result.record;
                            } else {
                                this.records.unshift(result.record);
                            }
                        }

                        this.saveToLocalStorageFallback();

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Simpan ke MySQL!',
                            text: 'Data tes 20x percobaan telah tersimpan di database.',
                            confirmButtonColor: '#7e22ce',
                            timer: 2000,
                            timerProgressBar: true,
                            customClass: { popup: 'rounded-3xl' }
                        });

                        this.resetForm();
                        this.activeTab = 'data';
                    } catch (err) {
                        console.error('Error simpan data:', err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: err.message || 'Gagal menyimpan data.',
                            confirmButtonColor: '#7e22ce'
                        });
                    } finally {
                        this.isSaving = false;
                    }
                },

                resetForm() {
                    this.form = {
                        id: null,
                        nama: '',
                        nim: '',
                        gender: 'L',
                        kelas: 'Palembang A 2024',
                        sekolah: '',
                        tanggal: new Date().toISOString().split('T')[0],
                        penguji: 'Silvi Aryanti, M.Pd.',
                        trialsServisPendek: Array(20).fill(0),
                        skorServisPendek: 0,
                        trialsServisPanjang: Array(20).fill(0),
                        skorServisPanjang: 0,
                        trialsLob: Array(20).fill(0),
                        skorLob: 0,
                        trialsSmash: Array(20).fill(0),
                        skorSmash: 0
                    };
                },

                editRecord(item) {
                    this.form = {
                        id: item.id,
                        nama: item.nama,
                        nim: item.nim,
                        gender: item.jenisKelamin,
                        kelas: item.kelas,
                        sekolah: item.sekolah || '',
                        tanggal: item.tanggal,
                        penguji: item.penguji,
                        trialsServisPendek: (item.trialsServisPendek && item.trialsServisPendek.length === 20)
                            ? [...item.trialsServisPendek]
                            : Array(20).fill(Math.round((item.skorServisPendek || 0) / 20)),
                        skorServisPendek: item.skorServisPendek ?? 0,
                        trialsServisPanjang: (item.trialsServisPanjang && item.trialsServisPanjang.length === 20)
                            ? [...item.trialsServisPanjang]
                            : Array(20).fill(Math.round((item.skorServisPanjang || 0) / 20)),
                        skorServisPanjang: item.skorServisPanjang ?? 0,
                        trialsLob: (item.trialsLob && item.trialsLob.length === 20)
                            ? [...item.trialsLob]
                            : Array(20).fill(Math.round((item.skorLob || 0) / 20)),
                        skorLob: item.skorLob ?? 0,
                        trialsSmash: (item.trialsSmash && item.trialsSmash.length === 20)
                            ? [...item.trialsSmash]
                            : Array(20).fill(Math.round((item.skorSmash || 0) / 20)),
                        skorSmash: item.skorSmash ?? 0
                    };
                    this.activeTab = 'form';
                },

                deleteRecord(id) {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: 'Data tes ini akan dihapus secara permanen dari MySQL!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7e22ce',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal',
                        customClass: { popup: 'rounded-3xl' }
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                await fetch(`/api/assessments/${id}`, { method: 'DELETE' });
                                this.records = this.records.filter(r => r.id !== id);
                                this.saveToLocalStorageFallback();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: 'Data tes telah berhasil dihapus dari database.',
                                    confirmButtonColor: '#7e22ce',
                                    timer: 1500,
                                    customClass: { popup: 'rounded-3xl' }
                                });
                            } catch (e) {
                                Swal.fire({ icon: 'error', title: 'Gagal Hapus', text: e.message });
                            }
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
                async saveMateri() {
                    try {
                        const res = await fetch('/api/materis', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify(this.materiForm)
                        });
                        const data = await res.json();
                        if (data.materi) {
                            const idx = this.materis.findIndex(m => m.id === data.materi.id);
                            if (idx !== -1) this.materis[idx] = data.materi;
                            else this.materis.push(data.materi);
                        }
                        this.saveToLocalStorageFallback();
                        this.showMateriModal = false;
                        Swal.fire({ icon: 'success', title: 'Materi Tersimpan ke MySQL', confirmButtonColor: '#7e22ce', timer: 1500, customClass: { popup: 'rounded-3xl' } });
                    } catch (e) {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: e.message });
                    }
                },
                async deleteMateri(id) {
                    Swal.fire({
                        title: 'Hapus Materi ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7e22ce',
                        confirmButtonText: 'Ya, hapus'
                    }).then(async (res) => {
                        if (res.isConfirmed) {
                            await fetch(`/api/materis/${id}`, { method: 'DELETE' });
                            this.materis = this.materis.filter(m => m.id !== id);
                            this.saveToLocalStorageFallback();
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
                async saveVideo() {
                    try {
                        const res = await fetch('/api/videos', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify(this.videoForm)
                        });
                        const data = await res.json();
                        if (data.video) {
                            const idx = this.videos.findIndex(v => v.id === data.video.id);
                            if (idx !== -1) this.videos[idx] = data.video;
                            else this.videos.push(data.video);
                        }
                        this.saveToLocalStorageFallback();
                        this.showVideoModal = false;
                        Swal.fire({ icon: 'success', title: 'Video Tersimpan ke MySQL', confirmButtonColor: '#7e22ce', timer: 1500, customClass: { popup: 'rounded-3xl' } });
                    } catch (e) {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: e.message });
                    }
                },
                async deleteVideo(id) {
                    Swal.fire({
                        title: 'Hapus Video ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7e22ce',
                        confirmButtonText: 'Ya, hapus'
                    }).then(async (res) => {
                        if (res.isConfirmed) {
                            await fetch(`/api/videos/${id}`, { method: 'DELETE' });
                            this.videos = this.videos.filter(v => v.id !== id);
                            this.saveToLocalStorageFallback();
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
                async saveFaq() {
                    try {
                        const res = await fetch('/api/faqs', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify(this.faqForm)
                        });
                        const data = await res.json();
                        if (data.faq) {
                            const idx = this.faqs.findIndex(f => f.id === data.faq.id);
                            if (idx !== -1) this.faqs[idx] = data.faq;
                            else this.faqs.push(data.faq);
                        }
                        this.saveToLocalStorageFallback();
                        this.showFaqModal = false;
                        Swal.fire({ icon: 'success', title: 'FAQ Tersimpan ke MySQL', confirmButtonColor: '#7e22ce', timer: 1500, customClass: { popup: 'rounded-3xl' } });
                    } catch (e) {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: e.message });
                    }
                },
                async deleteFaq(id) {
                    Swal.fire({
                        title: 'Hapus FAQ ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7e22ce',
                        confirmButtonText: 'Ya, hapus'
                    }).then(async (res) => {
                        if (res.isConfirmed) {
                            await fetch(`/api/faqs/${id}`, { method: 'DELETE' });
                            this.faqs = this.faqs.filter(f => f.id !== id);
                            this.saveToLocalStorageFallback();
                            Swal.fire({ icon: 'success', title: 'FAQ Terhapus', confirmButtonColor: '#7e22ce', timer: 1500 });
                        }
                    });
                },

                // CRUD RESEARCHERS
                openAddResearcherModal() {
                    this.researcherForm = { id: null, name: '', role: '', photo: '/images/logo1.png', isLeader: false };
                    this.showResearcherModal = true;
                },
                openEditResearcherModal(person) {
                    this.researcherForm = { ...person };
                    this.showResearcherModal = true;
                },
                async saveResearcher() {
                    try {
                        const res = await fetch('/api/researchers', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify(this.researcherForm)
                        });
                        const data = await res.json();
                        if (data.researcher) {
                            const idx = this.researchers.findIndex(r => r.id === data.researcher.id);
                            if (idx !== -1) this.researchers[idx] = data.researcher;
                            else this.researchers.push(data.researcher);
                        }
                        this.saveToLocalStorageFallback();
                        this.showResearcherModal = false;
                        Swal.fire({ icon: 'success', title: 'Data Peneliti Tersimpan ke MySQL', confirmButtonColor: '#7e22ce', timer: 1500, customClass: { popup: 'rounded-3xl' } });
                    } catch (e) {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: e.message });
                    }
                },
                async deleteResearcher(id) {
                    Swal.fire({
                        title: 'Hapus Peneliti ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7e22ce',
                        confirmButtonText: 'Ya, hapus'
                    }).then(async (res) => {
                        if (res.isConfirmed) {
                            await fetch(`/api/researchers/${id}`, { method: 'DELETE' });
                            this.researchers = this.researchers.filter(r => r.id !== id);
                            this.saveToLocalStorageFallback();
                            Swal.fire({ icon: 'success', title: 'Data Peneliti Terhapus', confirmButtonColor: '#7e22ce', timer: 1500 });
                        }
                    });
                },

                // SCHOOLS CRUD (MYSQL)
                async saveSchool() {
                    if (!this.schoolForm.nama.trim()) return;

                    try {
                        const res = await fetch('/api/schools', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify({
                                id: this.schoolForm.id,
                                nama: this.schoolForm.nama.trim()
                            })
                        });
                        const data = await res.json();
                        if (data.school) {
                            const idx = this.schools.findIndex(s => s.id === data.school.id);
                            if (idx !== -1) this.schools[idx] = data.school;
                            else this.schools.push(data.school);
                        }

                        this.saveToLocalStorageFallback();
                        this.schoolForm.id = null;
                        this.schoolForm.nama = '';
                        this.showSchoolModal = false;

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Simpan Sekolah!',
                            confirmButtonColor: '#7e22ce',
                            timer: 1500
                        });
                    } catch (e) {
                        Swal.fire({ icon: 'error', title: 'Gagal Simpan', text: e.message });
                    }
                },

                editSchool(school) {
                    this.schoolForm = { id: school.id, nama: school.nama };
                    this.showSchoolModal = true;
                },

                async deleteSchool(id) {
                    Swal.fire({
                        title: 'Hapus Sekolah ini?',
                        text: 'Semua atlet yang terhubung dengan sekolah ini tidak akan terhapus, tetapi rincian sekolah mereka akan kosong.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7e22ce',
                        confirmButtonText: 'Ya, hapus'
                    }).then(async (res) => {
                        if (res.isConfirmed) {
                            await fetch(`/api/schools/${id}`, { method: 'DELETE' });
                            this.schools = this.schools.filter(s => s.id !== id);
                            this.saveToLocalStorageFallback();
                            Swal.fire({ icon: 'success', title: 'Sekolah Terhapus', confirmButtonColor: '#7e22ce', timer: 1500 });
                        }
                    });
                },

                // APP SETTINGS
                async saveAppSettings() {
                    try {
                        const res = await fetch('/api/settings', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify(this.appSettings)
                        });
                        const data = await res.json();
                        if (data.appSettings) {
                            this.appSettings = { ...this.appSettings, ...data.appSettings };
                        }
                        this.saveToLocalStorageFallback();

                        Swal.fire({
                            icon: 'success',
                            title: 'Pengaturan Tersimpan ke MySQL!',
                            text: 'Icon logo & identitas aplikasi berhasil diperbarui.',
                            confirmButtonColor: '#7e22ce',
                            timer: 2000,
                            customClass: { popup: 'rounded-3xl' }
                        });
                    } catch (e) {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: e.message });
                    }
                },

                get filteredRecords() {
                    return this.records.filter(r => {
                        const matchQuery = !this.searchQuery || (r.nama && r.nama.toLowerCase().includes(this.searchQuery.toLowerCase())) || (r.nim && r.nim.includes(this.searchQuery));
                        const matchCat = !this.filterCategory || r.evaluasiTotal === this.filterCategory;
                        const matchSchool = !this.filterSchool || r.sekolah === this.filterSchool;
                        return matchQuery && matchCat && matchSchool;
                    });
                },

                getAverageScore() {
                    const items = this.filteredRecords;
                    if (items.length === 0) return '0';
                    const sum = items.reduce((acc, r) => acc + (parseInt(r.skorServisPendek) || 0) + (parseInt(r.skorServisPanjang) || 0) + (parseInt(r.skorLob) || 0) + (parseInt(r.skorSmash) || 0), 0);
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
                    let csv = 'Nama,NIM,Jenis Kelamin,Kelas,Sekolah,Tanggal,Penguji,Servis Pendek Total,Norma SP,Rincian SP (20x),Servis Panjang Total,Norma SJ,Rincian SJ (20x),Lob Total,Norma Lob,Rincian Lob (20x),Smash Total,Norma Smash,Rincian Smash (20x),Evaluasi Total\n';
                    items.forEach(r => {
                        const spStr = (r.trialsServisPendek && r.trialsServisPendek.length) ? r.trialsServisPendek.join('-') : '';
                        const sjStr = (r.trialsServisPanjang && r.trialsServisPanjang.length) ? r.trialsServisPanjang.join('-') : '';
                        const lobStr = (r.trialsLob && r.trialsLob.length) ? r.trialsLob.join('-') : '';
                        const smashStr = (r.trialsSmash && r.trialsSmash.length) ? r.trialsSmash.join('-') : '';

                        csv += `"${r.nama}","${r.nim}","${r.jenisKelamin}","${r.kelas}","${r.sekolah || '-'}","${r.tanggal}","${r.penguji}",${r.skorServisPendek || 0},"${r.normaServisPendek}","${spStr}",${r.skorServisPanjang || 0},"${r.normaServisPanjang}","${sjStr}",${r.skorLob || 0},"${r.normaLob}","${lobStr}",${r.skorSmash || 0},"${r.normaSmash}","${smashStr}","${r.evaluasiTotal}"\n`;
                    });
                    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.setAttribute('download', 'SA_BAWA_Rekap_20x_Penilaian.csv');
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
