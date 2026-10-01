<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Kehadiran Karyawan - DigiBAR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 min-h-screen p-4 md:p-8">

    <div class="max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo-digibar.png') }}" alt="Logo DigiBAR" class="h-12 mx-auto mb-4">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Direktori Karyawan</h1>
            <p class="text-gray-500 mt-2 text-sm md:text-base">Cek status ketersediaan karyawan di kantor hari ini secara real-time.</p>
        </div>

        <!-- Kolom Pencarian -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-8">
            <form action="/cek-kehadiran" method="GET" class="flex gap-2">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama karyawan..." class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all">
                </div>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors">
                    Cari
                </button>
            </form>
        </div>

        <!-- Hasil / Daftar Karyawan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($employees as $emp)
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">{{ $emp->nama_lengkap }}</h3>
                        <p class="text-gray-500 text-sm">{{ $emp->jabatan_posisi ?? 'Karyawan' }}</p>
                    </div>
                    <div class="text-right">
                        <!-- Label Status Otomatis -->
                        <span class="inline-block px-3 py-1 text-xs font-bold rounded-full border {{ $emp->status_color }}">
                            {{ $emp->status_kehadiran }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 bg-white p-8 rounded-2xl text-center border border-gray-100">
                    <p class="text-gray-500">Karyawan tidak ditemukan.</p>
                </div>
            @endforelse
        </div>
        
        <div class="text-center mt-10">
            <a href="/" class="text-sm text-gray-400 hover:text-gray-600 underline">&larr; Kembali ke Beranda</a>
        </div>

    </div>

</body>
</html>