<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu Digital | DigiBAR Group</title>
    <link rel="icon" href="{{ asset('images/Logo_icon.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Library Canvas Confetti -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Animasi Background Gradient Halus (Disamakan 100% dengan Absen WFA & RFID) */
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
<body class="bg-animated-gradient text-gray-800 antialiased selection:bg-red-500 selection:text-white min-h-screen flex flex-col items-center justify-center p-4 relative" x-data="bukuTamuApp()" x-init="initApp()">

    <!-- Tombol Kembali -->
    <a href="/" class="absolute top-6 left-6 flex items-center gap-2 text-gray-600 hover:text-red-600 transition-colors bg-white/70 px-5 py-2.5 border border-gray-200 shadow-sm rounded-full backdrop-blur-md font-bold text-sm z-40">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        <span class="hidden sm:inline">Kembali ke Menu</span>
    </a>

    <!-- Container Utama Form -->
    <div class="relative z-10 w-full max-w-lg bg-white/90 backdrop-blur-md p-6 sm:p-8 rounded-3xl shadow-xl border border-gray-100 mt-12 sm:mt-0">
        
        <div class="text-center mb-8">
            <!-- Icon diubah ke nuansa merah khas DigiBAR -->
            <div class="w-16 h-16 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Buku Tamu DigiBAR</h1>
            <p class="text-sm text-gray-500 font-medium">Halo! Selamat datang. Biar makin akrab, isi buku tamu dulu yuk sebelum masuk. 👋</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 text-red-600 text-sm p-4 rounded-xl mb-6 border border-red-100 font-semibold flex items-start gap-3">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div>
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="/buku-tamu/store" method="POST" id="formBukuTamu" @submit="isSubmitting = true">
            @csrf
            
            <div class="space-y-5">
                <!-- Nama Tamu -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input type="text" name="nama_tamu" required placeholder="Contoh: Budi Santoso" 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 font-medium text-gray-800 transition-shadow">
                </div>

                <!-- Instansi / Asal -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Instansi / Asal (Opsional)</label>
                    <input type="text" name="instansi_asal" placeholder="Contoh: PT Mencari Cinta Sejati / Personal" 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 font-medium text-gray-800 transition-shadow">
                </div>

                <!-- Nomor WhatsApp -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nomor WhatsApp Aktif</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-400 font-medium text-sm">WA</span>
                        </div>
                        <input type="number" name="no_hp" required placeholder="08123456789" 
                               class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 font-medium text-gray-800 transition-shadow">
                    </div>
                </div>

                <!-- Tujuan / Keperluan -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Keperluan Kunjungan</label>
                    <textarea name="tujuan_keperluan" required rows="3" placeholder="Contoh: Mau meeting bahas project bareng Kak Bastian..." 
                              class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 font-medium text-gray-800 transition-shadow resize-none"></textarea>
                </div>
            </div>

            <!-- Submit Button (Disamakan dengan tombol WFA) -->
            <button type="submit" :disabled="isSubmitting" 
                    class="w-full mt-8 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg flex justify-center items-center gap-2"
                    :class="isSubmitting ? 'bg-gray-300 cursor-not-allowed shadow-none' : 'bg-red-600 hover:bg-red-700 shadow-red-200 hover:-translate-y-0.5'">
                <span x-show="!isSubmitting">Check-In Sekarang</span>
                <span x-show="isSubmitting" style="display: none;" class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Mendaftarkan...
                </span>
            </button>
        </form>
    </div>

    <!-- MODAL CONFETTI & SUKSES (Warna hijau tetap dipertahankan khusus untuk notifikasi SUKSES) -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" x-transition.opacity.duration.300ms style="display: none;">
        <div class="bg-white text-gray-800 w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden transform transition-all text-center p-8 border-4 border-green-400"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-6 bg-green-100 text-green-500">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>

            <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Registrasi Sukses! 🎉</h2>
            <p class="text-gray-500 text-sm leading-relaxed mb-6">Selamat Datang di kantor DigiBAR Group</p>

            <button @click="closeModal()" type="button" class="w-full bg-green-50 hover:bg-green-100 text-green-700 font-bold py-3 rounded-xl transition duration-200">
                Siap, Tutup!
            </button>
        </div>
    </div>

    <!-- SCRIPT ALPINE JS -->
    <script>
        function bukuTamuApp() {
            return {
                isSubmitting: false,
                showModal: {{ session('success') ? 'true' : 'false' }},

                initApp() {
                    // Cek jika muncul modal sukses dari Session Controller
                    if (this.showModal) {
                        this.triggerConfetti();
                    }
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
                            particleCount: 5,
                            angle: 60,
                            spread: 55,
                            origin: { x: 0 },
                            colors: ['#EF4444', '#F97316', '#FBBF24'] // Confetti disesuaikan dengan warna DigiBAR (Merah/Orange/Kuning)
                        });
                        confetti({
                            particleCount: 5,
                            angle: 120,
                            spread: 55,
                            origin: { x: 1 },
                            colors: ['#EF4444', '#F97316', '#FBBF24']
                        });

                        if (Date.now() < end) {
                            requestAnimationFrame(frame);
                        }
                    }());
                }
            }
        }
    </script>
</body>
</html>