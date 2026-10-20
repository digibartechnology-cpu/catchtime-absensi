<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | CatchTime</title>
    <link rel="icon" href="{{ asset('images/Logo_icon.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
<body class="bg-catchtime-gradient text-gray-800 antialiased selection:bg-[#0033a0] selection:text-white min-h-screen flex flex-col items-center justify-center p-4 relative" x-data="{ isSubmitting: false, showPassword: false }">

    <!-- Tombol Kembali -->
    <a href="/" class="absolute top-6 left-6 flex items-center gap-2 text-gray-600 hover:text-[#0033a0] transition-colors bg-white/70 px-5 py-2.5 border border-gray-200 shadow-sm rounded-full backdrop-blur-md font-bold text-sm z-40">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        <span class="hidden sm:inline">Kembali ke Portal</span>
    </a>

    <!-- Container Utama Form -->
    <div class="relative z-10 w-full max-w-md bg-white/90 backdrop-blur-md p-8 sm:p-10 rounded-3xl shadow-xl border border-gray-100 mt-12 sm:mt-0">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-50 text-[#0033a0] rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900 mb-1">Login Dashboard Admin</h2>
            <p class="text-sm text-gray-500 font-medium">Silakan login untuk membuka dashboard MSDM</p>
        </div>

        <!-- Tampilkan Pesan Error Jika Login Gagal -->
        @error('username')
            <div class="bg-red-50 text-red-600 text-sm p-4 rounded-xl mb-6 border border-red-100 font-semibold flex items-start gap-3 animate-pulse">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div>
                    <p>{{ $message }}</p>
                </div>
            </div>
        @enderror

        <form action="/login" method="POST" @submit="isSubmitting = true">
            @csrf
            
            <div class="space-y-5">
                <!-- Username -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" for="username">
                        Username
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0033a0] font-medium text-gray-800 transition-shadow" 
                               id="username" name="username" type="text" placeholder="Masukkan username..." value="{{ old('username') }}" required autofocus>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" for="password">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        </div>
                        <input :type="showPassword ? 'text' : 'password'" 
                               class="w-full pl-11 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0033a0] font-medium text-gray-800 transition-shadow" 
                               id="password" name="password" placeholder="••••••••" required>
                        
                        <!-- Toggle Show/Hide Password -->
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#0033a0] focus:outline-none transition-colors">
                            <!-- Icon Eye Close -->
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            <!-- Icon Eye Open -->
                            <svg x-show="showPassword" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" :disabled="isSubmitting" 
                    class="w-full mt-8 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg flex justify-center items-center gap-2"
                    :class="isSubmitting ? 'bg-gray-300 cursor-not-allowed shadow-none' : 'bg-[#0033a0] hover:bg-blue-900 shadow-blue-200 hover:-translate-y-0.5'">
                <span x-show="!isSubmitting">Masuk ke Dasbor</span>
                <span x-show="isSubmitting" style="display: none;" class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Memeriksa...
                </span>
            </button>
        </form>
    </div>

</body>
</html>