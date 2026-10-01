<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku Tamu | Admin DigiBAR</title>
    <link rel="icon" href="{{ asset('images/Logo_icon.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
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
                <a href="/admin/dashboard" class="block px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-red-600 rounded-xl font-semibold transition-colors">
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
                <a href="/admin/buku-tamu" class="block px-4 py-3 bg-red-50 text-red-700 rounded-xl font-bold border-l-4 border-red-600 shadow-sm">
                    Buku Tamu
                </a>
            </nav>

            <div class="p-4 border-t border-gray-100 mt-auto">
                <button @click="logoutModalOpen = true" type="button" class="w-full flex items-center justify-center gap-3 px-4 py-3 text-red-600 bg-white border border-red-200 hover:bg-red-50 active:bg-red-100 rounded-xl transition-all duration-200 font-bold shadow-sm hover:shadow">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Keluar Sistem</span>
                </button>
            </div>
        </aside>

        <!-- OVERLAY GELAP -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 backdrop-blur-sm md:hidden" x-transition.opacity style="display: none;"></div>

        <main class="flex-1 overflow-y-auto w-full relative">
            <div class="min-h-screen p-4 sm:p-6 md:p-8">
                <div class="max-w-7xl mx-auto">
                    
                    <!-- HEADER TOP -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4 border-b border-gray-200/50 pb-4">
                        <div class="flex items-center gap-3 sm:gap-5">
                            <button @click="sidebarOpen = true" class="md:hidden p-2 -ml-2 text-gray-600 hover:bg-white hover:text-red-700 rounded-xl transition-colors shadow-sm bg-white/50 focus:outline-none">
                                <svg class="w-7 h-7 sm:w-9 sm:h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path></svg>
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
                    <header class="mb-8 mt-4">
                        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Daftar Buku Tamu</h1>
                        <p class="text-gray-500 mt-1 font-medium">Monitor pengunjung yang datang ke kantor secara real-time.</p>
                    </header>

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl relative mb-6 shadow-sm flex items-center gap-3">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="block sm:inline text-sm font-semibold">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl relative mb-6 shadow-sm flex items-center gap-3">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <div>
                                <strong class="font-extrabold">Gagal! </strong>
                                <span class="block sm:inline text-sm font-medium">{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Filter dan Download PDF -->
                    <div class="bg-white/90 backdrop-blur-md p-5 md:p-6 rounded-3xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row items-end justify-between gap-4">
                        <form action="/admin/buku-tamu" method="GET" class="flex flex-col md:flex-row items-end gap-4 w-full">
                            <div class="w-full md:w-auto">
                                <label class="block text-gray-500 text-xs font-bold mb-2 uppercase tracking-wider">Dari Tanggal</label>
                                <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50 rounded-xl text-sm font-medium focus:ring-2 focus:ring-red-500">
                            </div>
                            <div class="w-full md:w-auto">
                                <label class="block text-gray-500 text-xs font-bold mb-2 uppercase tracking-wider">Sampai Tanggal</label>
                                <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50 rounded-xl text-sm font-medium focus:ring-2 focus:ring-red-500">
                            </div>
                            <div class="flex gap-3 w-full md:w-auto">
                                <button type="submit" class="flex-1 md:flex-none bg-gray-800 hover:bg-gray-900 text-white px-8 py-2.5 rounded-xl text-sm transition font-bold shadow-md">Filter</button>
                                @if($startDate && $endDate)
                                <a href="/admin/buku-tamu/download-pdf?start_date={{ $startDate }}&end_date={{ $endDate }}" class="flex-1 md:flex-none bg-red-600 hover:bg-red-700 hover:-translate-y-0.5 text-white px-6 py-2.5 rounded-xl text-sm transition flex items-center justify-center gap-2 font-bold shadow-lg shadow-red-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Cetak PDF
                                </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Tabel Riwayat Tamu -->
                    <div class="bg-white/90 backdrop-blur-md rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-5 border-b border-gray-100 bg-white/50">
                            <h2 class="text-lg font-extrabold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Riwayat Kunjungan
                            </h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[800px]">
                                <thead>
                                    <tr class="bg-gray-50/80 text-gray-500 text-[10px] md:text-xs uppercase tracking-widest border-b border-gray-100 font-bold">
                                        <th class="p-4 pl-6">No</th>
                                        <th class="p-4">Waktu Kedatangan</th>
                                        <th class="p-4">Nama Tamu</th>
                                        <th class="p-4">Instansi Asal</th>
                                        <th class="p-4">Keperluan</th>
                                        <th class="p-4 pr-6">No. HP</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @forelse($guests as $index => $guest)
                                    <tr class="hover:bg-red-50/30 border-b border-gray-50 transition-colors">
                                        <td class="p-4 pl-6 text-xs text-gray-400 font-medium">{{ $index + 1 }}</td>
                                        <td class="p-4 whitespace-nowrap">
                                            <div class="font-bold text-gray-900">{{ $guest->created_at->format('d/m/y') }}</div>
                                            <div class="text-xs font-bold text-red-600 mt-0.5">{{ $guest->created_at->format('H:i') }} WIB</div>
                                        </td>
                                        <td class="p-4 font-extrabold text-gray-900">{{ $guest->nama_tamu }}</td>
                                        <td class="p-4 text-gray-500 font-medium">{{ $guest->instansi_asal ?? '-' }}</td>
                                        <td class="p-4">
                                            <span class="bg-purple-50 text-purple-700 text-[10px] font-extrabold px-2.5 py-1 rounded-md border border-purple-100 uppercase tracking-wider block max-w-[200px] truncate" title="{{ $guest->tujuan_keperluan }}">
                                                {{ $guest->tujuan_keperluan }}
                                            </span>
                                        </td>
                                        <td class="p-4 pr-6 font-medium text-gray-600 whitespace-nowrap">{{ $guest->no_hp }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="p-8 text-center text-gray-400 font-medium italic text-sm">
                                            Belum ada data tamu untuk periode ini.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br><br>

                </div>
            </div>
        </main>
    </div>

    <!-- Modal Logout -->
    <div x-show="logoutModalOpen" class="fixed inset-0 z-[120] flex items-center justify-center bg-gray-900/60 p-4 backdrop-blur-sm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0" style="display: none;">
        <div @click.away="logoutModalOpen = false" class="bg-white w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden transform transition-all p-8 text-center border-4 border-red-400">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6 text-red-500">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Yakin ingin keluar?</h3>
            <p class="text-gray-500 text-sm mb-8 font-medium">Anda akan mengakhiri sesi Portal saat ini.</p>
            <div class="flex flex-col gap-3">
                <form action="/logout" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 hover:-translate-y-0.5 text-white font-bold py-3.5 rounded-xl transition-all duration-200 shadow-lg shadow-red-200">Ya, Keluar Sistem</button>
                </form>
                <button @click="logoutModalOpen = false" type="button" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-xl transition duration-200">Batal</button>
            </div>
        </div>
    </div>
</body>
</html>