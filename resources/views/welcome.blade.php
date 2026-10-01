<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal MSDM | DigiBAR Group</title>
    <link rel="icon" href="{{ asset('images/Logo_icon.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Animasi Background Gradient Halus */
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
<body class="bg-animated-gradient text-gray-800 antialiased selection:bg-red-500 selection:text-white flex flex-col min-h-screen">

    @php
        // Logika sapaan otomatis pembakar semangat ala Gen Z / Millennial
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

        // Kumpulan Quotes Harian (Akan berganti otomatis setiap hari)
        $quotes = [
            "Selesai itu lebih baik daripada sempurna. Sheryl Sandberg",
            "Cara terbaik untuk memulai adalah berhenti bicara dan mulai bertindak. Walt Disney",
            "Kamu tidak harus hebat untuk memulai, tapi kamu harus memulai untuk menjadi hebat. Zig Ziglar",
            "Cara terbaik untuk memprediksi masa depan adalah dengan menciptakannya.  Peter Drucker",
            "Hampir semuanya akan berfungsi lagi jika dicabut sejenak, termasuk dirimu.  Anne Lamott",
            "Fokuslah hanya pada apa yang bisa kamu kendalikan.  Epictetus",
            "Tidak apa-apa untuk beristirahat, asal jangan menyerah.  Banksy",
            "Jangan berhenti saat kamu lelah. Berhentilah saat kamu selesai.  David Goggins",
            "Kamu tidak naik ke level tujuanmu, kamu jatuh ke level kebiasaanmu.  James Clear",
            "Kerja keras mengalahkan bakat ketika bakat tidak bekerja keras.  Tim Notke",
            "Satu-satunya cara untuk melakukan pekerjaan hebat adalah dengan mencintai apa yang kamu lakukan.  Steve Jobs"
        ];
        
        // Ambil hari ke-sekian dalam setahun, lalu modulus dengan total array
        $hariIni = date('z'); 
        $quoteHariIni = $quotes[$hariIni % count($quotes)];
    @endphp

    <!-- MAIN CONTENT (Centered without Navbar & Logo) -->
    <main class="flex-grow flex flex-col justify-center items-center py-12 px-4 sm:px-6 relative overflow-hidden">
        
        <div class="max-w-5xl w-full mx-auto relative z-10">
            
            <!-- HERO SECTION -->
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4 tracking-tight">
                    {{ $sapaan }}, <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-500">DigiBAR Crew!</span>
                </h1>
                
                <!-- Quote of the Day Dinamis -->
                <p class="text-gray-600 font-medium text-lg max-w-2xl mx-auto mb-8 bg-white/40 inline-block px-6 py-2 rounded-full backdrop-blur-sm border border-white/50">
                    "{{ $quoteHariIni }}"
                </p>
                
                <br>

                <!-- Tanggal & Jam Live AlpineJS -->
                <div class="inline-flex items-center justify-center gap-4 bg-white/80 backdrop-blur-sm px-6 py-3 rounded-2xl shadow-sm border border-gray-100" 
                     x-data="{ waktu: '', tanggal: '' }" 
                     x-init="
                        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                        tanggal = new Date().toLocaleDateString('id-ID', options);
                        setInterval(() => { waktu = new Date().toLocaleTimeString('id-ID', { hour12: false }) + ' WIB' }, 1000); 
                        waktu = new Date().toLocaleTimeString('id-ID', { hour12: false }) + ' WIB';
                     ">
                    <div class="flex items-center gap-2 text-gray-500 font-semibold">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span x-text="tanggal"></span>
                    </div>
                    <div class="w-px h-5 bg-gray-300"></div>
                    <div class="flex items-center gap-2 text-gray-800 font-bold tracking-wider">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span x-text="waktu"></span>
                    </div>
                </div>
            </div>

            <!-- GRID MENU -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 justify-center">
                
                <!-- Card 1: Absensi Kantor (Sembunyi di HP, Muncul di PC) -->
                <a href="{{ route('rfid.index') }}" class="hidden md:flex group relative bg-white/90 backdrop-blur-md p-8 rounded-3xl shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 transform hover:-translate-y-1 overflow-hidden items-start gap-6">
                    <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <!-- Icon Gedung / Office Outline -->
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-purple-700 transition-colors">Absen Kantor</h2>
                        <p class="text-sm text-gray-500 leading-relaxed mb-4">Udah nyampe di kantor? Jangan lupa tap kartu ID di scanner resepsionis ya biar tercatat.</p>
                        <span class="text-purple-600 font-bold text-sm flex items-center gap-1 opacity-0 group-hover:opacity-100 transform translate-x-[-10px] group-hover:translate-x-0 transition-all duration-300">
                            Buka Layar Scanner &rarr;
                        </span>
                    </div>
                </a>

                <!-- Card 2: Absen Lapangan -->
                <a href="/absen" class="group relative bg-white/90 backdrop-blur-md p-8 rounded-3xl shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 transform hover:-translate-y-1 overflow-hidden flex items-start gap-6">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <!-- Icon Location Map Outline -->
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-700 transition-colors">Absen Remote / WFA</h2>
                        <p class="text-sm text-gray-500 leading-relaxed mb-4">Lagi kerja di luar, WFA, atau lapangan? absen disini aja.</p>
                        <span class="text-blue-600 font-bold text-sm flex items-center gap-1 opacity-0 group-hover:opacity-100 transform translate-x-[-10px] group-hover:translate-x-0 transition-all duration-300">
                            Mulai Absen Sekarang &rarr;
                        </span>
                    </div>
                </a>

                <!-- Card 3: Direktori / Cek Kehadiran (BARU) -->
                <a href="/cek-kehadiran" class="group relative bg-white/90 backdrop-blur-md p-8 rounded-3xl shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 transform hover:-translate-y-1 overflow-hidden flex items-start gap-6">
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0 group-hover:bg-rose-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <!-- Icon Users / ID Card Outline -->
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-rose-700 transition-colors">Cek Kehadiran (Direktori)</h2>
                        <p class="text-sm text-gray-500 leading-relaxed mb-4">Ingin bertemu karyawan? Cek dulu apakah orangnya sedang ada di kantor hari ini.</p>
                        <span class="text-rose-600 font-bold text-sm flex items-center gap-1 opacity-0 group-hover:opacity-100 transform translate-x-[-10px] group-hover:translate-x-0 transition-all duration-300">
                            Cari Karyawan &rarr;
                        </span>
                    </div>
                </a>

                <!-- Card 4: Buku Tamu (Pindah ke bawah tengah di PC) -->
                <a href="/buku-tamu-form" class="lg:col-start-1 lg:col-end-3 lg:w-[calc(150%+1rem)] lg:justify-self-end group relative bg-white/90 backdrop-blur-md p-8 rounded-3xl shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 transform hover:-translate-y-1 overflow-hidden flex items-start gap-6">
                    <div class="w-14 h-14 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0 group-hover:bg-green-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <!-- Icon Book Open Outline -->
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-green-700 transition-colors">Buku Tamu</h2>
                        <p class="text-sm text-gray-500 leading-relaxed mb-4">Mohon daftarkan data Anda untuk rekap kunjungan.</p>
                        <span class="text-green-600 font-bold text-sm flex items-center gap-1 opacity-0 group-hover:opacity-100 transform translate-x-[-10px] group-hover:translate-x-0 transition-all duration-300">
                            Isi Data Kunjungan &rarr;
                        </span>
                    </div>
                </a>

                <!-- Card 5: Pengajuan Cuti (Pindah ke bawah tengah di PC) -->
                <a href="/cuti" class="lg:col-start-2 lg:col-end-4 lg:w-[calc(150%+1rem)] lg:justify-self-start lg:ml-[calc(-50%-1rem)] group relative bg-white/90 backdrop-blur-md p-8 rounded-3xl shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 transform hover:-translate-y-1 overflow-hidden flex items-start gap-6">
                    <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center flex-shrink-0 group-hover:bg-orange-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <!-- Icon Calendar Outline -->
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-orange-700 transition-colors">Pengajuan Izin / Cuti</h2>
                        <p class="text-sm text-gray-500 leading-relaxed mb-4">Butuh istirahat, lagi sakit, atau ada urusan dadakan? Request izin dari sini.</p>
                        <span class="text-orange-600 font-bold text-sm flex items-center gap-1 opacity-0 group-hover:opacity-100 transform translate-x-[-10px] group-hover:translate-x-0 transition-all duration-300">
                            Ajukan Formulir &rarr;
                        </span>
                    </div>
                </a>

            </div>
        </div>
    </main>

    <!-- FLOATING WHATSAPP BUTTON (Lapor Error) -->
    <a href="https://wa.me/628559000857?text=Halo%20Kak%20Bastian%2C%20mau%20lapor%20nemu%20error%20nih%20di%20portal%20MSDM%20DigiBAR.%20Bisa%20tolong%20dicek%3F" 
       target="_blank"
       class="fixed bottom-6 right-6 z-50 flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white p-4 sm:px-5 sm:py-3 rounded-full shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 group">
        
        <!-- Icon WA -->
        <svg class="w-6 h-6 sm:w-5 sm:h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.88-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
        
        <span class="hidden sm:inline-block font-bold text-sm tracking-wide">Lapor Error</span>
    </a>

</body>
</html>