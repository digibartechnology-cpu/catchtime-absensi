<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest; // Pastikan nama Model Anda sesuai (Guest / BukuTamu)
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class GuestController extends Controller
{
    // Halaman Admin (Lihat Data Tamu)
    public function adminIndex(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Guest::query();
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $guests = $query->latest()->get();
        return view('admin.guest_index', compact('guests', 'startDate', 'endDate'));
    }

    // Halaman Form Publik untuk Tamu
    public function create()
    {
        return view('buku_tamu'); // Sesuaikan dengan nama view form publik Anda
    }

    // Proses Simpan Data Tamu & Kirim WA
    public function store(Request $request)
    {
        // 1. Validasi Input Tamu
        $request->validate([
            'nama_tamu' => 'required|string|max:255',
            'instansi_asal' => 'nullable|string|max:255',
            'tujuan_keperluan' => 'required|string',
            'no_hp' => 'required|string|min:9|max:15',
        ]);

        // 2. Simpan Data ke Database
        $guest = Guest::create([
            'nama_tamu' => $request->nama_tamu,
            'instansi_asal' => $request->instansi_asal ?? '-',
            'tujuan_keperluan' => $request->tujuan_keperluan,
            'no_hp' => $request->no_hp,
        ]);

        // 3. Kirim WhatsApp ke Tamu (Ucapan Selamat Datang)
        $this->sendWelcomeWhatsapp($guest);

        // 4. (Opsional) Kirim Notif ke HRD / Resepsionis bahwa ada tamu
        $this->sendNotificationToAdmin($guest);

        return back()->with('success', 'Data kunjungan berhasil didaftarkan. Silakan masuk, pesan sambutan telah dikirim ke WhatsApp Anda!');
    }

    // ==========================================
    // FUNGSI PENGIRIM WA KE TAMU
    // ==========================================
    private function sendWelcomeWhatsapp($guest)
    {
        $token = env('FONNTE_TOKEN', 'KvH5jtTzc6yagwZsy6qa');
        
        // Fonnte bisa otomatis membaca format 08x atau 628x
        $target = $guest->no_hp; 
        
        // Merangkai Pesan Sambutan yang Ramah & Profesional
        $message = "Halo *" . $guest->nama_tamu . "*! 👋\n\n";
        $message .= "Selamat datang di kantor *DigiBAR Group*.\n\n";

        $message .= "Kami harap urusan dan agenda Anda terkait _" . $guest->tujuan_keperluan . "_ dapat berjalan lancar, penuh inspirasi, dan membawa kolaborasi yang luar biasa ke depannya. 🚀\n\n";
        $message .= "Jika butuh bantuan jangan sungkan untuk memberi tahu tim kami yang ada di lokasi ya.\n\n";
        $message .= "Salam hangat,\n*Tim DigiBAR Technology*";

        // Eksekusi API Fonnte
        Http::withoutVerifying()->withHeaders([
            'Authorization' => $token,
        ])->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => $message,
        ]);
    }

    // ==========================================
    // FUNGSI NOTIFIKASI KE ADMIN/HRD
    // ==========================================
    private function sendNotificationToAdmin($guest)
    {
        $token = env('FONNTE_TOKEN', 'KvH5jtTzc6yagwZsy6qa');
        // Nomor WA Anda sebagai admin/HRD
        $target = env('WA_HRD', '628559000857'); 

        $messageAdmin = "🔔 *INFO TAMU BARU DI KANTOR*\n\n";
        $messageAdmin .= "👤 Nama: " . $guest->nama_tamu . "\n";
        $messageAdmin .= "🏢 Instansi: " . $guest->instansi_asal . "\n";
        $messageAdmin .= "📝 Keperluan: " . $guest->tujuan_keperluan . "\n";
        $messageAdmin .= "📱 No. HP: " . $guest->no_hp . "\n\n";
        $messageAdmin .= "Tolong disambut ya!";

        Http::withoutVerifying()->withHeaders([
            'Authorization' => $token,
        ])->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => $messageAdmin,
        ]);
    }

    // Fungsi PDF (Sama seperti sebelumnya jika ada)
    public function downloadPdf(Request $request)
    {
        // Logika download PDF buku tamu...
    }
}