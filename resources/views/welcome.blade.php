<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal MSDM | CatchTime</title>
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
<body class="bg-catchtime-gradient text-gray-800 antialiased selection:bg-[#0033a0] selection:text-white min-h-screen flex flex-col relative overflow-x-hidden">

    @php
        // Logika sapaan otomatis
        $jam = now()->timezone('Asia/Jakarta')->format('H');
        if ($jam >= 5 && $jam < 11) {
            $sapaan = 'Selamat Pagi! ☀️';
        } elseif ($jam >= 11 && $jam < 15) {
            $sapaan = 'Selamat Siang! 🌤️';
        } elseif ($jam >= 15 && $jam < 18) {
            $sapaan = 'Selamat Sore! 🌅';
        } else {
            $sapaan = 'Selamat Malam! 🌙';
        }

        // Kumpulan Quotes Harian
        $quotes = [
            "Fokuslah hanya pada apa yang bisa kamu kendalikan. Epictetus",
            "Selesai itu lebih baik daripada sempurna. Sheryl Sandberg",
            "Cara terbaik untuk memulai adalah berhenti bicara dan mulai bertindak. Walt Disney",
            "Kamu tidak harus hebat untuk memulai, tapi kamu harus memulai untuk menjadi hebat. Zig Ziglar",
            "Cara terbaik untuk memprediksi masa depan adalah dengan menciptakannya. Peter Drucker",
            "Hampir semuanya akan berfungsi lagi jika dicabut sejenak, termasuk dirimu. Anne Lamott",
            "Tidak apa-apa untuk beristirahat, asal jangan menyerah. Banksy",
            "Jangan berhenti saat kamu lelah. Berhentilah saat kamu selesai. David Goggins"
        ];
        
        $hariIni = date('z'); 
        $quoteHariIni = $quotes[$hariIni % count($quotes)];
    @endphp

    <!-- MAIN CONTENT CENTERED -->
    <main class="flex-grow flex flex-col justify-center items-center py-12 px-4 sm:px-6 z-10 w-full">
        
        <!-- HEADER TEXT & WAKTU -->
        <div class="text-center flex flex-col items-center mb-10">
            <h1 class="text-3xl sm:text-4xl md:text-[40px] font-extrabold text-gray-900 mb-5 tracking-tight">
                {{ $sapaan }}, <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#0033a0] to-blue-500">CatchTime Crew!</span>
            </h1>
            
            <div class="bg-white px-8 py-3 rounded-full shadow-sm text-sm font-bold text-gray-700 border border-white/80 mb-5">
                "{{ $quoteHariIni }}"
            </div>

            <!-- KOMPONEN WAKTU RESPONSIVE (Disesuaikan dengan Screenshot) -->
            <div class="flex items-center gap-4 sm:gap-6 bg-white px-6 sm:px-8 py-3 sm:py-4 rounded-2xl sm:rounded-full shadow-sm border border-gray-100" 
                 x-data="{ waktu: '', tanggal: '' }" 
                 x-init="
                    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                    tanggal = new Date().toLocaleDateString('id-ID', options);
                    setInterval(() => { waktu = new Date().toLocaleTimeString('id-ID', { hour12: false }) + ' WIB' }, 1000); 
                    waktu = new Date().toLocaleTimeString('id-ID', { hour12: false }) + ' WIB';
                 ">
                
                <!-- Tanggal -->
                <div class="flex items-start sm:items-center gap-3">
                    <svg class="w-5 h-5 text-green-600 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <!-- Trik memecah teks pada mobile dengan max-width dan text-center -->
                    <span class="text-sm font-semibold text-gray-600 w-[120px] sm:w-auto text-center sm:text-left leading-snug" x-text="tanggal"></span>
                </div>
                
                <!-- Garis Vertikal Pemisah -->
                <div class="w-px h-10 sm:h-5 bg-gray-200"></div>
                
                <!-- Jam -->
                <div class="flex items-start sm:items-center gap-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div class="text-sm font-extrabold text-gray-900 tracking-wide flex flex-col sm:flex-row items-center sm:gap-1 text-center sm:text-left">
                        <span x-text="waktu.split(' ')[0]"></span>
                        <span x-text="waktu.split(' ')[1]"></span>
                    </div>
                </div>
            </div>

        </div>

        <!-- GRID MENU (2 KOLOM - RAPI DI BAWAH) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-4xl mx-auto">
            
            <!-- Card 1: Absensi Kantor -->
            <a href="{{ route('rfid.index') }}" class="bg-white p-7 rounded-[28px] shadow-sm hover:shadow-md border border-transparent hover:border-[#0033a0]/20 transition-all transform hover:-translate-y-1 flex items-start gap-5">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#0033a0] flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </div>
                <div>
                    <h2 class="text-[17px] font-bold text-gray-900 mb-1.5">Absensi Kantor</h2>
                    <p class="text-[13px] text-gray-500 leading-relaxed font-medium">Udah nyampe di kantor? Jangan lupa tap kartu ID di scanner resepsionis ya biar tercatat.</p>
                </div>
            </a>

            <!-- Card 2: Absen Remote / WFA -->
            <a href="/absen" class="bg-white p-7 rounded-[28px] shadow-sm hover:shadow-md border border-transparent hover:border-[#0033a0]/20 transition-all transform hover:-translate-y-1 flex items-start gap-5">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#0033a0] flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <div>
                    <h2 class="text-[17px] font-bold text-gray-900 mb-1.5">Absen Remote / WFA</h2>
                    <p class="text-[13px] text-gray-500 leading-relaxed font-medium">Lagi kerja di luar, WFA, atau lapangan? absen disini aja.</p>
                </div>
            </a>

            <!-- Card 3: Cek Kehadiran (Direktori) -->
            <a href="/cek-kehadiran" class="bg-white p-7 rounded-[28px] shadow-sm hover:shadow-md border border-transparent hover:border-[#0033a0]/20 transition-all transform hover:-translate-y-1 flex items-start gap-5">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#0033a0] flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                </div>
                <div>
                    <h2 class="text-[17px] font-bold text-gray-900 mb-1.5">Cek Kehadiran (Direktori)</h2>
                    <p class="text-[13px] text-gray-500 leading-relaxed font-medium">Ingin bertemu karyawan? Cek dulu apakah orangnya sedang ada di kantor hari ini.</p>
                </div>
            </a>

            <!-- Card 4: Buku Tamu -->
            <a href="/buku-tamu-form" class="bg-white p-7 rounded-[28px] shadow-sm hover:shadow-md border border-transparent hover:border-[#0033a0]/20 transition-all transform hover:-translate-y-1 flex items-start gap-5">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#0033a0] flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                </div>
                <div>
                    <h2 class="text-[17px] font-bold text-gray-900 mb-1.5">Buku Tamu</h2>
                    <p class="text-[13px] text-gray-500 leading-relaxed font-medium">Mohon daftarkan data Anda untuk rekap kunjungan.</p>
                </div>
            </a>

            <!-- Card 5: Pengajuan Izin / Cuti (Diletakkan Pas di Tengah Bawah Layar PC) -->
            <div class="md:col-span-2 flex justify-center mt-2">
                <a href="/cuti" class="bg-white p-7 rounded-[28px] shadow-sm hover:shadow-md border border-transparent hover:border-[#0033a0]/20 transition-all transform hover:-translate-y-1 flex items-start gap-5 w-full md:w-[calc(50%-0.75rem)]">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#0033a0] flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <div>
                        <h2 class="text-[17px] font-bold text-gray-900 mb-1.5">Pengajuan Izin / Cuti</h2>
                        <p class="text-[13px] text-gray-500 leading-relaxed font-medium">Butuh istirahat, lagi sakit, atau ada urusan dadakan? Request izin dari sini.</p>
                    </div>
                </a>
            </div>

        </div>
    </main>

    <!-- FLOATING WHATSAPP BUTTON (Lapor Error) -->
    <a href="https://wa.me/628559000857?text=Halo%20Kak%20Bastian%2C%20mau%20lapor%20nemu%20error%20nih%20di%20portal%20MSDM%20CatchTime.%20Bisa%20tolong%20dicek%3F" 
       target="_blank"
       class="fixed bottom-6 right-6 z-50 flex items-center justify-center gap-2 bg-[#25D366] hover:bg-[#1ebd5a] text-white px-5 py-3 rounded-full shadow-lg hover:-translate-y-1 transition-all duration-300">
        <!-- Icon WA -->
        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.88-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
        <span class="font-bold text-sm">Lapor Error</span>
    </a>

</body>
</html>