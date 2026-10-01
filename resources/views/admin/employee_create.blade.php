<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Karyawan | Admin DigiBAR</title>
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
<body class="bg-animated-gradient text-gray-800 font-sans selection:bg-red-500 selection:text-white" x-data="{ sidebarOpen: false, detailOpen: false, selectedEmp: {}, logoutModalOpen: false, deleteModalOpen: false, deleteUrl: '' }">

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
                <a href="/admin/karyawan" class="block px-4 py-3 bg-red-50 text-red-700 rounded-xl font-bold border-l-4 border-red-600 shadow-sm">
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
                        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Kelola Data Karyawan</h1>
                        <p class="text-gray-500 mt-1 font-medium">Tambah karyawan baru dan lihat daftar seluruh karyawan.</p>
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

                    <!-- Form Tambah Karyawan -->
                    <div class="bg-white/90 backdrop-blur-md p-6 md:p-8 rounded-3xl shadow-sm border border-gray-100 mb-8 hover:shadow-md transition-shadow">
                        <h2 class="text-lg font-extrabold text-gray-900 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            Tambah Karyawan Baru
                        </h2>
                        
                        @php
                            $isLimitReached = count($employees) >= 15;
                        @endphp

                        @if($isLimitReached)
                            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl mb-6 flex items-start gap-4">
                                <svg class="w-8 h-8 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <div>
                                    <p class="font-extrabold text-lg">Batas Maksimal Tercapai!</p>
                                    <p class="text-sm mt-1 font-medium leading-relaxed">Sistem mendeteksi Anda sudah memiliki 15 karyawan aktif. Anda tidak dapat menambahkan data baru lagi kecuali menghapus data lama terlebih dahulu.</p>
                                </div>
                            </div>
                        @endif

                        <form action="/admin/karyawan" method="POST" @if($isLimitReached) onsubmit="event.preventDefault(); alert('Akses Ditolak: Batas maksimal 15 karyawan telah tercapai!');" @endif>
                            @csrf
                            <fieldset @if($isLimitReached) disabled class="opacity-50 cursor-not-allowed" @endif>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5 text-sm">
                                    <div>
                                        <label class="block text-gray-500 font-bold mb-2 uppercase tracking-wider text-xs">Nama Lengkap</label>
                                        <input type="text" name="nama_lengkap" class="w-full px-4 py-3 border border-gray-200 bg-gray-50 rounded-xl focus:ring-2 focus:ring-red-500 disabled:bg-gray-100 font-medium" required>
                                    </div>
                                    <div>
                                        <label class="block text-gray-500 font-bold mb-2 uppercase tracking-wider text-xs">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" class="w-full px-4 py-3 border border-gray-200 bg-gray-50 rounded-xl focus:ring-2 focus:ring-red-500 disabled:bg-gray-100 font-medium" required>
                                            <option value="">-- Pilih Jenis Kelamin --</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-gray-500 font-bold mb-2 uppercase tracking-wider text-xs">No. WhatsApp</label>
                                        <input type="text" name="no_whatsapp" class="w-full px-4 py-3 border border-gray-200 bg-gray-50 rounded-xl focus:ring-2 focus:ring-red-500 disabled:bg-gray-100 font-medium" placeholder="Cth: 08123..." required>
                                    </div>
                                    <div>
                                        <label class="block text-gray-500 font-bold mb-2 uppercase tracking-wider text-xs">Jabatan / Posisi</label>
                                        <input type="text" name="jabatan_posisi" class="w-full px-4 py-3 border border-gray-200 bg-gray-50 rounded-xl focus:ring-2 focus:ring-red-500 disabled:bg-gray-100 font-medium" required>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-gray-500 font-bold mb-2 uppercase tracking-wider text-xs flex items-center justify-between">
                                            <span>Kode Kartu RFID / Flazz</span>
                                            <span class="text-red-500 font-bold italic normal-case tracking-normal border border-red-200 bg-red-50 px-2 py-0.5 rounded-md">(Klik kolom ini lalu Tap Kartu)</span>
                                        </label>
                                        <input type="text" name="rfid_uid" id="rfid_uid" readonly class="w-full px-4 py-4 border border-gray-300 bg-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-red-300 cursor-pointer placeholder-gray-400 font-mono tracking-[0.2em] text-center text-xl font-extrabold text-gray-800" placeholder="TAP KARTU DI SCANNER">
                                    </div>
                                </div>
                                <div class="mb-8 text-sm">
                                    <label class="block text-gray-500 font-bold mb-2 uppercase tracking-wider text-xs">Alamat Tempat Tinggal</label>
                                    <textarea name="alamat_tempat_tinggal" rows="2" class="w-full px-4 py-3 border border-gray-200 bg-gray-50 rounded-xl focus:ring-2 focus:ring-red-500 disabled:bg-gray-100 font-medium" required></textarea>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="w-full md:w-auto text-white font-bold py-3.5 px-10 rounded-xl transition-all duration-200 shadow-lg 
                                        @if($isLimitReached) bg-gray-400 hover:bg-gray-400 cursor-not-allowed shadow-none 
                                        @else bg-red-600 hover:bg-red-700 hover:-translate-y-0.5 shadow-red-200 @endif">
                                        Simpan Data
                                    </button>
                                </div>
                            </fieldset>
                        </form>
                    </div>

                    <!-- Tabel Daftar Karyawan -->
                    <div class="bg-white/90 backdrop-blur-md rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-white/50">
                            <h2 class="text-lg font-extrabold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Daftar Karyawan (<span x-text="{{ count($employees) }}"></span>/15)
                            </h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[800px]">
                                <thead>
                                    <tr class="bg-gray-50/80 text-gray-500 text-[10px] md:text-xs uppercase tracking-widest border-b border-gray-100 font-bold">
                                        <th class="p-4 pl-6">No</th>
                                        <th class="p-4">Nama Lengkap</th>
                                        <th class="p-4">L/P</th>
                                        <th class="p-4">WhatsApp</th>
                                        <th class="p-4">Jabatan</th>
                                        <th class="p-4 text-center pr-6">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @forelse($employees as $index => $emp)
                                    <tr class="hover:bg-red-50/30 border-b border-gray-50 transition-colors">
                                        <td class="p-4 pl-6 text-xs text-gray-400 font-medium">{{ $index + 1 }}</td>
                                        <td class="p-4 font-extrabold text-gray-900">{{ $emp->nama_lengkap }}</td>
                                        <td class="p-4 font-bold text-gray-500">{{ $emp->jenis_kelamin == 'Laki-laki' ? 'L' : 'P' }}</td>
                                        <td class="p-4 font-medium text-gray-600">{{ $emp->no_whatsapp }}</td>
                                        <td class="p-4">
                                            <span class="bg-red-50 text-red-700 text-[10px] font-extrabold px-2.5 py-1 rounded-md border border-red-100 uppercase tracking-wider">
                                                {{ $emp->jabatan_posisi }}
                                            </span>
                                        </td>
                                        <td class="p-4 pr-6 text-center">
                                            <div class="flex items-center justify-center gap-3">
                                                <button 
                                                    @click="selectedEmp = {{ json_encode($emp) }}; detailOpen = true"
                                                    class="text-blue-600 hover:text-blue-800 font-extrabold text-xs uppercase tracking-wider transition">
                                                    Detail
                                                </button>
                                                <span class="text-gray-300">|</span>
                                                <button type="button" 
                                                    @click="deleteUrl = '/admin/karyawan/{{ $emp->id }}'; deleteModalOpen = true"
                                                    class="text-red-500 hover:text-red-700 font-extrabold text-xs uppercase tracking-wider transition">
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="6" class="p-8 text-center text-gray-400 font-medium italic text-sm">Belum ada data karyawan.</td></tr>
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

    <!-- Modal Detail Karyawan -->
    <div x-show="detailOpen" 
         class="fixed inset-0 z-[110] flex items-center justify-center bg-gray-900/60 p-4 backdrop-blur-sm" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100"
         style="display: none;">
        
        <div @click.away="detailOpen = false" 
             class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden transform transition-all"
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 scale-90 translate-y-8" 
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="bg-gradient-to-r from-red-600 to-red-800 p-6 flex justify-between items-center text-white">
                <h3 class="text-lg font-extrabold tracking-wide">Biodata Karyawan</h3>
                <button @click="detailOpen = false" class="text-red-200 hover:text-white text-2xl font-bold transition-colors">&times;</button>
            </div>

            <div class="p-6 md:p-8 space-y-5 text-sm">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nama Lengkap</label>
                    <p class="text-xl font-extrabold text-gray-900 mt-1" x-text="selectedEmp.nama_lengkap"></p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Jenis Kelamin</label>
                        <p class="text-gray-800 font-bold mt-1" x-text="selectedEmp.jenis_kelamin"></p>
                    </div>
                    <div class="bg-red-50 p-3 rounded-xl border border-red-100">
                        <label class="block text-[10px] font-bold text-red-400 uppercase tracking-widest">Jabatan</label>
                        <p class="text-red-700 font-extrabold mt-1" x-text="selectedEmp.jabatan_posisi"></p>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">No. WhatsApp</label>
                    <p class="text-gray-800 font-bold mt-1" x-text="selectedEmp.no_whatsapp"></p>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Alamat Tempat Tinggal</label>
                    <p class="text-gray-600 mt-1 leading-relaxed bg-gray-50 p-4 rounded-xl border border-gray-100 font-medium" x-text="selectedEmp.alamat_tempat_tinggal"></p>
                </div>
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end">
                <button @click="detailOpen = false" class="bg-white hover:bg-gray-100 text-gray-800 font-bold py-2.5 px-8 rounded-xl border border-gray-200 transition duration-200 shadow-sm">
                    Tutup
                </button>
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
            <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Hapus Karyawan Ini?</h3>
            <p class="text-gray-500 text-sm mb-8 font-medium">Data karyawan ini akan dihapus permanen. Data absensi yang terkait mungkin juga akan ikut terhapus.</p>
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
    // Script Auto-Refresh Tabel
    setInterval(function() {
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                let parser = new DOMParser();
                let doc = parser.parseFromString(html, 'text/html');
                let newTable = doc.querySelector('table').innerHTML; 
                document.querySelector('table').innerHTML = newTable;
            })
            .catch(err => console.log('Update otomatis berjalan...'));
    }, 5000);

    // ==========================================
    // LOGIKA ANTI-KEYBOARD & TANGKAP SCANNER RFID
    // ==========================================
    let rfidBuffer = "";
    let rfidTimer;

    document.addEventListener('keydown', function(e) {
        const rfidInput = document.getElementById('rfid_uid');

        // Pastikan kursor sedang fokus di kolom RFID
        if (document.activeElement === rfidInput) {
            
            // Cegah perilaku default keyboard agar tidak mengganggu sistem
            if (e.key !== 'Tab') {
                e.preventDefault(); 
            }

            // Jika scanner mengirimkan karakter "Enter" di akhir tap (standar alat RFID)
            if (e.key === 'Enter') {
                if (rfidBuffer.length >= 5) {
                    rfidInput.value = rfidBuffer.toUpperCase(); // Tampilkan kode ke input
                }
                rfidBuffer = ""; // Bersihkan memori buffer
            } 
            // Tangkap karakter ketikan
            else if (e.key.length === 1) {
                rfidBuffer += e.key;

                // Cek kecepatan: Timer 100ms. Jika jeda antar karakter lebih dari 100ms, 
                // berarti itu ketikan jari manusia yang lambat, otomatis dibuang.
                clearTimeout(rfidTimer);
                rfidTimer = setTimeout(() => {
                    rfidBuffer = ""; 
                }, 100);
            }
        }
    });
    </script>
</body>
</html>