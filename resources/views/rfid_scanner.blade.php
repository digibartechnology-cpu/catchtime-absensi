<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiosk Absensi RFID | DigiBAR</title>
    <link rel="icon" href="{{ asset('images/Logo_icon.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Library Canvas Confetti -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; overflow: hidden; }
        
        /* Background konsisten dengan halaman Welcome */
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

        /* Animasi radar mutar */
        .radar-scan {
            animation: scan 3s linear infinite;
        }
        @keyframes scan {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-animated-gradient text-gray-800 min-h-screen flex flex-col items-center justify-center relative"
      x-data="rfidKiosk()" 
      x-init="initKiosk()">

    <!-- Tombol Kembali ke Home (Pojok Kiri Atas) -->
    <a href="/" class="absolute top-6 left-6 flex items-center gap-2 text-gray-600 hover:text-red-600 transition-colors bg-white/70 px-5 py-2.5 border border-gray-200 shadow-sm rounded-full backdrop-blur-md font-bold text-sm">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Menu
    </a>

    <!-- UI Scanner Tengah -->
    <div class="relative z-10 flex flex-col items-center text-center mt-8">

        <!-- Lingkaran Radar Scanner (Disesuaikan dengan warna merah/orange DigiBAR) -->
        <div class="relative w-64 h-64 flex items-center justify-center mb-8">
            <div class="absolute inset-0 border-4 border-red-500/20 rounded-full shadow-[0_0_30px_rgba(239,68,68,0.1)]"></div>
            <div class="absolute inset-4 border border-red-400/30 rounded-full"></div>
            <div class="absolute inset-0 rounded-full border-t-4 border-red-500 radar-scan shadow-[0_0_15px_rgba(239,68,68,0.4)]"></div>
            
            <div class="bg-white/90 p-6 rounded-full backdrop-blur-md shadow-xl border border-red-100">
                <!-- Icon RFID Card -->
                <svg class="w-20 h-20 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v.01M16 4v.01M8 4v.01"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 text-gray-900 drop-shadow-sm">
            SIAPKAN KARTU ID ANDA
        </h1>
        <p class="text-gray-600 text-lg md:text-xl font-medium tracking-wide">
            Silakan Tap/Tempelkan Kartu pada Alat Scanner
        </p>

        <!-- Pesan Error (jika kartu belum terdaftar) -->
        @if(session('error'))
            <div class="mt-6 bg-red-100 border border-red-400 text-red-700 px-6 py-3 rounded-xl font-bold animate-bounce shadow-md">
                {{ session('error') }}
            </div>
        @endif

        <!-- Input Tersembunyi untuk menangkap ketikan dari alat RFID -->
        <!-- PERBAIKAN: Action disamakan dengan route yang ada di web.php -->
        <form action="/absen-kantor/scan" method="POST" id="rfidForm" class="mt-8 opacity-0">
            @csrf
            <input type="text" name="uid" id="rfidInput" x-ref="rfidInput" class="text-black" autocomplete="off">
        </form>
    </div>

    <!-- POP UP BERHASIL (MUNCUL JIKA ADA SESSION SUCCESS) -->
    <div x-show="showModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4"
         x-transition.opacity.duration.300ms
         style="display: none;">
        
        <div @click.away="closeModal()" 
             class="bg-white text-gray-800 w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden transform transition-all text-center p-8 border-4"
             :class="tipeAbsen === 'Masuk' ? 'border-green-400 shadow-[0_0_50px_rgba(74,222,128,0.3)]' : 'border-blue-400 shadow-[0_0_50px_rgba(96,165,250,0.3)]'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <!-- Icon -->
            <div class="w-24 h-24 mx-auto rounded-full flex items-center justify-center mb-6"
                 :class="tipeAbsen === 'Masuk' ? 'bg-green-100 text-green-500' : 'bg-blue-100 text-blue-500'">
                <!-- Icon Masuk (Gedung/Ceklis) -->
                <svg x-show="tipeAbsen === 'Masuk'" class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                <!-- Icon Pulang (Rumah/Bulan) -->
                <svg x-show="tipeAbsen === 'Pulang'" class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>

            <!-- Pesan Masuk -->
            <div x-show="tipeAbsen === 'Masuk'">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Halo, <span class="text-green-600" x-text="namaKaryawan"></span>! 👋</h2>
                <p class="text-gray-500 text-lg leading-relaxed">Selamat datang di kantor. Semangat terus kerjanya hari ini, mari ciptakan hal-hal hebat!</p>
            </div>

            <!-- Pesan Pulang -->
            <div x-show="tipeAbsen === 'Pulang'">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Kerja Bagus, <span class="text-blue-600" x-text="namaKaryawan"></span>! 🎉</h2>
                <p class="text-gray-500 text-lg leading-relaxed">Yeayy akhirnya pulang juga! Kerja keras yang luar biasa hari ini. Jangan lupa istirahat yang cukup dan makan ya.</p>
            </div>

            <!-- Teks Status -->
            <div class="mt-8 bg-gray-50 py-3 rounded-xl border border-gray-100">
                <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">Status: </span>
                <span class="text-sm font-extrabold uppercase tracking-widest"
                      :class="tipeAbsen === 'Masuk' ? 'text-green-600' : 'text-blue-600'"
                      x-text="'ABSEN ' + tipeAbsen + ' BERHASIL'"></span>
            </div>

        </div>
    </div>

    <!-- Script Logika Scanner & Confetti -->
    <script>
        function rfidKiosk() {
            return {
                showModal: {{ session('rfid_success') ? 'true' : 'false' }},
                namaKaryawan: '{{ session('rfid_nama', '') }}',
                tipeAbsen: '{{ session('rfid_tipe', '') }}', 
                timeoutId: null,

                initKiosk() {
                    this.keepFocus();
                    document.addEventListener('click', () => this.keepFocus());

                    if (this.showModal) {
                        this.triggerConfetti();
                        this.timeoutId = setTimeout(() => {
                            this.closeModal();
                        }, 5000);
                    }

                    this.$refs.rfidInput.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter' && this.$refs.rfidInput.value.length > 3) {
                            document.getElementById('rfidForm').submit();
                        }
                    });
                },

                keepFocus() {
                    if (!this.showModal) {
                        this.$refs.rfidInput.focus();
                    }
                },

                closeModal() {
                    this.showModal = false;
                    clearTimeout(this.timeoutId);
                    this.$refs.rfidInput.value = ''; 
                    setTimeout(() => { this.keepFocus(); }, 100);
                },

                triggerConfetti() {
                    if (this.tipeAbsen === 'Masuk') {
                        confetti({
                            particleCount: 150,
                            spread: 80,
                            origin: { y: 0.8 },
                            colors: ['#4ade80', '#22c55e', '#16a34a', '#ffffff']
                        });
                    } else if (this.tipeAbsen === 'Pulang') {
                        var duration = 3000;
                        var end = Date.now() + duration;

                        (function frame() {
                            confetti({
                                particleCount: 5, angle: 60, spread: 55, origin: { x: 0 },
                                colors: ['#60A5FA', '#F87171', '#FBBF24']
                            });
                            confetti({
                                particleCount: 5, angle: 120, spread: 55, origin: { x: 1 },
                                colors: ['#60A5FA', '#F87171', '#FBBF24']
                            });
                            if (Date.now() < end) requestAnimationFrame(frame);
                        }());
                    }
                }
            }
        }
    </script>
</body>
</html>