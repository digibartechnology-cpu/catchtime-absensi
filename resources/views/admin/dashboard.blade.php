<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | CatchTime</title>
    <link rel="icon" href="{{ asset('images/Logo_icon.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Background Mint Halus Sesuai Mockup */
        .bg-dashboard-mint {
            background-color: #e6f7f1;
        }
    </style>
</head>
<body class="bg-dashboard-mint text-gray-800 font-sans selection:bg-blue-600 selection:text-white" x-data="{ sidebarOpen: false, logoutModalOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-[260px] bg-white shadow-2xl transform transition-transform duration-300 ease-in-out border-r border-gray-100 flex flex-col h-full md:relative md:translate-x-0">
            
            <div class="p-6 flex justify-between items-center">
                <h2 class="text-2xl font-extrabold text-blue-800 tracking-tight">Menu</h2>
                <button @click="sidebarOpen = false" class="md:hidden text-gray-400 hover:text-red-600 text-3xl font-bold transition-colors focus:outline-none">&times;</button>
            </div>

            <nav class="flex-1 px-4 space-y-2 overflow-y-auto mt-2">
                <!-- Menu Aktif (Dashboard) -->
                <a href="/admin/dashboard" class="block px-4 py-3.5 bg-[#f0f4ff] text-blue-700 rounded-xl font-bold border-l-[4px] border-blue-600 shadow-sm text-base">
                    Dashboard Overview
                </a>
                
                <!-- Menu Inaktif -->
                <a href="/admin/karyawan" class="block px-4 py-3.5 text-gray-500 hover:bg-gray-50 hover:text-blue-700 rounded-xl font-semibold transition-colors text-base">
                    Data Karyawan
                </a>
                <a href="/admin/absensi" class="block px-4 py-3.5 text-gray-500 hover:bg-gray-50 hover:text-blue-700 rounded-xl font-semibold transition-colors text-base">
                    Data Absensi
                </a>
                <a href="/admin/cuti" class="block px-4 py-3.5 text-gray-500 hover:bg-gray-50 hover:text-blue-700 rounded-xl font-semibold transition-colors text-base">
                    Data Cuti
                </a>
                <a href="/admin/buku-tamu" class="block px-4 py-3.5 text-gray-500 hover:bg-gray-50 hover:text-blue-700 rounded-xl font-semibold transition-colors text-base">
                    Buku Tamu
                </a>
            </nav>

            <!-- Tombol Keluar Sistem -->
            <div class="p-6 mt-auto">
                <button @click="logoutModalOpen = true" type="button" class="w-full flex items-center justify-center gap-2 px-4 py-3 text-red-500 bg-white border border-red-500 hover:bg-red-50 active:bg-red-100 rounded-xl transition-all duration-200 font-bold text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Keluar Sistem</span>
                </button>
            </div>
        </aside>

        <!-- OVERLAY GELAP UNTUK MOBILE -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 backdrop-blur-sm md:hidden" x-transition.opacity style="display: none;"></div>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 overflow-y-auto w-full relative">
            <div class="min-h-screen p-6 md:p-10">
                <div class="max-w-6xl mx-auto">
                    
                    <!-- TOP BRANDING -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 mb-10">
                        <button @click="sidebarOpen = true" class="md:hidden p-2 -ml-2 text-gray-600 hover:bg-white hover:text-blue-700 rounded-xl transition-colors shadow-sm bg-white/50 focus:outline-none">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        <!-- LOGO DIGIBAR (DIBESARKAN) -->
                        <img src="{{ asset('images/Logo_DigiBAR.png') }}" alt="Logo DigiBAR" class="h-16 sm:h-20 md:h-24 w-auto drop-shadow-sm">
                        
                        <div class="mt-2 sm:mt-0 flex-1">
                            <!-- TEKS LOGO (DIBESARKAN) -->
                            <h1 class="text-2xl sm:text-3xl md:text-4xl font-black text-red-800 tracking-tight flex flex-wrap items-baseline gap-2">
                                DigiBAR Group <span class="font-medium text-gray-500 text-base sm:text-lg md:text-xl">| (Human Resource Management)</span>
                            </h1>
                            <p class="text-gray-500 text-sm sm:text-base md:text-lg font-bold mt-1">Digital Attendance and Guest Book</p>
                        </div>
                    </div>

                    <!-- HEADER PAGE TITLE -->
                    <header class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-4">
                        <div>
                            <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight">Dashboard Overview</h1>
                            <p class="text-gray-500 mt-2 font-medium text-base">Ringkasan data hari ini secara real-time.</p>
                        </div>
                        <div class="bg-white px-6 py-2.5 rounded-full shadow-sm flex items-center gap-3 self-start md:self-auto border border-gray-100">
                            <div class="w-2.5 h-2.5 bg-blue-600 rounded-full animate-pulse"></div>
                            <span class="text-sm text-gray-600 font-bold">Halo, <strong class="text-blue-600">Admin</strong></span>
                        </div>
                    </header>

                    <!-- GRID STATISTIK -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
                        
                        <!-- Kartu 1: Total Karyawan (Biru) -->
                        <div class="bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
                            <div>
                                <h3 class="text-gray-400 text-xs md:text-sm font-extrabold uppercase tracking-wider mb-2">Total Karyawan</h3>
                                <p class="text-4xl md:text-5xl font-black text-gray-900">{{ $totalKaryawan ?? 0 }}</p>
                            </div>
                            <div class="bg-blue-50 w-14 h-14 md:w-16 md:h-16 rounded-2xl text-blue-500 flex items-center justify-center">
                                <svg class="w-8 h-8 md:w-9 md:h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                        </div>

                        <!-- Kartu 2: Absen Hari Ini (Hijau) -->
                        <div class="bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
                            <div>
                                <h3 class="text-gray-400 text-xs md:text-sm font-extrabold uppercase tracking-wider mb-2">Absen Hari Ini</h3>
                                <p class="text-4xl md:text-5xl font-black text-gray-900">{{ $absenHariIni ?? 0 }}</p>
                            </div>
                            <div class="bg-green-50 w-14 h-14 md:w-16 md:h-16 rounded-2xl text-green-500 flex items-center justify-center">
                                <svg class="w-8 h-8 md:w-9 md:h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>

                        <!-- Kartu 3: Tamu Hari Ini (Ungu) -->
                        <div class="bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
                            <div>
                                <h3 class="text-gray-400 text-xs md:text-sm font-extrabold uppercase tracking-wider mb-2">Tamu Hari Ini</h3>
                                <p class="text-4xl md:text-5xl font-black text-gray-900">{{ $tamuHariIni ?? 0 }}</p>
                            </div>
                            <div class="bg-purple-50 w-14 h-14 md:w-16 md:h-16 rounded-2xl text-purple-500 flex items-center justify-center">
                                <svg class="w-8 h-8 md:w-9 md:h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        </div>

                        <!-- Kartu 4: Pengajuan Cuti (Kuning) -->
                        <div class="bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
                            <div>
                                <h3 class="text-gray-400 text-xs md:text-sm font-extrabold uppercase tracking-wider mb-2">Pengajuan Cuti</h3>
                                <p class="text-4xl md:text-5xl font-black text-gray-900">{{ $pengajuanCuti ?? 0 }}</p>
                            </div>
                            <div class="bg-yellow-50 w-14 h-14 md:w-16 md:h-16 rounded-2xl text-yellow-500 flex items-center justify-center">
                                <svg class="w-8 h-8 md:w-9 md:h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                        </div>
                        
                    </div>

                    <!-- BANNER WELCOME PREMIUM -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-8 md:p-12 rounded-3xl shadow-xl text-white relative overflow-hidden">
                        
                        <!-- Pill "System Ready" -->
                        <div class="inline-block border border-white/30 rounded-full px-5 py-2 mb-6 backdrop-blur-sm">
                            <span class="text-xs font-extrabold tracking-widest uppercase">System Ready</span>
                        </div>
                        
                        <h2 class="text-3xl md:text-4xl font-extrabold mb-4 tracking-tight">Selamat Datang di Portal Admin</h2>
                        
                        <p class="text-blue-100 text-base md:text-lg font-medium leading-relaxed max-w-3xl">
                            Kelola data karyawan, verifikasi absensi instan, pantau pendaftaran buku tamu harian, dan kelola pengajuan izin cuti dengan mudah dalam satu sistem terintegrasi.
                        </p>
                    </div>

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