<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard DigiBAR Digital MSDM</title>
    <link rel="icon" href="{{ asset('images/Logo_icon.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Animasi Background Gradient Halus khas DigiBAR */
        .bg-animated-gradient {
            background: linear-gradient(-45deg, #f8fafc, #fee2e2, #eff6ff, #f8fafc);
            background-size: 400% 400%;
            animation: gradientBG 12s ease infinite;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body class="bg-animated-gradient text-gray-800 font-sans selection:bg-red-500 selection:text-white" x-data="{ sidebarOpen: false, logoutModalOpen: false }">

    <div class="flex h-screen overflow-hidden">
        <!-- SIDEBAR -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-2xl transform transition-transform duration-300 ease-in-out border-r border-gray-100 flex flex-col h-full md:relative md:translate-x-0">
            
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-xl font-extrabold text-red-700 tracking-tight">DigiBAR MSDM</h2>
                <button @click="sidebarOpen = false" class="md:hidden text-gray-400 hover:text-red-600 text-3xl font-bold transition-colors focus:outline-none">&times;</button>
            </div>

            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="/admin/dashboard" class="block px-4 py-3 bg-red-50 text-red-700 rounded-xl font-bold border-l-4 border-red-600 shadow-sm">
                    Dashboard Overview
                </a>
                <a href="/admin/karyawan" class="block px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-red-600 rounded-xl font-semibold transition-colors">
                    Data Karyawan
                </a>
                <a href="/admin/absensi" class="block px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-red-600 rounded-xl font-semibold transition-colors">
                    Data Absensi
                </a>
                <a href="/admin/cuti" class="block px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-red-600 rounded-xl font-semibold transition-colors flex justify-between items-center">
                    Data Cuti
                </a>
                <a href="/admin/buku-tamu" class="block px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-red-600 rounded-xl font-semibold transition-colors">
                    Buku Tamu
                </a>
            </nav>

            <div class="p-4 border-t border-gray-100 mt-auto">
                <button @click="logoutModalOpen = true" type="button" class="w-full flex items-center justify-center gap-3 px-4 py-3 text-red-600 bg-white border border-red-200 hover:bg-red-50 active:bg-red-100 rounded-xl transition-all duration-200 font-bold shadow-sm hover:shadow">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Keluar Sistem</span>
                </button>
            </div>
        </aside>

        <!-- OVERLAY GELAP -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 backdrop-blur-sm md:hidden" x-transition.opacity style="display: none;"></div>

        <main class="flex-1 overflow-y-auto w-full relative">
            <div class="min-h-screen p-4 sm:p-6 md:p-8">
                <div class="max-w-7xl mx-auto">
                    
                    <!-- HEADER TOP (LOGO & HAMBURGER) -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4 border-b border-gray-200/50 pb-4">
                        <div class="flex items-center gap-3 sm:gap-5">
                            <button @click="sidebarOpen = true" class="md:hidden p-2 -ml-2 text-gray-600 hover:bg-white hover:text-red-700 rounded-xl transition-colors shadow-sm bg-white/50 focus:outline-none">
                                <svg class="w-7 h-7 sm:w-9 sm:h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>

                            <img src="{{ asset('images/Logo_DigiBAR.png') }}" alt="Logo DigiBAR" class="h-10 sm:h-24 w-auto drop-shadow-sm">
                            <div>
                                <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-red-900 tracking-tight">
                                    DigiBAR Group <span class="block sm:inline font-medium text-gray-500 text-sm sm:text-base md:text-lg">| (Human Resource Management)</span>
                                </h1>
                                <p class="text-gray-500 text-xs sm:text-sm mt-0.5 font-medium">Digital Attendance and Guest Book</p>
                            </div>
                        </div>
                    </div>

                    <!-- HEADER PAGE TITLE -->
                    <header class="flex flex-col md:flex-row md:justify-between md:items-center mb-8 gap-4 mt-4">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Dashboard Overview</h1>
                            <p class="text-gray-500 mt-1 font-medium">Ringkasan data hari ini secara real-time.</p>
                        </div>
                        <div class="bg-white/80 backdrop-blur-md px-5 py-2.5 rounded-full shadow-sm border border-gray-100 self-start md:self-auto flex items-center gap-2">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                            </span>
                            <span class="text-sm text-gray-600 font-semibold">Halo, <strong class="text-red-600 font-extrabold">Admin</strong></span>
                        </div>
                    </header>

                    <!-- GRID STATISTIK -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
                        
                        <!-- Kartu Total Karyawan -->
                        <div class="bg-white/90 backdrop-blur-sm p-6 rounded-3xl shadow-sm border border-gray-100 hover:-translate-y-1 hover:shadow-xl hover:shadow-red-100 transition-all duration-300 group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Total Karyawan</h3>
                                    <p class="text-3xl md:text-4xl font-extrabold text-gray-800">{{ $totalKaryawan ?? 0 }}</p>
                                </div>
                                <div class="bg-red-50 p-4 rounded-2xl text-red-500 group-hover:bg-red-500 group-hover:text-white transition-colors duration-300 shadow-sm">
                                    <svg class="w-7 h-7 md:w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Kartu Absen Masuk & Pulang -->
                        <div class="bg-white/90 backdrop-blur-sm p-6 rounded-3xl shadow-sm border border-gray-100 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-100 transition-all duration-300 group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Absen Hari Ini</h3>
                                    <p class="text-3xl md:text-4xl font-extrabold text-gray-800">{{ $absenHariIni ?? 0 }}</p>
                                </div>
                                <div class="bg-blue-50 p-4 rounded-2xl text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition-colors duration-300 shadow-sm">
                                    <svg class="w-7 h-7 md:w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Kartu Tamu Hari Ini -->
                        <div class="bg-white/90 backdrop-blur-sm p-6 rounded-3xl shadow-sm border border-gray-100 hover:-translate-y-1 hover:shadow-xl hover:shadow-purple-100 transition-all duration-300 group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Tamu Hari Ini</h3>
                                    <p class="text-3xl md:text-4xl font-extrabold text-gray-800">{{ $tamuHariIni ?? 0 }}</p>
                                </div>
                                <div class="bg-purple-50 p-4 rounded-2xl text-purple-500 group-hover:bg-purple-500 group-hover:text-white transition-colors duration-300 shadow-sm">
                                    <svg class="w-7 h-7 md:w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Kartu Pengajuan Cuti -->
                        <div class="bg-white/90 backdrop-blur-sm p-6 rounded-3xl shadow-sm border border-gray-100 hover:-translate-y-1 hover:shadow-xl hover:shadow-yellow-100 transition-all duration-300 group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Pengajuan Cuti</h3>
                                    <p class="text-3xl md:text-4xl font-extrabold text-gray-800">{{ $pengajuanCuti ?? 0 }}</p>
                                </div>
                                <div class="bg-yellow-50 p-4 rounded-2xl text-yellow-500 group-hover:bg-yellow-500 group-hover:text-white transition-colors duration-300 shadow-sm">
                                    <svg class="w-7 h-7 md:w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BANNER WELCOME PREMIUM -->
                    <div class="bg-gradient-to-br from-red-600 to-red-800 p-8 md:p-10 rounded-3xl shadow-xl border border-red-500 text-white relative overflow-hidden">
                        <!-- Elemen Dekoratif Banner -->
                        <div class="absolute top-0 right-0 -mt-8 -mr-8 w-48 h-48 bg-white opacity-10 rounded-full blur-2xl"></div>
                        <div class="absolute bottom-0 right-32 -mb-8 w-32 h-32 bg-yellow-400 opacity-20 rounded-full blur-xl"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="bg-white/20 px-3 py-1 rounded-full text-xs font-bold tracking-widest uppercase border border-white/30 backdrop-blur-sm">System Ready</span>
                            </div>
                            <h2 class="text-2xl md:text-4xl font-extrabold mb-3">Selamat Datang di Portal Admin</h2>
                            <p class="text-red-100 text-sm md:text-base font-medium leading-relaxed max-w-2xl">
                                Kelola data karyawan, verifikasi absensi instan, pantau pendaftaran buku tamu harian, dan kelola pengajuan izin cuti dengan mudah dalam satu sistem terintegrasi.
                            </p>
                        </div>
                    </div>
                    <br><br>

                </div>
            </div> 
        </main>
    </div>

    <!-- Modal Logout -->
    <div x-show="logoutModalOpen" 
         class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/60 p-4 backdrop-blur-sm" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div @click.away="logoutModalOpen = false" 
             class="bg-white w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden transform transition-all p-8 text-center border-4 border-red-400"
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 scale-90 translate-y-8" 
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6 text-red-500">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
            </div>
            
            <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Yakin ingin keluar?</h3>
            <p class="text-gray-500 text-sm mb-8 font-medium">Anda akan mengakhiri sesi Portal saat ini.</p>
            
            <div class="flex flex-col gap-3">
                <form action="/logout" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 hover:-translate-y-0.5 text-white font-bold py-3.5 rounded-xl transition-all duration-200 shadow-lg shadow-red-200">
                        Ya, Keluar Sistem
                    </button>
                </form>
                <button @click="logoutModalOpen = false" type="button" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-xl transition duration-200">
                    Batal
                </button>
            </div>
        </div>
    </div>
</body>
</html>