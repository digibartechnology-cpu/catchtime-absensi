<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Izin & Cuti | CatchTime</title>
    <link rel="icon" href="{{ asset('images/Logo_icon.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Library Canvas Confetti -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Animasi Background Gradient Biru Halus khas CatchTime */
        .bg-catchtime-gradient {
            background: linear-gradient(-45deg, #e8f0fe, #d4e3fc, #f4f7fc, #eef3fb);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body class="bg-catchtime-gradient text-gray-800 antialiased selection:bg-[#0033a0] selection:text-white min-h-screen flex flex-col items-center justify-center p-4 relative" 
      x-data="cutiApp()" 
      x-init="initApp()">

    <!-- Tombol Kembali -->
    <a href="/" class="absolute top-6 left-6 flex items-center gap-2 text-gray-600 hover:text-[#0033a0] transition-colors bg-white/70 px-5 py-2.5 border border-gray-200 shadow-sm rounded-full backdrop-blur-md font-bold text-sm z-40">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        <span class="hidden sm:inline">Kembali ke Menu</span>
    </a>

    <!-- Container Utama Form -->
    <div class="relative z-10 w-full max-w-lg bg-white/90 backdrop-blur-md p-6 sm:p-8 rounded-3xl shadow-xl border border-gray-100 mt-12 sm:mt-0">
        
        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-50 text-[#0033a0] rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                <!-- Icon Outline Kalender Cuti -->
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-[#0033a0] mb-1">Pengajuan Izin & Cuti</h1>
            <p class="text-sm text-gray-500 font-medium">Rehat sejenak atau urus keperluan penting dengan tenang. 🌿</p>
        </div>

        <!-- NOTIFIKASI ERROR (Durasi Hari Cuti Ditolak) -->
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl relative mb-6 shadow-sm flex items-center gap-3">
                <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div>
                    <strong class="font-extrabold">Gagal! </strong>
                    <span class="block sm:inline text-sm font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @php
            // PERHITUNGAN WAKTU UNTUK BATAS SAKIT HARI ESOK
            $sekarang = \Carbon\Carbon::now('Asia/Jakarta');
            $hariIni = $sekarang->format('Y-m-d');
            $besok = $sekarang->copy()->addDay()->format('Y-m-d');
            
            // Cek apakah saat ini sudah melewati jam pulang kantor (17:00 WIB)
            $isLewatJamPulang = $sekarang->format('H:i') >= '17:00';
            
            // Jika sudah lewat jam 5 sore, karyawan boleh pilih tanggal sakit untuk besok
            $maxTanggalSakit = $isLewatJamPulang ? $besok : $hariIni;
        @endphp

        <form action="/cuti/store" method="POST" id="formCuti" @submit="isSubmitting = true">
            @csrf
            
            <div class="space-y-5">
                <!-- Nama Karyawan -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Karyawan</label>
                    <select name="employee_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#0033a0] font-semibold text-gray-700 appearance-none transition-shadow">
                        <option value="">-- Pilih Nama Anda --</option>
                        @foreach($employees as $emp) 
                            <option value="{{ $emp->id }}">{{ $emp->nama_lengkap }}</option> 
                        @endforeach
                    </select>
                </div>

                <!-- Pilihan Jenis Cuti -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Jenis / Keperluan</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        
                        <!-- Cuti Tahunan -->
                        <label class="cursor-pointer group">
                            <input type="radio" name="jenis_cuti" value="Cuti Tahunan" x-model="jenisCuti" class="peer sr-only" required>
                            <div class="p-3 border-2 border-gray-200 rounded-xl peer-checked:border-[#0033a0] peer-checked:bg-blue-50 text-gray-400 peer-checked:text-[#0033a0] hover:bg-gray-50 hover:text-blue-500 transition-all text-center h-full flex flex-col justify-center shadow-sm">
                                <svg class="w-8 h-8 mx-auto mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="font-bold text-xs text-gray-800">Cuti Tahunan</span>
                            </div>
                        </label>
                        
                        <!-- Sakit -->
                        <label class="cursor-pointer group">
                            <input type="radio" name="jenis_cuti" value="Sakit" x-model="jenisCuti" class="peer sr-only" required>
                            <div class="p-3 border-2 border-gray-200 rounded-xl peer-checked:border-[#0033a0] peer-checked:bg-blue-50 text-gray-400 peer-checked:text-[#0033a0] hover:bg-gray-50 hover:text-blue-500 transition-all text-center h-full flex flex-col justify-center shadow-sm">
                                <svg class="w-8 h-8 mx-auto mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11v4m-2-2h4" />
                                </svg>
                                <span class="font-bold text-xs text-gray-800">Sakit</span>
                            </div>
                        </label>
                        
                        <!-- Izin Penting -->
                        <label class="cursor-pointer group">
                            <input type="radio" name="jenis_cuti" value="Izin Penting" x-model="jenisCuti" class="peer sr-only" required>
                            <div class="p-3 border-2 border-gray-200 rounded-xl peer-checked:border-[#0033a0] peer-checked:bg-blue-50 text-gray-400 peer-checked:text-[#0033a0] hover:bg-gray-50 hover:text-blue-500 transition-all text-center h-full flex flex-col justify-center shadow-sm">
                                <svg class="w-8 h-8 mx-auto mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span class="font-bold text-xs text-gray-800">Izin Penting</span>
                            </div>
                        </label>
                        
                        <!-- Ijin Setengah Hari -->
                        <label class="cursor-pointer group">
                            <input type="radio" name="jenis_cuti" value="Ijin Setengah Hari" x-model="jenisCuti" class="peer sr-only" required>
                            <div class="p-3 border-2 border-gray-200 rounded-xl peer-checked:border-[#0033a0] peer-checked:bg-blue-50 text-gray-400 peer-checked:text-[#0033a0] hover:bg-gray-50 hover:text-blue-500 transition-all text-center h-full flex flex-col justify-center shadow-sm">
                                <svg class="w-8 h-8 mx-auto mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-bold text-xs text-gray-800">Setengah Hari</span>
                            </div>
                        </label>
                        
                        <!-- Cuti Melahirkan -->
                        <label class="cursor-pointer group">
                            <input type="radio" name="jenis_cuti" value="Cuti Melahirkan" x-model="jenisCuti" class="peer sr-only" required>
                            <div class="p-3 border-2 border-gray-200 rounded-xl peer-checked:border-[#0033a0] peer-checked:bg-blue-50 text-gray-400 peer-checked:text-[#0033a0] hover:bg-gray-50 hover:text-blue-500 transition-all text-center h-full flex flex-col justify-center shadow-sm">
                                <svg class="w-8 h-8 mx-auto mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-bold text-xs text-gray-800">Melahirkan</span>
                            </div>
                        </label>
                        
                        <!-- Lainnya -->
                        <label class="cursor-pointer group">
                            <input type="radio" name="jenis_cuti" value="Lainnya" x-model="jenisCuti" class="peer sr-only" required>
                            <div class="p-3 border-2 border-gray-200 rounded-xl peer-checked:border-[#0033a0] peer-checked:bg-blue-50 text-gray-400 peer-checked:text-[#0033a0] hover:bg-gray-50 hover:text-blue-500 transition-all text-center h-full flex flex-col justify-center shadow-sm">
                                <svg class="w-8 h-8 mx-auto mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span class="font-bold text-xs text-gray-800">Lainnya...</span>
                            </div>
                        </label>
                        
                    </div>
                </div>

                <!-- Opsi Khusus: Ijin Setengah Hari -->
                <div x-show="jenisCuti === 'Ijin Setengah Hari'" style="display: none;" class="p-4 bg-blue-50/60 rounded-2xl border border-blue-100">
                    <label class="block text-xs font-bold text-[#0033a0] uppercase tracking-wider mb-3">
                        Pilih Sesi Izin <span class="text-[10px] font-normal lowercase italic text-gray-500">(sesi libur yang diambil)</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        
                        <!-- Opsi Pagi -->
                        <label class="cursor-pointer group">
                            <input type="radio" name="waktu_setengah_hari" value="Ijin Pagi (Mulai Kerja Siang)" class="peer sr-only" :required="jenisCuti === 'Ijin Setengah Hari'">
                            <div class="p-3 border-2 border-gray-200 rounded-xl peer-checked:border-[#0033a0] peer-checked:bg-white text-gray-400 peer-checked:text-[#0033a0] bg-white/70 hover:bg-white hover:text-blue-500 transition-all text-center h-full flex flex-col justify-center shadow-sm">
                                <svg class="w-7 h-7 mx-auto mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <div class="font-bold text-gray-800 text-xs mb-0.5">Izin Pagi</div>
                                <div class="text-[10px] text-gray-500 leading-tight">Mulai masuk setelah makan siang</div>
                            </div>
                        </label>
                        
                        <!-- Opsi Siang -->
                        <label class="cursor-pointer group">
                            <input type="radio" name="waktu_setengah_hari" value="Ijin Siang (Pulang Cepat)" class="peer sr-only" :required="jenisCuti === 'Ijin Setengah Hari'">
                            <div class="p-3 border-2 border-gray-200 rounded-xl peer-checked:border-[#0033a0] peer-checked:bg-white text-gray-400 peer-checked:text-[#0033a0] bg-white/70 hover:bg-white hover:text-blue-500 transition-all text-center h-full flex flex-col justify-center shadow-sm">
                                <svg class="w-7 h-7 mx-auto mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <div class="font-bold text-gray-800 text-xs mb-0.5">Izin Siang</div>
                                <div class="text-[10px] text-gray-500 leading-tight">Pulang lebih awal saat jam istirahat</div>
                            </div>
                        </label>
                        
                    </div>
                </div>

                <!-- Input Khusus: Alasan Lainnya -->
                <div x-show="jenisCuti === 'Lainnya'" style="display: none;">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tuliskan Alasan Spesifik</label>
                    <textarea name="alasan_lainnya" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#0033a0] font-medium text-gray-800 transition-shadow resize-none" placeholder="Tuliskan keterangan detail pengajuan Anda..." :required="jenisCuti === 'Lainnya'"></textarea>
                </div>

                <!-- Kolom Tanggal Dinamis (Dengan Logika Jam Pulang) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Dari Tanggal -->
                    <div :class="jenisCuti === 'Ijin Setengah Hari' ? 'sm:col-span-2' : ''">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" x-text="jenisCuti === 'Ijin Setengah Hari' ? 'Tanggal Izin' : 'Dari Tanggal'"></label>
                        
                        <input type="date" name="tanggal_mulai" x-model="tanggalMulai" 
                               :min="jenisCuti === 'Sakit' ? '' : '{{ $hariIni }}'" 
                               :max="jenisCuti === 'Sakit' ? '{{ $maxTanggalSakit }}' : ''"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#0033a0] font-semibold text-gray-800 transition-shadow" required>
                        
                        <!-- Pesan Info Dinamis untuk Sakit -->
                        <p x-show="jenisCuti === 'Sakit'" style="display: none;" class="text-[10px] mt-1.5 font-bold" :class="'{{ $isLewatJamPulang }}' ? 'text-green-600' : 'text-red-500'">
                            @if($isLewatJamPulang)
                                ✔️ Sudah lewat jam pulang. Anda diizinkan mengajukan sakit untuk besok.
                            @else
                                *Izin sakit untuk besok hanya bisa diajukan setelah jam pulang kantor (17:00 WIB).
                            @endif
                        </p>
                    </div>
                    
                    <!-- Sampai Tanggal -->
                    <div x-show="jenisCuti !== 'Ijin Setengah Hari'">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Sampai Tanggal</label>
                        
                        <input type="date" name="tanggal_selesai" x-model="tanggalSelesai" 
                               :min="tanggalMulai ? tanggalMulai : '{{ $hariIni }}'" 
                               :max="getMaxTanggalSelesai()"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#0033a0] font-semibold text-gray-800 transition-shadow" 
                               :required="jenisCuti !== 'Ijin Setengah Hari'">
                        
                        <p x-show="getMaxTanggalSelesai() !== '' && jenisCuti !== 'Izin Penting'" style="display: none;" class="text-[10px] text-gray-500 mt-2 font-medium">
                            *Batas maksimal pilihan tanggal: <br> <span class="font-extrabold text-red-600" x-text="formatTanggal(getMaxTanggalSelesai())"></span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" :disabled="isSubmitting" 
                    class="w-full mt-8 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg flex justify-center items-center gap-2"
                    :class="isSubmitting ? 'bg-gray-300 cursor-not-allowed shadow-none' : 'bg-[#0033a0] hover:bg-blue-900 shadow-blue-200 hover:-translate-y-0.5'">
                <span x-show="!isSubmitting">Kirim Pengajuan</span>
                <span x-show="isSubmitting" style="display: none;" class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Memproses...
                </span>
            </button>
        </form>
    </div>

    <!-- MODAL CONFETTI & SUKSES -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" x-transition.opacity.duration.300ms style="display: none;">
        <div class="bg-white text-gray-800 w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden transform transition-all text-center p-8 border-4 border-green-400"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-6 bg-green-100 text-green-500">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>

            <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Permohonan Dikirim! 🎉</h2>
            <p class="text-gray-500 text-sm leading-relaxed mb-6" x-text="successMessage"></p>

            <button @click="closeModal()" type="button" class="w-full bg-green-50 hover:bg-green-100 text-green-700 font-bold py-3 rounded-xl transition duration-200">
                Mengerti, Terima Kasih!
            </button>
        </div>
    </div>

    <!-- MODAL ERROR VALIDASI BAWAAN LARAVEL -->
    <div x-show="showErrorModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" x-transition.opacity.duration.300ms style="display: none;">
        <div @click.away="showErrorModal = false" class="bg-white text-gray-800 w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden transform transition-all text-center p-8 border-4 border-red-400 shadow-[0_0_50px_rgba(248,113,113,0.3)]"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-6 bg-red-100 text-red-500">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>

            <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Periksa Formulir!</h2>
            <div class="text-gray-500 text-sm leading-relaxed mb-6 space-y-1 font-medium">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>

            <button @click="showErrorModal = false" type="button" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-xl transition duration-200">
                Perbaiki Data
            </button>
        </div>
    </div>

    <!-- SCRIPT ALPINE JS -->
    <script>
        function cutiApp() {
            return {
                isSubmitting: false,
                jenisCuti: '',
                tanggalMulai: '',
                tanggalSelesai: '',
                showModal: {{ session('success') ? 'true' : 'false' }},
                showErrorModal: {{ $errors->any() ? 'true' : 'false' }},
                successMessage: {!! json_encode(session('success') ?? '') !!},

                initApp() {
                    if (this.showModal) {
                        this.triggerConfetti();
                    }

                    // PENGAMAN JIKA USER MENGUBAH JENIS CUTI / TANGGAL MULAI 
                    this.$watch('jenisCuti', () => {
                        this.koreksiTanggal();
                    });
                    this.$watch('tanggalMulai', () => {
                        this.koreksiTanggal();
                    });
                },

                // LOGIKA HITUNG BATAS MAKSIMAL HARI CUTI
                getMaxTanggalSelesai() {
                    if (!this.tanggalMulai || !this.jenisCuti) return '';

                    let date = new Date(this.tanggalMulai);
                    let addedDays = 0;

                    if (this.jenisCuti === 'Izin Penting') {
                        addedDays = 0; // Maksimal 1 hari
                    } else if (this.jenisCuti === 'Sakit') {
                        addedDays = 6; // Maksimal 7 hari (Hari pertama + 6)
                    } else if (this.jenisCuti === 'Cuti Tahunan') {
                        addedDays = 13; // Maksimal 14 hari (Hari pertama + 13)
                    } else {
                        return ''; // Jenis lain bebas (tidak dibatasi sistem)
                    }

                    date.setDate(date.getDate() + addedDays);
                    return date.toISOString().split('T')[0];
                },

                koreksiTanggal() {
                    let maxStr = this.getMaxTanggalSelesai();
                    if (maxStr !== '' && this.tanggalSelesai > maxStr) {
                        this.tanggalSelesai = maxStr;
                    }
                    if (this.tanggalSelesai && this.tanggalSelesai < this.tanggalMulai) {
                        this.tanggalSelesai = this.tanggalMulai;
                    }
                },

                formatTanggal(tglStr) {
                    if (!tglStr) return '';
                    const options = { day: 'numeric', month: 'long', year: 'numeric' };
                    return new Date(tglStr).toLocaleDateString('id-ID', options);
                },

                closeModal() {
                    this.showModal = false;
                    window.location.href = "/";
                },

                triggerConfetti() {
                    var duration = 3000;
                    var end = Date.now() + duration;

                    (function frame() {
                        confetti({
                            particleCount: 5, angle: 60, spread: 55, origin: { x: 0 },
                            colors: ['#0033a0', '#3b82f6', '#ffffff'] // Warna disesuaikan dengan CatchTime
                        });
                        confetti({
                            particleCount: 5, angle: 120, spread: 55, origin: { x: 1 },
                            colors: ['#0033a0', '#3b82f6', '#ffffff']
                        });

                        if (Date.now() < end) requestAnimationFrame(frame);
                    }());
                }
            }
        }
    </script>
</body>
</html>