<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Absensi | Admin DigiBAR</title>
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
<body class="bg-animated-gradient text-gray-800 font-sans selection:bg-red-500 selection:text-white" x-data="{ sidebarOpen: false, logoutModalOpen: false, detailAbsenOpen: false, absenData: {}, deleteModalOpen: false, deleteUrl: '', tab: 'kantor' }">

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
                <a href="/admin/absensi" class="block px-4 py-3 bg-red-50 text-red-700 rounded-xl font-bold border-l-4 border-red-600 shadow-sm">
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
                    <header class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-4 mt-4">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Data Absensi</h1>
                            <p class="text-gray-500 mt-1 font-medium">Catat kehadiran masuk dan pulang karyawan.</p>
                        </div>
                        <div class="hidden sm:flex items-center gap-2 text-xs font-bold text-green-700 bg-green-100/80 backdrop-blur-sm px-4 py-2 rounded-full border border-green-200 shadow-sm">
                            <span class="relative flex h-2.5 w-2.5">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                            </span>
                            Live Update Aktif
                        </div>
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
                        <form action="/admin/absensi" method="GET" class="flex flex-col md:flex-row items-end gap-4 w-full">
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
                                <a href="/admin/absensi/download-pdf?start_date={{ $startDate }}&end_date={{ $endDate }}" class="flex-1 md:flex-none bg-red-600 hover:bg-red-700 hover:-translate-y-0.5 text-white px-6 py-2.5 rounded-xl text-sm transition flex items-center justify-center gap-2 font-bold shadow-lg shadow-red-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Cetak PDF
                                </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- TAB NAVIGASI -->
                    <div class="flex gap-6 border-b border-gray-200 mb-6 overflow-x-auto pb-1" id="wadah-tabel-absensi">
                        <button @click="tab = 'kantor'" 
                                :class="tab === 'kantor' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700'" 
                                class="pb-3 border-b-4 font-extrabold text-sm md:text-base transition px-2 whitespace-nowrap flex items-center gap-2">
                            🏢 Absensi Kantor (RFID)
                        </button>
                        <button @click="tab = 'lapangan'" 
                                :class="tab === 'lapangan' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" 
                                class="pb-3 border-b-4 font-extrabold text-sm md:text-base transition px-2 whitespace-nowrap flex items-center gap-2">
                            📍 Absensi Lapangan (Web/GPS)
                        </button>
                    </div>

                    <div id="tabel-dinamis">
                        <!-- ================================
                             TAB 1: ABSENSI KANTOR (RFID)
                        ================================= -->
                        <div x-show="tab === 'kantor'" class="bg-white/90 backdrop-blur-md rounded-3xl shadow-sm border border-gray-100 overflow-hidden" style="display: none;" x-transition>
                            <div class="p-5 border-b border-gray-100 bg-red-50/50">
                                <h2 class="text-sm font-extrabold text-red-800 uppercase tracking-widest">Riwayat Tap Kartu</h2>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse min-w-[800px]">
                                    <thead>
                                        <tr class="bg-gray-50/80 text-gray-500 text-[10px] md:text-xs uppercase tracking-widest border-b border-gray-100 font-bold">
                                            <th class="p-4 pl-6">No</th>
                                            <th class="p-4">Waktu</th>
                                            <th class="p-4">Nama Karyawan</th>
                                            <th class="p-4">Jenis Absen</th>
                                            <th class="p-4">Status</th>
                                            <th class="p-4 text-center pr-6">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm">
                                        @forelse($absenKantor as $index => $absen)
                                        @php
                                            $jamAbsen = $absen->created_at->format('H:i');
                                            $statusWaktu = '-';
                                            $badgeColor = 'bg-gray-100 text-gray-800 border-gray-200';

                                            if ($absen->type == 'Masuk') {
                                                if ($jamAbsen > '09:00') {
                                                    $statusWaktu = 'Telat';
                                                    $badgeColor = 'bg-red-50 text-red-700 border-red-200';
                                                } else {
                                                    $statusWaktu = 'On Time';
                                                    $badgeColor = 'bg-green-50 text-green-700 border-green-200';
                                                }
                                            } elseif ($absen->type == 'Pulang') {
                                                if ($jamAbsen < '17:00') {
                                                    $statusWaktu = 'Pulang Awal';
                                                    $badgeColor = 'bg-yellow-50 text-yellow-700 border-yellow-200';
                                                } else {
                                                    $statusWaktu = 'On Time';
                                                    $badgeColor = 'bg-green-50 text-green-700 border-green-200';
                                                }
                                            } elseif ($absen->type == 'Ijin Keluar') {
                                                $statusWaktu = 'Keluar Sementara';
                                                $badgeColor = 'bg-orange-50 text-orange-700 border-orange-200';
                                            } elseif (in_array($absen->type, ['Kembali Ijin', 'Kembali ke Kantor'])) {
                                                $statusWaktu = 'Kembali Bekerja';
                                                $badgeColor = 'bg-teal-50 text-teal-700 border-teal-200';
                                            }
                                        @endphp
                                            <tr class="hover:bg-red-50/30 border-b border-gray-50 transition-colors">
                                                <td class="p-4 pl-6 text-xs text-gray-400 font-medium">{{ $index + 1 }}</td>
                                                <td class="p-4 whitespace-nowrap">
                                                    <div class="font-bold text-gray-900">{{ $absen->created_at->format('d/m/y') }}</div>
                                                    <div class="text-xs font-bold text-red-600 mt-0.5">{{ $absen->created_at->format('H:i') }} WIB</div>
                                                </td>
                                                <td class="p-4">
                                                    <div class="font-extrabold text-gray-900">{{ $absen->employee->nama_lengkap ?? 'Dihapus' }}</div>
                                                    <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wide mt-0.5">{{ $absen->employee->jabatan_posisi ?? '-' }}</div>
                                                </td>
                                                <td class="p-4">
                                                    <span class="bg-gray-100/80 text-gray-700 text-[10px] font-extrabold px-2.5 py-1 rounded-md border border-gray-200 uppercase tracking-wider">
                                                        {{ $absen->type }}
                                                    </span>
                                                </td>
                                                <td class="p-4">
                                                    <span class="{{ $badgeColor }} text-[10px] font-extrabold px-2.5 py-1 rounded-md border uppercase tracking-wider">
                                                        {{ $statusWaktu }}
                                                    </span>
                                                </td>
                                                <td class="p-4 pr-6 text-center">
                                                    <button type="button" 
                                                        @click="deleteUrl = '/admin/absensi/{{ $absen->id }}'; deleteModalOpen = true"
                                                        class="text-red-500 hover:text-red-700 font-extrabold text-xs uppercase tracking-wider transition">
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="p-8 text-center text-gray-400 font-medium italic text-sm">Belum ada data tap kartu untuk periode ini.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- ================================
                             TAB 2: ABSENSI LAPANGAN (WEB)
                        ================================= -->
                        <div x-show="tab === 'lapangan'" class="bg-white/90 backdrop-blur-md rounded-3xl shadow-sm border border-gray-100 overflow-hidden" style="display: none;" x-transition>
                            <div class="p-5 border-b border-gray-100 bg-blue-50/50 flex justify-between items-center">
                                <h2 class="text-sm font-extrabold text-blue-800 uppercase tracking-widest">Riwayat WFA / GPS</h2>
                                
                                <!-- FORM INPUT MANUAL (Disembunyikan di Tab Kantor) -->
                                <button onclick="document.getElementById('formManual').classList.toggle('hidden')" class="bg-white border border-blue-200 text-blue-600 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-blue-100 transition shadow-sm">
                                    + Input Manual
                                </button>
                            </div>

                            <!-- Form Manual (Hidden by default) -->
                            <div id="formManual" class="p-6 bg-blue-50/30 border-b border-blue-100 hidden">
                                <form action="/admin/absensi/store" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                                        <div>
                                            <select name="employee_id" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-xs font-bold" required>
                                                <option value="">-- Karyawan --</option>
                                                @foreach($employees as $emp)
                                                    <option value="{{ $emp->id }}">{{ $emp->nama_lengkap }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <div class="flex gap-4 p-2 bg-white rounded-xl border border-gray-200 h-[34px] items-center text-xs">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="radio" name="type" value="Masuk" class="form-radio text-blue-600 focus:ring-blue-500 w-3 h-3" checked>
                                                    <span class="ml-2 font-bold text-gray-700">Masuk</span>
                                                </label>
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="radio" name="type" value="Pulang" class="form-radio text-blue-600 focus:ring-blue-500 w-3 h-3">
                                                    <span class="ml-2 font-bold text-gray-700">Pulang</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div>
                                            <input type="file" name="foto_bukti" accept="image/*" class="w-full px-4 py-1.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 bg-white text-xs font-bold" required>
                                        </div>
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-xl text-xs transition-all shadow-md shadow-blue-200">
                                            Simpan Absensi Manual
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse min-w-[800px]">
                                    <thead>
                                        <tr class="bg-gray-50/80 text-gray-500 text-[10px] md:text-xs uppercase tracking-widest border-b border-gray-100 font-bold">
                                            <th class="p-4 pl-6">No</th>
                                            <th class="p-4">Waktu</th>
                                            <th class="p-4">Nama Karyawan</th>
                                            <th class="p-4">Jenis Absen</th>
                                            <th class="p-4 text-center pr-6">Aksi Detail & Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm">
                                        @forelse($absenLapangan as $index => $absen)
                                        @php
                                            $urlFoto = '';
                                            if ($absen->foto_bukti && $absen->foto_bukti !== 'Tanpa Foto' && $absen->foto_bukti !== 'default.png') {
                                                $urlFoto = asset('images/absensi/' . $absen->foto_bukti);
                                            }
                                        @endphp
                                            <tr class="hover:bg-blue-50/30 border-b border-gray-50 transition-colors">
                                                <td class="p-4 pl-6 text-xs text-gray-400 font-medium">{{ $index + 1 }}</td>
                                                <td class="p-4 whitespace-nowrap">
                                                    <div class="font-bold text-gray-900">{{ $absen->created_at->format('d/m/y') }}</div>
                                                    <div class="text-xs font-bold text-blue-600 mt-0.5">{{ $absen->created_at->format('H:i') }} WIB</div>
                                                </td>
                                                <td class="p-4">
                                                    <div class="font-extrabold text-gray-900">{{ $absen->employee->nama_lengkap ?? 'Dihapus' }}</div>
                                                    <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wide mt-0.5">{{ $absen->employee->jabatan_posisi ?? '-' }}</div>
                                                </td>
                                                <td class="p-4">
                                                    <span class="bg-gray-100/80 text-gray-700 text-[10px] font-extrabold px-2.5 py-1 rounded-md border border-gray-200 uppercase tracking-wider">
                                                        {{ $absen->type }}
                                                    </span>
                                                </td>
                                                <td class="p-4 pr-6 text-center">
                                                    <div class="flex items-center justify-center gap-3">
                                                        <button 
                                                            @click="absenData = {
                                                                nama: {{ json_encode($absen->employee->nama_lengkap ?? 'Karyawan Dihapus') }},
                                                                jabatan: {{ json_encode($absen->employee->jabatan_posisi ?? '-') }},
                                                                waktu: '{{ $absen->created_at->format('d M Y, H:i') }} WIB',
                                                                tipe: '{{ $absen->type }}',
                                                                status: 'Absen Lapangan',
                                                                statusWarna: 'bg-blue-50 text-blue-700',
                                                                foto: '{{ $urlFoto }}',
                                                                keterangan: {{ json_encode($absen->keterangan ?? '') }},
                                                                lat: '{{ $absen->latitude ?? '' }}',
                                                                lng: '{{ $absen->longitude ?? '' }}'
                                                            }; detailAbsenOpen = true"
                                                            class="text-blue-600 hover:text-blue-800 font-extrabold text-xs uppercase tracking-wider transition">
                                                            Detail
                                                        </button>
                                                        <span class="text-gray-300">|</span>
                                                        <button type="button" 
                                                            @click="deleteUrl = '/admin/absensi/{{ $absen->id }}'; deleteModalOpen = true"
                                                            class="text-red-500 hover:text-red-700 font-extrabold text-xs uppercase tracking-wider transition">
                                                            Hapus
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="p-8 text-center text-gray-400 font-medium italic text-sm">Belum ada data absensi lapangan untuk periode ini.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- End Div Tabel Dinamis -->
                    
                    <br><br>

                </div>
            </div>
        </main>
    </div>

    <!-- Modal Detail Absen -->
    <div x-show="detailAbsenOpen" 
         class="fixed inset-0 z-[110] flex items-center justify-center bg-gray-900/60 p-4 backdrop-blur-sm" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100"
         style="display: none;">
        <div @click.away="detailAbsenOpen = false" 
             class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden transform transition-all p-0 border-4 border-blue-300"
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 scale-90 translate-y-8" 
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="relative w-full h-64 bg-gray-100 flex items-center justify-center border-b border-gray-200">
                <img x-show="absenData.foto !== '' && absenData.foto !== 'rfid_icon'" :src="absenData.foto" class="w-full h-full object-cover">
                
                <div x-show="absenData.foto === ''" class="w-full h-full flex flex-col items-center justify-center text-gray-400 text-sm italic">
                    <svg class="w-12 h-12 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Tidak ada foto dari perangkat
                </div>
                
                <button @click="detailAbsenOpen = false" class="absolute top-4 right-4 bg-black/50 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center transition-colors shadow-sm">&times;</button>
            </div>
            
            <div class="p-6 md:p-8">
                <div class="mb-5 pb-5 border-b border-gray-100">
                    <h3 class="text-2xl font-extrabold text-gray-900" x-text="absenData.nama"></h3>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1" x-text="absenData.jabatan"></p>
                </div>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center bg-blue-50/50 p-4 rounded-2xl border border-blue-100">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu Rekam</span>
                        <span class="text-sm font-extrabold text-blue-600" x-text="absenData.waktu"></span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Jenis Absen</span>
                            <span class="inline-block bg-gray-100 text-gray-700 text-[10px] font-extrabold px-2.5 py-1 rounded-md border border-gray-200 uppercase tracking-wider" x-text="absenData.tipe"></span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Lokasi Absen</span>
                            <span :class="absenData.statusWarna" class="inline-block text-[10px] font-extrabold px-2.5 py-1 rounded-md border uppercase tracking-wider" x-text="absenData.status"></span>
                        </div>
                    </div>
                    
                    <div class="mt-5" x-show="absenData.lat !== '' && absenData.lng !== ''">
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Lokasi Koordinat GPS</span>
                        <a :href="'https://www.google.com/maps?q=' + absenData.lat + ',' + absenData.lng" 
                           target="_blank" 
                           class="flex items-center justify-center gap-2 w-full bg-blue-50 hover:bg-blue-100 hover:-translate-y-0.5 border border-blue-200 text-blue-700 text-sm font-bold py-3 rounded-xl transition-all shadow-sm">
                           <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                           Buka di Google Maps
                        </a>
                    </div>

                    <div class="mt-5" x-show="absenData.keterangan !== '' && absenData.keterangan !== null">
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Keterangan / Alasan</span>
                        <div class="bg-yellow-50/80 text-yellow-800 text-sm px-4 py-3 rounded-xl border border-yellow-200 font-medium" x-text="absenData.keterangan"></div>
                    </div>
                </div>
            </div>
        </div>
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

    <!-- Modal Delete -->
    <div x-show="deleteModalOpen" class="fixed inset-0 z-[120] flex items-center justify-center bg-gray-900/60 p-4 backdrop-blur-sm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0" style="display: none;">
        <div @click.away="deleteModalOpen = false" class="bg-white w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden transform transition-all p-8 text-center border-4 border-red-400">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6 text-red-500">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Hapus Data Ini?</h3>
            <p class="text-gray-500 text-sm mb-8 font-medium">Data absensi beserta foto buktinya akan dihapus permanen dan tidak dapat dikembalikan.</p>
            <div class="flex flex-col gap-3">
                <form :action="deleteUrl" method="POST" class="w-full">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 hover:-translate-y-0.5 text-white font-bold py-3.5 rounded-xl transition-all duration-200 shadow-lg shadow-red-200">Hapus Permanen</button>
                </form>
                <button @click="deleteModalOpen = false" type="button" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-xl transition duration-200">Batal</button>
            </div>
        </div>
    </div>

    <script>
        setInterval(function() {
            // Kita ubah auto-refresh untuk hanya me-refresh div container datanya saja (tabel dinamis)
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    let newContent = doc.getElementById('tabel-dinamis').innerHTML; 
                    document.getElementById('tabel-dinamis').innerHTML = newContent;
                })
                .catch(err => console.log('Update otomatis berjalan...'));
        }, 5000);
    </script>
</body>
</html>