<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function create()
    {
        $employees = Employee::orderBy('nama_lengkap', 'asc')->get();
        return view('cuti', compact('employees'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Dasar
        $request->validate([
            'employee_id' => 'required',
            'jenis_cuti' => 'required',
            'tanggal_mulai' => 'required|date',
        ]);

        $jenisCuti = $request->jenis_cuti;
        $tanggalMulai = $request->tanggal_mulai;
        $tanggalSelesai = $request->tanggal_selesai;
        $alasan = $jenisCuti; // Default alasan mengikuti jenis cuti

        // 2. Modifikasi Data Berdasarkan Jenis Cuti
        if ($jenisCuti == 'Ijin Setengah Hari') {
            $tanggalSelesai = $tanggalMulai; // Paksa 1 hari
            $alasan = "Ijin Setengah Hari (" . $request->waktu_setengah_hari . ")";
        } elseif ($jenisCuti == 'Lainnya') {
            $alasan = $request->alasan_lainnya;
            $request->validate([
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai'
            ]);
        } else {
            // Jika cuti normal, validasi tanggal_selesai wajib ada
            $request->validate([
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai'
            ], [
                'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih cepat dari tanggal mulai.'
            ]);

            // ==========================================
            // LOGIKA PEMBATASAN JUMLAH HARI CUTI
            // ==========================================
            $tglMulai = Carbon::parse($tanggalMulai);
            $tglSelesai = Carbon::parse($tanggalSelesai);
            // +1 karena hari inklusif (misal tgl 1 s/d 1 = 1 hari kerja)
            $jumlahHari = $tglMulai->diffInDays($tglSelesai) + 1; 

            if ($jenisCuti == 'Ijin Penting' && $jumlahHari > 1) {
                return back()->with('error', 'Pengajuan ditolak! Ijin Penting maksimal hanya 1 hari.');
            }
            if ($jenisCuti == 'Sakit' && $jumlahHari > 7) {
                return back()->with('error', 'Pengajuan ditolak! Cuti Sakit via sistem maksimal 1 minggu (7 hari). Untuk rawat inap/waktu lebih lama, harap hubungi HRD secara langsung.');
            }
            if ($jenisCuti == 'Cuti Tahunan' && $jumlahHari > 14) {
                return back()->with('error', 'Pengajuan ditolak! Cuti Tahunan maksimal 2 minggu (14 hari) dalam satu kali pengajuan.');
            }
            // ==========================================
        }

        // 3. Logika Auto-Approve Sakit
        $statusAwal = 'Pending';
        if ($jenisCuti == 'Sakit') {
            $statusAwal = 'Disetujui Sementara';
        }

        // 4. Simpan ke Database
        $cuti = LeaveRequest::create([
            'employee_id' => $request->employee_id,
            'jenis_cuti' => $jenisCuti,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'alasan' => $alasan,
            'status' => $statusAwal
        ]);

        $cuti->load('employee');
        
        $targetNomor = $cuti->employee->no_whatsapp ?? env('WA_HRD', '628559000857');

        // 5. Kirim Notifikasi WhatsApp
        if ($jenisCuti == 'Sakit') {
            $pesanKaryawan = "Halo " . $cuti->employee->nama_lengkap . ",\n\n";
            $pesanKaryawan .= "Pengajuan Cuti SAKIT Anda dari tgl " . Carbon::parse($tanggalMulai)->format('d M') . " s/d " . Carbon::parse($tanggalSelesai)->format('d M') . " telah *DISETUJUI SEMENTARA*.\n\n";
            $pesanKaryawan .= "⚠️ *PERHATIAN:* Harap serahkan Surat Keterangan Dokter segera setelah Anda kembali ke kantor. Jika tidak ada surat, otomatis akan memotong jatah cuti tahunan normal Anda.\n\nSemoga lekas sembuh!\nHRD DigiBAR Group";
            
            $this->sendWhatsappNotification($pesanKaryawan, $targetNomor);

            $pesanHRD = "🩺 *INFO CUTI SAKIT (AUTO-APPROVE)*\n";
            $pesanHRD .= "👤 Nama: " . $cuti->employee->nama_lengkap . "\n";
            $pesanHRD .= "📅 Tgl: " . Carbon::parse($tanggalMulai)->format('d/m') . " - " . Carbon::parse($tanggalSelesai)->format('d/m') . "\n";
            $pesanHRD .= "Telah disetujui sementara oleh sistem. Tagih surat dokter saat kembali.";
            $this->sendWhatsappNotification($pesanHRD);

            return back()->with('success', 'Pengajuan Sakit berhasil dikirim & otomatis disetujui sementara! Ingat bawa surat dokter saat masuk ya.');

        } else {
            $pesanWA = "📬 *PENGAJUAN CUTI BARU*\n";
            $pesanWA .= "👤 Nama: " . $cuti->employee->nama_lengkap . "\n";
            $pesanWA .= "🏷️ Jenis: " . $jenisCuti . "\n";
            $pesanWA .= "⏳ Durasi: " . (isset($jumlahHari) ? $jumlahHari . " Hari\n" : "1 Hari\n");
            $pesanWA .= "📅 Tgl: " . Carbon::parse($tanggalMulai)->format('d/m/Y') . " s/d " . Carbon::parse($tanggalSelesai)->format('d/m/Y') . "\n";
            $pesanWA .= "📝 Alasan: " . $alasan . "\n\n";
            $pesanWA .= "Silakan cek Dashboard Admin untuk Menyetujui / Menolak.";
            
            $this->sendWhatsappNotification($pesanWA);

            return back()->with('success', 'Pengajuan cuti berhasil dikirim! Silakan tunggu konfirmasi dari HRD via WhatsApp.');
        }
    }

    public function adminIndex(Request $request)
    {
        $query = LeaveRequest::with('employee')->latest();
        
        if ($request->search) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%'.$request->search.'%');
            })
            ->orWhere('jenis_cuti', 'like', '%'.$request->search.'%')
            ->orWhere('status', 'like', '%'.$request->search.'%');
        }

        $cuti_requests = $query->get();
        return view('admin.cuti_index', compact('cuti_requests'));
    }

    public function updateStatus(Request $request, $id)
    {
        $cuti = LeaveRequest::findOrFail($id);
        
        $cuti->status = $request->status; 
        
        if ($request->status == 'Reschedule' && $request->tanggal_reschedule) {
            $tglBaru = Carbon::parse($request->tanggal_reschedule)->format('d M Y');
            $cuti->catatan_admin = "Saran Tanggal Baru: " . $tglBaru . ". " . $request->catatan_admin;
        } else {
            $cuti->catatan_admin = $request->catatan_admin;
        }
        
        $cuti->save();

        $simbol = $cuti->status == 'Disetujui' ? '✅' : ($cuti->status == 'Ditolak' ? '❌' : '🔄');
        
        $pesanWA = "📢 *PENGUMUMAN STATUS CUTI*\n";
        $pesanWA .= "Halo " . $cuti->employee->nama_lengkap . ",\n\n";
        $pesanWA .= "Pengajuan *" . $cuti->jenis_cuti . "* Anda untuk tgl " . Carbon::parse($cuti->tanggal_mulai)->format('d M') . " s/d " . Carbon::parse($cuti->tanggal_selesai)->format('d M') . "\n";
        $pesanWA .= "Status saat ini: " . $simbol . " *" . strtoupper($cuti->status) . "*\n";
        
        if ($cuti->status == 'Reschedule') {
            $pesanWA .= "\n*HRD menyarankan perubahan tanggal cuti ke:* \n👉 " . Carbon::parse($request->tanggal_reschedule)->format('d F Y') . "\n\n";
            $pesanWA .= "📌 *Mekanisme Tindak Lanjut:*\n";
            $pesanWA .= "- Jika *SETUJU*, silakan ajukan ulang cuti di Web menggunakan tanggal baru di atas.\n";
            $pesanWA .= "- Jika *TIDAK SETUJU/BATAL*, abaikan pesan ini dan Anda tetap diwajibkan masuk bekerja.\n";
            $pesanWA .= "- Hubungi HRD langsung jika ada yang perlu didiskusikan.\n";
        }

        if ($cuti->catatan_admin && $cuti->status != 'Reschedule') {
            $pesanWA .= "📝 Catatan: " . $cuti->catatan_admin . "\n";
        }
        
        $pesanWA .= "\nTerima kasih,\nHRD DigiBAR Group";

        $targetNomor = $cuti->employee->no_whatsapp ?? env('WA_HRD', '628559000857');
        $this->sendWhatsappNotification($pesanWA, $targetNomor);

        return back()->with('success', 'Status Cuti berhasil diperbarui & WA terkirim ke karyawan.');
    }

    private function sendWhatsappNotification($message, $targetNumber = null)
    {
        $token = env('FONNTE_TOKEN', 'KvH5jtTzc6yagwZsy6qa');
        $target = $targetNumber ? $targetNumber : env('WA_HRD', '628559000857'); 
        
        Http::withoutVerifying()->withHeaders([
            'Authorization' => $token,
        ])->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => $message,
        ]);
    }

    public function resolveSakitSementara(Request $request, $id)
    {
        $cuti = LeaveRequest::findOrFail($id); 

        if ($request->keputusan === 'valid_sakit') {
            $cuti->update([
                'status' => 'DISETUJUI'
            ]);
            $pesanWA = "Halo, pengajuan SAKIT Anda pada " . $cuti->tanggal_mulai . " telah disetujui sepenuhnya oleh Admin.";
            
        } elseif ($request->keputusan === 'convert_cuti') {
            $cuti->update([
                'jenis_cuti' => 'Cuti Tahunan',
                'status' => 'DISETUJUI',
                'catatan_admin' => 'Tanpa surat dokter. Dialihkan ke pemotongan Cuti Tahunan.'
            ]);
            $pesanWA = "Halo, karena tidak ada lampiran surat dokter yang valid, status ketidakhadiran Anda pada " . $cuti->tanggal_mulai . " dialihkan menjadi potongan CUTI TAHUNAN.";
        }

        $targetNomor = $cuti->employee->no_whatsapp ?? env('WA_HRD', '628559000857');
        $this->sendWhatsappNotification($pesanWA, $targetNomor);

        return back()->with('success', 'Status pengajuan berhasil diselesaikan!');
    }
}