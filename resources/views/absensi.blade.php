<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absen WFA / Lapangan | DigiBAR</title>
    <link rel="icon" href="{{ asset('images/Logo_icon.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
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
        /* Efek kamera terbalik (seperti cermin) */
        video { transform: scaleX(-1); }
    </style>
</head>
<body class="bg-animated-gradient text-gray-800 min-h-screen flex flex-col items-center justify-center p-4 relative" x-data="remoteAbsen()" x-init="initApp()">

    <!-- Tombol Kembali -->
    <a href="/" class="absolute top-6 left-6 flex items-center gap-2 text-gray-600 hover:text-red-600 transition-colors bg-white/70 px-5 py-2.5 border border-gray-200 shadow-sm rounded-full backdrop-blur-md font-bold text-sm z-40">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        <span class="hidden sm:inline">Kembali ke Menu</span>
    </a>

    <!-- Container Utama -->
    <div class="relative z-10 w-full max-w-lg bg-white/90 backdrop-blur-md p-6 sm:p-8 rounded-3xl shadow-xl border border-gray-100 mt-12 sm:mt-0">
        
        <div class="text-center mb-6">
            <h1 class="text-2xl font-extrabold text-gray-900 mb-1">Absensi Jarak Jauh</h1>
            <p class="text-sm text-gray-500 font-medium">Sistem Auto-Snap & Konfirmasi Lokasi GPS</p>
        </div>

        <form action="/absen/store" method="POST" id="formAbsen" @submit.prevent="submitForm">
            @csrf
            
            <!-- Pilih Karyawan -->
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pilih Nama Anda</label>
                <select name="employee_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 font-semibold text-gray-700 appearance-none">
                    <option value="">-- Ketuk untuk memilih --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Jenis Absen -->
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Jenis Kehadiran</label>
                <div class="grid grid-cols-2 gap-3">
                    
                    <!-- Opsi Masuk -->
                    <label class="cursor-pointer group">
                        <input type="radio" name="type" value="Masuk" class="peer sr-only" required>
                        <div class="p-3 border-2 border-gray-200 rounded-xl peer-checked:border-red-500 peer-checked:bg-red-50 text-gray-400 peer-checked:text-red-500 hover:bg-gray-50 hover:text-red-400 transition-all text-center h-full flex flex-col justify-center shadow-sm">
                            <svg class="w-8 h-8 mx-auto mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <!-- Icon Login / Masuk -->
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            <span class="font-bold text-sm text-gray-800">Masuk</span>
                        </div>
                    </label>
                    
                    <!-- Opsi Pulang -->
                    <label class="cursor-pointer group">
                        <input type="radio" name="type" value="Pulang" class="peer sr-only" required>
                        <div class="p-3 border-2 border-gray-200 rounded-xl peer-checked:border-red-500 peer-checked:bg-red-50 text-gray-400 peer-checked:text-red-500 hover:bg-gray-50 hover:text-red-400 transition-all text-center h-full flex flex-col justify-center shadow-sm">
                            <svg class="w-8 h-8 mx-auto mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <!-- Icon Logout / Pulang -->
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="font-bold text-sm text-gray-800">Pulang</span>
                        </div>
                    </label>

                </div>
            </div>

            <!-- Kamera Box (AUTO SNAP) -->
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Swafoto (Otomatis)</label>
                <div class="relative w-full h-64 bg-gray-100 rounded-2xl overflow-hidden border-2 border-gray-200 flex items-center justify-center group">
                    
                    <!-- Indikator Loading Kamera -->
                    <div x-show="!cameraReady && !photoTaken" class="text-gray-400 text-sm font-semibold flex flex-col items-center">
                        <svg class="animate-spin h-6 w-6 mb-2 text-red-500" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Menyiapkan Kamera...
                    </div>

                    <!-- Video Stream -->
                    <video x-ref="video" autoplay playsinline class="absolute inset-0 w-full h-full object-cover" x-show="!photoTaken && cameraReady"></video>
                    
                    <!-- Hitung Mundur Overlay di Tengah Video -->
                    <div x-show="countdown > 0 && !photoTaken" class="absolute inset-0 flex items-center justify-center bg-black/30 z-20">
                        <span class="text-white text-7xl font-extrabold drop-shadow-[0_4px_4px_rgba(0,0,0,0.5)] animate-ping" x-text="countdown"></span>
                    </div>

                    <!-- Hasil Foto -->
                    <img :src="fotoBase64" class="absolute inset-0 w-full h-full object-cover" x-show="photoTaken">
                    
                    <!-- Indikator Teks di Bawah -->
                    <div class="absolute bottom-4 left-0 right-0 flex justify-center z-30" x-show="cameraReady && !photoTaken">
                        <div class="bg-black/60 text-white px-4 py-2 rounded-full text-xs font-bold backdrop-blur-sm shadow-lg">
                            Otomatis mengambil foto dalam <span x-text="countdown" class="text-red-400 text-base ml-1"></span>
                        </div>
                    </div>

                    <!-- Tombol Ulang Foto (Muncul Setelah Foto Terambil) -->
                    <div class="absolute bottom-4 left-0 right-0 flex justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-30" x-show="photoTaken">
                        <button type="button" @click="retakePhoto" class="bg-gray-800 hover:bg-black text-white px-4 py-2 rounded-full shadow-lg text-sm font-bold flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Ulangi Foto
                        </button>
                    </div>
                </div>
            </div>

            <!-- Info Lokasi GPS -->
            <div class="mb-6 flex items-center gap-3 bg-gray-50 p-3 rounded-xl border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0" x-show="locationReady">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center flex-shrink-0 animate-pulse" x-show="!locationReady">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800" x-text="locationReady ? 'Lokasi Ditemukan' : 'Mencari Lokasi GPS...'"></h4>
                    <p class="text-[10px] text-gray-500 font-mono" x-show="locationReady" x-text="latitude + ', ' + longitude"></p>
                </div>
            </div>

            <!-- Hidden Inputs -->
            <input type="hidden" name="foto_bukti" x-model="fotoBase64">
            <input type="hidden" name="latitude" x-model="latitude">
            <input type="hidden" name="longitude" x-model="longitude">
            <canvas x-ref="canvas" style="display:none;"></canvas>

            <!-- Submit Button -->
            <button type="submit" :disabled="!photoTaken || !locationReady || isSubmitting" 
                    class="w-full text-white font-bold py-3.5 rounded-xl transition-all shadow-lg flex justify-center items-center gap-2"
                    :class="(!photoTaken || !locationReady || isSubmitting) ? 'bg-gray-300 cursor-not-allowed shadow-none' : 'bg-red-600 hover:bg-red-700 shadow-red-200 hover:-translate-y-0.5'">
                <span x-show="!isSubmitting">Kirim Absensi Saya</span>
                <span x-show="isSubmitting">Memproses...</span>
            </button>
        </form>
    </div>

    <!-- MODAL CONFETTI (Berhasil Absen) -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" x-transition.opacity.duration.300ms style="display: none;">
        <div class="bg-white text-gray-800 w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden transform transition-all text-center p-8 border-4"
             :class="tipeAbsen === 'Masuk' ? 'border-green-400' : 'border-blue-400'"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="w-24 h-24 mx-auto rounded-full flex items-center justify-center mb-6" :class="tipeAbsen === 'Masuk' ? 'bg-green-100 text-green-500' : 'bg-blue-100 text-blue-500'">
                <svg x-show="tipeAbsen === 'Masuk'" class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                <svg x-show="tipeAbsen === 'Pulang'" class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>

            <div x-show="tipeAbsen === 'Masuk'">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Halo, <span class="text-green-600" x-text="namaKaryawan"></span>! 👋</h2>
                <p class="text-gray-500 text-lg leading-relaxed">Selamat bekerja. Semangat terus kerjanya hari ini, mari ciptakan hal-hal hebat!</p>
            </div>

            <div x-show="tipeAbsen === 'Pulang'">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Kerja Bagus, <span class="text-blue-600" x-text="namaKaryawan"></span>! 🎉</h2>
                <p class="text-gray-500 text-lg leading-relaxed">Yeayy akhirnya pulang juga! Kerja keras yang luar biasa hari ini. Jangan lupa istirahat yang cukup dan makan ya.</p>
            </div>

            <div class="mt-8 bg-gray-50 py-3 rounded-xl border border-gray-100">
                <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">Status: </span>
                <span class="text-sm font-extrabold uppercase tracking-widest" :class="tipeAbsen === 'Masuk' ? 'text-green-600' : 'text-blue-600'" x-text="'ABSEN ' + tipeAbsen + ' BERHASIL'"></span>
            </div>
        </div>
    </div>

    <!-- MODAL ERROR / DITOLAK -->
    <div x-show="showErrorModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" x-transition.opacity.duration.300ms style="display: none;">
        <div @click.away="showErrorModal = false" class="bg-white text-gray-800 w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden transform transition-all text-center p-8 border-4 border-red-400 shadow-[0_0_50px_rgba(248,113,113,0.3)]"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-6 bg-red-100 text-red-500">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>

            <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Akses Ditolak!</h2>
            <p class="text-gray-500 text-sm leading-relaxed mb-6" x-text="errorMessage"></p>

            <button @click="showErrorModal = false" type="button" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-xl transition duration-200">
                Tutup Peringatan
            </button>
        </div>
    </div>

    <!-- SCRIPT ALPINE JS -->
    <script>
        function remoteAbsen() {
            return {
                cameraReady: false,
                photoTaken: false,
                fotoBase64: '',
                locationReady: false,
                latitude: '',
                longitude: '',
                isSubmitting: false,
                stream: null,
                
                // Tambahan state untuk Auto-Snap
                countdown: 0,
                autoSnapTimer: null,

                // State Modal Sukses & Error
                showModal: {{ session('absen_success') ? 'true' : 'false' }},
                showErrorModal: {{ $errors->any() ? 'true' : 'false' }},
                errorMessage: {!! json_encode($errors->first('pesan') ?? $errors->first()) !!},
                namaKaryawan: '{!! addslashes(session('absen_nama', '')) !!}',
                tipeAbsen: '{!! addslashes(session('absen_tipe', '')) !!}',

                initApp() {
                    // Cek jika muncul modal sukses
                    if (this.showModal) {
                        this.triggerConfetti();
                        setTimeout(() => { window.location.href = "/"; }, 5000);
                        return; 
                    }

                    // Inisiasi GPS & Kamera
                    this.getLocation();
                    this.startCamera();
                },

                startCamera() {
                    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
                        .then(stream => {
                            this.stream = stream;
                            this.$refs.video.srcObject = stream;
                            this.cameraReady = true;
                            
                            // Mulai hitung mundur otomatis begitu kamera siap
                            this.startCountdown();
                        })
                        .catch(err => {
                            alert("Kamera tidak dapat diakses. Pastikan Anda memberi izin kamera pada browser.");
                        });
                },

                startCountdown() {
                    if(this.autoSnapTimer) clearInterval(this.autoSnapTimer);
                    this.countdown = 3; // Mulai dari 3 detik
                    
                    this.autoSnapTimer = setInterval(() => {
                        this.countdown--;
                        if (this.countdown <= 0) {
                            clearInterval(this.autoSnapTimer);
                            this.takePhoto(); // Eksekusi jepret foto
                        }
                    }, 1000);
                },

                takePhoto() {
                    let video = this.$refs.video;
                    let canvas = this.$refs.canvas;
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    
                    let context = canvas.getContext('2d');
                    // Balik gambar di canvas agar hasilnya tidak mirror
                    context.translate(canvas.width, 0);
                    context.scale(-1, 1);
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    
                    this.fotoBase64 = canvas.toDataURL('image/png');
                    this.photoTaken = true;
                },

                retakePhoto() {
                    this.photoTaken = false;
                    this.fotoBase64 = '';
                    this.startCountdown(); // Jika klik ulang foto, hitung mundur 3 detik lagi
                },

                getLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                this.latitude = position.coords.latitude;
                                this.longitude = position.coords.longitude;
                                this.locationReady = true;
                            },
                            (error) => {
                                alert("Mohon izinkan akses Lokasi (GPS) untuk melakukan absensi.");
                            }
                        );
                    } else {
                        alert("Browser Anda tidak mendukung Geolocation.");
                    }
                },

                submitForm() {
                    if (!this.photoTaken) {
                        alert("Silakan ambil foto selfie terlebih dahulu.");
                        return;
                    }
                    this.isSubmitting = true;
                    if (this.stream) {
                        this.stream.getTracks().forEach(track => track.stop());
                    }
                    document.getElementById('formAbsen').submit();
                },

                closeModal() {
                    this.showModal = false;
                    window.location.href = "/";
                },

                triggerConfetti() {
                    if (this.tipeAbsen === 'Masuk') {
                        confetti({ particleCount: 150, spread: 80, origin: { y: 0.8 }, colors: ['#4ade80', '#22c55e', '#ffffff'] });
                    } else {
                        var end = Date.now() + 3000;
                        (function frame() {
                            confetti({ particleCount: 5, angle: 60, spread: 55, origin: { x: 0 }, colors: ['#60A5FA', '#F87171', '#FBBF24'] });
                            confetti({ particleCount: 5, angle: 120, spread: 55, origin: { x: 1 }, colors: ['#60A5FA', '#F87171', '#FBBF24'] });
                            if (Date.now() < end) requestAnimationFrame(frame);
                        }());
                    }
                }
            }
        }
    </script>
</body>
</html>