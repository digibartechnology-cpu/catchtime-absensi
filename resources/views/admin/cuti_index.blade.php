<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengajuan Cuti | Admin DigiBAR</title>
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
<body class="bg-animated-gradient text-gray-800 font-sans selection:bg-red-500 selection:text-white" x-data="{ sidebarOpen: false, modalProsesOpen: false, modalResolveOpen: false, cutiData: {}, resolveData: {}, logoutModalOpen: false, pilihStatus: '' }">

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
                <a href="/admin/cuti" class="block px-4 py-3 bg-red-50 text-red-700 rounded-xl font-bold border-l-4 border-red-600 shadow-sm flex justify-between items-center">
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

        <!-- MAIN CONTENT -->
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

                    <!-- HEADER HALAMAN CUTI & PENCARIAN -->
                    <header class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mt-4">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Kelola Pengajuan Cuti</h1>
                            <p class="text-gray-500 mt-1 font-medium">Review dan berikan persetujuan untuk cuti karyawan.</p>
                        </div>

                        <form action="/admin/cuti" method="GET" class="w-full md:w-auto flex gap-3">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, tipe, status..." class="w-full md:w-64 px-4 py-2.5 border border-gray-200 bg-white/80 backdrop-blur-sm rounded-xl text-sm font-medium focus:ring-2 focus:ring-red-500 shadow-sm">
                            <button type="submit" class="bg-gray-800 hover:bg-gray-900 hover:-translate-y-0.5 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-all shadow-md">Cari</button>
                            @if(request('search'))
                                <a href="/admin/cuti" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-5 py-2.5 rounded-xl text-sm font-bold transition flex items-center justify-center shadow-sm">Reset</a>
                            @endif
                        </form>
                    </header>

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl relative mb-6 shadow-sm flex items-center gap-3">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="block sm:inline text-sm font-semibold">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Tabel Riwayat Cuti -->
                    <div class="bg-white/90 backdrop-blur-md rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-5 border-b border-gray-100 bg-white/50">
                            <h2 class="text-lg font-extrabold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Antrean Pengajuan
                            </h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[800px]">
                                <thead>
                                    <tr class="bg-gray-50/80 text-gray-500 text-[10px] md:text-xs uppercase tracking-widest border-b border-gray-100 font-bold">
                                        <th class="p-4 pl-6">Tgl Pengajuan</th>
                                        <th class="p-4">Nama Karyawan</th>
                                        <th class="p-4">Detail Cuti</th>
                                        <th class="p-4">Status</th>
                                        <th class="p-4 text-center pr-6 w-48">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @forelse($cuti_requests as $cuti)
                                        <tr class="hover:bg-red-50/30 border-b border-gray-50 transition-colors">
                                            <td class="p-4 pl-6 whitespace-nowrap">
                                                <div class="font-bold text-gray-900">{{ $cuti->created_at->format('d/m/y') }}</div>
                                                <div class="text-xs font-bold text-red-600 mt-0.5">{{ $cuti->created_at->format('H:i') }} WIB</div>
                                            </td>
                                            <td class="p-4">
                                                <div class="font-extrabold text-gray-900">{{ $cuti->employee->nama_lengkap ?? 'Karyawan Dihapus' }}</div>
                                            </td>
                                            <td class="p-4 whitespace-nowrap">
                                                <span class="block font-extrabold text-red-700 text-[10px] uppercase tracking-wider mb-1">{{ $cuti->jenis_cuti }}</span>
                                                <span class="text-xs font-bold text-gray-700">{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d M Y') }}</span>
                                                <p class="text-xs text-gray-500 mt-1 max-w-[200px] truncate font-medium" title="{{ $cuti->alasan }}">"{{ $cuti->alasan }}"</p>
                                            </td>
                                            <td class="p-4">
                                                @php
                                                    $color = 'bg-yellow-50 text-yellow-700 border-yellow-200';
                                                    if(str_contains(strtoupper($cuti->status), 'DISETUJUI SEMENTARA')) {
                                                        $color = 'bg-teal-50 text-teal-700 border-teal-200';
                                                    } elseif(str_contains(strtoupper($cuti->status), 'DISETUJUI')) {
                                                        $color = 'bg-green-50 text-green-700 border-green-200';
                                                    }
                                                    if($cuti->status == 'Ditolak') $color = 'bg-red-50 text-red-700 border-red-200';
                                                    if($cuti->status == 'Reschedule') $color = 'bg-purple-50 text-purple-700 border-purple-200';
                                                @endphp
                                                <span class="{{ $color }} text-[10px] font-extrabold px-2.5 py-1 rounded-md border uppercase tracking-wider">
                                                    {{ $cuti->status }}
                                                </span>
                                            </td>
                                            <td class="p-4 pr-6 text-center">
                                                @if($cuti->status == 'Pending')
                                                    <button 
                                                        @click="cutiData = { id: {{ $cuti->id }}, nama: '{{ $cuti->employee->nama_lengkap ?? '' }}' }; modalProsesOpen = true; pilihStatus = '';"
                                                        class="bg-blue-600 hover:bg-blue-700 hover:-translate-y-0.5 text-white text-[11px] font-extrabold px-4 py-2.5 rounded-xl transition-all shadow-md shadow-blue-200 w-full uppercase tracking-wider">
                                                        Proses Cuti
                                                    </button>
                                                
                                                @elseif(strtoupper($cuti->status) === 'DISETUJUI SEMENTARA')
                                                    <div class="flex flex-col gap-2">
                                                        <button type="button" 
                                                                @click="resolveData = { id: {{ $cuti->id }}, keputusan: 'valid_sakit', title: 'Validasi Surat Dokter', message: 'Apakah Anda yakin surat dokter ini valid? Status akan diubah menjadi DISETUJUI penuh.', btnColor: 'bg-green-500 hover:bg-green-600 shadow-green-200' }; modalResolveOpen = true;"
                                                                class="w-full bg-green-50 hover:bg-green-100 text-green-700 border border-green-200 px-3 py-2 rounded-xl text-[10px] font-extrabold transition-colors uppercase tracking-wider">
                                                            ✅ Surat Valid
                                                        </button>
                                                        <button type="button" 
                                                                @click="resolveData = { id: {{ $cuti->id }}, keputusan: 'convert_cuti', title: 'Ubah ke Cuti Tahunan', message: 'Surat dokter tidak valid/tidak ada? Pengajuan ini akan dipotong dari kuota Cuti Tahunan.', btnColor: 'bg-orange-500 hover:bg-orange-600 shadow-orange-200' }; modalResolveOpen = true;"
                                                                class="w-full bg-orange-50 hover:bg-orange-100 text-orange-700 border border-orange-200 px-3 py-2 rounded-xl text-[10px] font-extrabold transition-colors uppercase tracking-wider">
                                                            🔄 Ubah Normal
                                                        </button>
                                                    </div>
                                                @else
                                                    <span class="text-[11px] text-gray-400 font-bold italic tracking-wider">Selesai Diproses</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="p-8 text-center text-gray-400 font-medium italic text-sm">Belum ada pengajuan / tidak ditemukan.</td></tr>
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

    <!-- Modal Proses Cuti -->
    <div x-show="modalProsesOpen" class="fixed inset-0 z-[110] flex items-center justify-center bg-gray-900/60 p-4 backdrop-blur-sm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0" style="display: none;">
        <div @click.away="modalProsesOpen = false" class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden transform transition-all">
            <div class="bg-gradient-to-r from-blue-700 to-blue-800 p-6 text-white border-b border-blue-900">
                <h3 class="text-xl font-extrabold tracking-tight">Proses Pengajuan Cuti</h3>
                <p class="text-sm text-blue-200 mt-1 font-medium">Karyawan: <span x-text="cutiData.nama" class="font-extrabold text-white"></span></p>
            </div>
            
            <form :action="'/admin/cuti/' + cutiData.id + '/status'" method="POST" class="p-6 md:p-8">
                @csrf
                <div class="mb-5">
                    <label class="block text-gray-500 text-xs font-bold mb-2 uppercase tracking-wider">Pilih Keputusan</label>
                    <select name="status" x-model="pilihStatus" class="w-full px-4 py-3 border border-gray-200 bg-gray-50 rounded-xl focus:ring-2 focus:ring-blue-500 font-bold text-gray-800" required>
                        <option value="">-- Pilih --</option>
                        <option value="Disetujui">✅ Setujui Cuti</option>
                        <option value="Ditolak">❌ Tolak Pengajuan</option>
                        <option value="Reschedule">🔄 Diizinkan tapi Ganti Tanggal</option>
                    </select>
                </div>

                <div x-show="pilihStatus === 'Reschedule'" class="mb-5 bg-purple-50 p-4 rounded-2xl border border-purple-100">
                    <label class="block text-purple-800 text-xs font-bold mb-2 uppercase tracking-wider">Saran Tanggal Baru</label>
                    <input type="date" name="tanggal_reschedule" class="w-full px-4 py-3 border border-purple-200 bg-white rounded-xl focus:ring-2 focus:ring-purple-500 text-sm font-medium text-purple-900">
                </div>
                
                <div class="mb-8">
                    <label class="block text-gray-500 text-xs font-bold mb-2 uppercase tracking-wider">Catatan Tambahan</label>
                    <textarea name="catatan_admin" rows="2" class="w-full px-4 py-3 border border-gray-200 bg-gray-50 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-medium" placeholder="Tuliskan alasan penolakan/persetujuan..."></textarea>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" @click="modalProsesOpen = false" class="w-full sm:w-auto flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3.5 px-6 rounded-xl transition">Batal</button>
                    <button type="submit" class="w-full sm:w-auto flex-1 bg-blue-600 hover:bg-blue-700 hover:-translate-y-0.5 text-white font-bold py-3.5 px-6 rounded-xl transition-all shadow-lg shadow-blue-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Kirim WA
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Konfirmasi Resolusi Sakit Sementara -->
    <div x-show="modalResolveOpen" class="fixed inset-0 z-[110] flex items-center justify-center bg-gray-900/60 p-4 backdrop-blur-sm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0" style="display: none;">
        <div @click.away="modalResolveOpen = false" 
             class="bg-white text-gray-800 w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden transform transition-all text-center p-8 border-4"
             :class="resolveData.keputusan === 'valid_sakit' ? 'border-green-400' : 'border-orange-400'">
            
            <div class="w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-6" 
                 :class="resolveData.keputusan === 'valid_sakit' ? 'bg-green-100 text-green-500' : 'bg-orange-100 text-orange-500'">
                <svg x-show="resolveData.keputusan === 'valid_sakit'" class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                <svg x-show="resolveData.keputusan === 'convert_cuti'" class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </div>

            <h2 class="text-2xl font-extrabold text-gray-900 mb-2" x-text="resolveData.title"></h2>
            <p class="text-gray-500 text-sm leading-relaxed mb-8 font-medium" x-text="resolveData.message"></p>

            <form :action="'/admin/cuti/' + resolveData.id + '/resolve'" method="POST" class="flex flex-col gap-3">
                @csrf
                <input type="hidden" name="keputusan" :value="resolveData.keputusan">
                <button type="submit" :class="resolveData.btnColor" class="w-full text-white font-bold py-3.5 rounded-xl transition-all duration-200 shadow-lg hover:-translate-y-0.5">
                    Ya, Lanjutkan
                </button>
                <button type="button" @click="modalResolveOpen = false" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-xl transition duration-200">
                    Batal
                </button>
            </form>
        </div>
    </div>
</body>
</html>