<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direktori Waktu Nyata | CatchTime</title>
    <link rel="icon" href="{{ asset('images/Logo_icon.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- Pastikan Alpine.js dipanggil -->
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
        
        /* Transisi halus saat list berubah */
        .fade-item-enter-active, .fade-item-leave-active { transition: all 0.3s ease; }
        .fade-item-enter-from, .fade-item-leave-to { opacity: 0; transform: translateY(10px); }
    </style>
</head>
<body class="bg-catchtime-gradient text-gray-800 min-h-screen flex flex-col items-center p-4 relative py-20">

    <!-- Tombol Kembali -->
    <a href="/" class="absolute top-6 left-6 flex items-center gap-2 text-gray-600 hover:text-[#0033a0] transition-colors bg-white/70 px-5 py-2.5 border border-gray-200 shadow-sm rounded-full backdrop-blur-md font-bold text-sm z-40">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        <span class="hidden sm:inline">Kembali ke Menu</span>
    </a>

    <!-- Deklarasikan AlpineJS di Container Utama -->
    <div class="relative z-10 w-full max-w-4xl bg-white/90 backdrop-blur-md p-6 sm:p-8 rounded-3xl shadow-xl border border-gray-100 mt-12 sm:mt-0" 
         x-data="direktoriLive()" x-init="initData()">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0033a0] mb-2 tracking-tight">Direktori Karyawan</h1>
            <p class="text-sm sm:text-base text-gray-500 font-medium">Cek status ketersediaan karyawan di kantor hari ini secara <span class="text-[#0033a0] font-bold animate-pulse">real-time</span>.</p>
        </div>

        <!-- Form Pencarian (Instan/Tanpa Submit) -->
        <div class="mb-8 relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <!-- Menggunakan x-model agar filter langsung bereaksi saat diketik -->
            <input type="text" x-model="search" placeholder="Ketik nama karyawan..." class="w-full pl-11 pr-4 py-4 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#0033a0] font-semibold text-gray-700 transition-all outline-none shadow-sm">
        </div>

        <!-- Indikator Loading Saat Pertama Buka -->
        <div x-show="isLoading" class="flex justify-center py-10">
            <svg class="animate-spin h-8 w-8 text-[#0033a0]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </div>

        <!-- Hasil / Daftar Karyawan (Looping dengan Alpine) -->
        <div x-show="!isLoading" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            
            <template x-for="emp in filteredEmployees" :key="emp.id">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg mb-1" x-text="emp.nama_lengkap"></h3>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-wider" x-text="emp.jabatan_posisi"></p>
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-4 py-1.5 text-xs font-extrabold rounded-full border transition-colors duration-500" 
                              :class="emp.status_color" 
                              x-text="emp.status_kehadiran">
                        </span>
                    </div>
                </div>
            </template>

            <!-- Tampilan Jika Karyawan Tidak Ditemukan -->
            <div x-show="filteredEmployees.length === 0" class="col-span-1 sm:col-span-2 bg-gray-50 p-10 rounded-2xl text-center border-2 border-gray-200 border-dashed flex flex-col items-center justify-center mt-4">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-700 mb-1">Data Tidak Ditemukan</h3>
                <p class="text-gray-500 text-sm">Coba gunakan kata kunci nama yang lain.</p>
            </div>
        </div>
    </div>

    <!-- SCRIPT LOGIKA LIVE -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('direktoriLive', () => ({
                search: '',
                employees: [],
                isLoading: true,
                
                initData() {
                    this.fetchData();
                    
                    // Fitur Ajaib: Menarik data terbaru otomatis setiap 3 detik!
                    setInterval(() => {
                        this.fetchData();
                    }, 3000); 
                },
                
                fetchData() {
                    fetch('/api/live-kehadiran')
                        .then(response => response.json())
                        .then(data => {
                            this.employees = data;
                            this.isLoading = false;
                        })
                        .catch(error => console.error('Error:', error));
                },
                
                // Fitur Pencarian Instan (Langsung saring data tanpa loading)
                get filteredEmployees() {
                    if (this.search === '') {
                        return this.employees;
                    }
                    return this.employees.filter(emp => {
                        return emp.nama_lengkap.toLowerCase().includes(this.search.toLowerCase());
                    });
                }
            }))
        })
    </script>
</body>
</html>