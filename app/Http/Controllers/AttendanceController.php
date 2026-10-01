<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // ==========================================
    // FUNGSI RAHASIA: PENYAPU OTOMATIS IJIN LEBIH 2 JAM
    // ==========================================
    private function autoCheckIjinGantung()
    {
        $sekarang = now()->timezone('Asia/Jakarta');
        $hariIni = \Carbon\Carbon::today('Asia/Jakarta');

        $ijinGantung = Attendance::with('employee')
            ->whereDate('created_at', $hariIni)
            ->whereIn('type', ['Ijin Keluar', 'Leave Office'])
            ->get();

        foreach ($ijinGantung as $ijin) {
            $adaLanjutan = Attendance::where('employee_id', $ijin->employee_id)
                ->where('id', '>', $ijin->id)
                ->exists();

            if (!$adaLanjutan) {
                $waktuIjin = \Carbon\Carbon::parse($ijin->created_at)->timezone('Asia/Jakarta');
                $selisihMenit = $waktuIjin->diffInMinutes($sekarang);
                
                // BATAS 2 JAM = 120 MENIT
                if ($selisihMenit >= 120) {
                    $ijin->update([
                        'type' => 'Pulang',
                        'keterangan' => 'Otomatis Pulang (Tidak kembali > 2 Jam)'
                    ]);

                    if ($ijin->employee) {
                        $pesanWA = "⚠️ *SISTEM AUTO UPDATE*\n";
                        $pesanWA .= "👤 Nama: " . $ijin->employee->nama_lengkap . "\n";
                        $pesanWA .= "Anda terdeteksi Izin Keluar melebihi 2 Jam. Status absensi Anda hari ini telah otomatis ditimpa menjadi *PULANG* pada jam " . $ijin->created_at->format('H:i') . " WIB.";
                        $this->sendWhatsappNotification($pesanWA);
                    }
                }
            }
        }
    }

    public function adminIndex(Request $request)
    {
        $this->autoCheckIjinGantung();

        $employees = Employee::orderBy('nama_lengkap', 'asc')->get();
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Attendance::with('employee');
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $attendances = $query->latest()->get();
        
        $absenKantor = $attendances->where('foto_bukti', 'RFID Tap')->values();
        $absenLapangan = $attendances->where('foto_bukti', '!=', 'RFID Tap')->values();

        return view('admin.attendance_create', compact('employees', 'attendances', 'absenKantor', 'absenLapangan', 'startDate', 'endDate'));
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'type' => 'required|in:Masuk,Pulang',
            'foto_bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imageName = 'default.png';
        
        if ($request->hasFile('foto_bukti')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->foto_bukti->extension();
            $uploadPath = public_path('images/absensi');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $request->file('foto_bukti')->move($uploadPath, $imageName);
        }

        $attendance = Attendance::create([
            'employee_id' => $request->employee_id,
            'type' => $request->type,
            'foto_bukti' => $imageName,
            'latitude' => $request->latitude ?? null,
            'longitude' => $request->longitude ?? null,
        ]);

        $pesanWA = "📌 *ABSENSI MANUAL (ADMIN)*\n👤 Nama: " . $attendance->employee->nama_lengkap . "\n📅 Tipe: " . $attendance->type;
        $this->sendWhatsappNotification($pesanWA);

        return redirect()->route('admin.absensi.index')->with('success', 'Data tersimpan & WA Terkirim!');
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'type' => 'required',
            'foto_bukti' => 'nullable|string', 
        ]);

        $tipeAbsen = $request->type;
        $isIjin = in_array($tipeAbsen, ['Leave Office', 'Ijin Keluar']);

        $sudahAbsen = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('created_at', Carbon::today())
            ->where('type', $tipeAbsen)
            ->exists();

        if ($sudahAbsen) {
            return back()->withErrors(['pesan' => 'AKSES DITOLAK: Anda sudah melakukan absensi "' . $tipeAbsen . '" hari ini.']);
        }

        if ($tipeAbsen === 'Pulang') {
            $absenTerakhir = Attendance::where('employee_id', $request->employee_id)
                            ->whereDate('created_at', Carbon::today())
                            ->orderBy('id', 'desc')
                            ->first();

            if ($absenTerakhir && in_array($absenTerakhir->type, ['Leave Office', 'Ijin Keluar'])) {
                return back()->withErrors(['pesan' => 'AKSES DITOLAK: Anda masih berstatus "Ijin Keluar". Harap lapor "Kembali" terlebih dahulu.']);
            }
        }

        $keterangan_alasan = null;
        if ($isIjin) {
            $keterangan_alasan = ($request->alasan_ijin === 'Lainnya') ? $request->alasan_lainnya : $request->alasan_ijin;
        }

        $imageName = 'Tanpa Foto'; 
        if ($request->filled('foto_bukti') && str_contains($request->foto_bukti, 'base64')) {
            try {
                $image_parts = explode(";base64,", $request->foto_bukti);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                
                $imageName = time() . '_' . uniqid() . '.' . $image_type;
                
                $uploadPath = public_path('images/absensi');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                file_put_contents($uploadPath . '/' . $imageName, $image_base64);

            } catch (\Exception $e) {
                return back()->withErrors(['pesan' => 'Gagal menyimpan foto lokal: ' . $e->getMessage()]);
            }
        }
        
        $absenBaru = Attendance::create([
            'employee_id' => $request->employee_id,
            'type' => $tipeAbsen,
            'foto_bukti' => $imageName,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'keterangan' => $keterangan_alasan 
        ]);

        $absenBaru->load('employee'); 

        $statusWaktu = $isIjin ? '🔵 *Ijin Keluar*' : '📍 *Absen Lapangan / WFA*';

        $pesanWA = "📌 *ABSENSI KARYAWAN*\n👤 Nama: " . $absenBaru->employee->nama_lengkap . "\n📅 Tipe: " . $absenBaru->type;
        $pesanWA .= "\n🚦 Status: " . $statusWaktu;

        if ($keterangan_alasan) {
            $pesanWA .= "\n📝 Keterangan: " . $keterangan_alasan;
        }
        
        if ($absenBaru->latitude && $absenBaru->longitude) {
            $pesanWA .= "\n📍 Lokasi: https://www.google.com/maps?q=" . $absenBaru->latitude . "," . $absenBaru->longitude;
        }
        
        $this->sendWhatsappNotification($pesanWA);

        if ($isIjin) {
            session(['kunci_ijin_keluar' => $absenBaru->id]);
            return redirect('/absen/terkunci');
        }

        return back()
            ->with('absen_success', true)
            ->with('absen_nama', $absenBaru->employee->nama_lengkap)
            ->with('absen_tipe', $absenBaru->type);
    }

    public function kembaliKeKantor(Request $request)
    {
        $idAbsenKeluar = session('kunci_ijin_keluar');
        $employeeId = $request->employee_id;
        $absenLama = null;

        if ($idAbsenKeluar) {
            $absenLama = Attendance::find($idAbsenKeluar);
        }

        if (!$absenLama && $employeeId) {
            $absenLama = Attendance::where('employee_id', $employeeId)
                            ->whereDate('created_at', \Carbon\Carbon::today())
                            ->whereIn('type', ['Leave Office', 'Ijin Keluar'])
                            ->orderBy('id', 'desc')
                            ->first();
        }

        $request->session()->forget('kunci_ijin_keluar');
        $request->session()->save(); 

        if ($absenLama) {
            $sekarang = now()->timezone('Asia/Jakarta');
            $waktuIjin = \Carbon\Carbon::parse($absenLama->created_at)->timezone('Asia/Jakarta');
            $selisihMenit = $waktuIjin->diffInMinutes($sekarang);
            
            // BATAS 2 JAM = 120 MENIT
            if ($selisihMenit >= 120) {
                // OVERWRITE WEB: Timpa baris yang ada tanpa menambah baris baru
                $absenLama->update([
                    'type' => 'Pulang',
                    'keterangan' => 'Otomatis Pulang (Izin Web > 2 Jam)'
                ]);
                $absenKembali = clone $absenLama;
                $pesanRedirect = 'Izin Anda melebihi 2 jam! Status otomatis ditimpa menjadi PULANG sesuai jam Anda keluar.';
            } else {
                $absenKembali = Attendance::create([
                    'employee_id' => $absenLama->employee_id,
                    'type' => 'Kembali ke Kantor',
                    'foto_bukti' => 'Tanpa Foto',
                    'keterangan' => 'Selesai Ijin: ' . ($absenLama->keterangan ?? '-')
                ]);
                $pesanRedirect = 'Status diperbarui. Selamat bekerja kembali!';
            }

            $absenKembali->load('employee');
            if ($absenKembali->employee) {
                $pesanWA = "📌 *UPDATE ABSENSI*\n👤 Nama: " . $absenKembali->employee->nama_lengkap . "\n📅 Tipe: " . $absenKembali->type;
                $this->sendWhatsappNotification($pesanWA);
            }
            
            return redirect('/')->with('success', $pesanRedirect);
        }

        return redirect('/')->with('success', 'Gembok berhasil direset.');
    }

    private function sendWhatsappNotification($message)
    {
        $token = env('FONNTE_TOKEN', 'KvH5jtTzc6yagwZsy6qa');
        $target = env('WA_HRD', '628559000857'); 
        
        \Illuminate\Support\Facades\Http::withoutVerifying()->withHeaders([
            'Authorization' => $token,
        ])->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => $message . "\n⏰ Waktu: " . now()->format('d/m/Y H:i') . " WIB",
        ]);
    }

    public function userIndex()
    {
        if (session()->has('kunci_ijin_keluar')) {
            return redirect('/absen/terkunci');
        }
        $employees = Employee::orderBy('nama_lengkap', 'asc')->get();
        return view('absensi', compact('employees'));
    }

    public function destroy($id) {
        $attendance = Attendance::findOrFail($id);
        if ($attendance->foto_bukti && $attendance->foto_bukti != 'default.png' && $attendance->foto_bukti != 'Tanpa Foto' && file_exists(public_path('images/absensi/'.$attendance->foto_bukti))) {
            unlink(public_path('images/absensi/'.$attendance->foto_bukti));
        }
        $attendance->delete();
        return back()->with('success', 'Data dihapus!');
    }

    public function downloadPdf(Request $request) {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');

        $query = \App\Models\Attendance::with('employee');
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date.' 00:00:00', $request->end_date.' 23:59:59']);
        }
        
        $attendances = $query->orderBy('employee_id')->orderBy('created_at', 'asc')->get();

        if ($attendances->isEmpty()) {
            return back()->with('error', 'Data absensi kosong pada rentang tanggal tersebut, tidak dapat mencetak PDF.');
        }

        $reportData = [];

        foreach ($attendances as $absen) {
            $date = $absen->created_at->format('Y-m-d');
            $empId = $absen->employee_id;

            if (!isset($reportData[$empId])) {
                $reportData[$empId] = [
                    'nama' => $absen->employee->nama_lengkap ?? 'Karyawan Dihapus',
                    'jabatan' => $absen->employee->jabatan_posisi ?? '-',
                    'harian' => []
                ];
            }

            if (!isset($reportData[$empId]['harian'][$date])) {
                $reportData[$empId]['harian'][$date] = [
                    'tanggal_format' => $absen->created_at->format('d M Y'),
                    'masuk' => null,
                    'pulang' => null,
                    'leave' => [],
                    'kembali' => []
                ];
            }

            $time = clone $absen->created_at;
            $typeLokal = strtolower($absen->type);

            if ($typeLokal === 'masuk') {
                $reportData[$empId]['harian'][$date]['masuk'] = $time;
            } elseif ($typeLokal === 'pulang') {
                $reportData[$empId]['harian'][$date]['pulang'] = $time;
            } elseif (str_contains($typeLokal, 'kembali')) {
                $reportData[$empId]['harian'][$date]['kembali'][] = $time;
            } elseif (str_contains($typeLokal, 'ijin') || str_contains($typeLokal, 'leave')) {
                $reportData[$empId]['harian'][$date]['leave'][] = $time;
            }
        }

        foreach ($reportData as $empId => &$empData) {
            $totalMenitPeriode = 0;

            foreach ($empData['harian'] as $date => &$dayData) {
                if ($dayData['masuk'] && $dayData['pulang']) {
                    
                    $masuk = \Carbon\Carbon::parse($dayData['masuk'])->startOfMinute();
                    $pulang = \Carbon\Carbon::parse($dayData['pulang'])->startOfMinute();
                    
                    $totalMinutes = $masuk->diffInMinutes($pulang);
                    $leaveMinutes = 0;
                    
                    $availableKembali = [];
                    foreach ($dayData['kembali'] as $k) {
                        $availableKembali[] = \Carbon\Carbon::parse($k)->startOfMinute();
                    }

                    foreach ($dayData['leave'] as $leaveTime) {
                        $leaveStart = \Carbon\Carbon::parse($leaveTime)->startOfMinute();
                        $leaveEnd = null;

                        foreach ($availableKembali as $idx => $kembaliTime) {
                            if ($kembaliTime->gt($leaveStart)) {
                                $leaveEnd = $kembaliTime;
                                unset($availableKembali[$idx]); 
                                break;
                            }
                        }

                        if (!$leaveEnd) {
                            $leaveEnd = clone $pulang;
                        }

                        if ($leaveEnd->gt($leaveStart)) {
                            if ($leaveEnd->gt($pulang)) {
                                $leaveEnd = clone $pulang;
                            }
                            $leaveMinutes += $leaveStart->diffInMinutes($leaveEnd);
                        }
                    }

                    if ($leaveMinutes > $totalMinutes) {
                        $leaveMinutes = $totalMinutes;
                    }

                    $netMinutes = $totalMinutes - $leaveMinutes;
                    if ($netMinutes < 0) $netMinutes = 0; 

                    $totalMenitPeriode += $netMinutes;

                    $hours = floor($netMinutes / 60);
                    $minutes = $netMinutes % 60;
                    $dayData['total_jam_text'] = $hours . ' Jam ' . $minutes . ' Menit';
                    
                    if ($leaveMinutes > 0) {
                        $leaveH = floor($leaveMinutes / 60);
                        $leaveM = $leaveMinutes % 60;
                        $dayData['durasi_ijin'] = $leaveH . ' Jam ' . $leaveM . ' Menit';
                    } else {
                        $dayData['durasi_ijin'] = '-';
                    }
                    
                } else {
                    $dayData['total_jam_text'] = 'Data Tidak Lengkap (Lupa Absen)';
                    $dayData['durasi_ijin'] = '-';
                }
            }

            $jamPeriode = floor($totalMenitPeriode / 60);
            $menitPeriode = $totalMenitPeriode % 60;
            $empData['total_jam_periode'] = $jamPeriode . ' Jam ' . $menitPeriode . ' Menit';
        }

        $pdf = Pdf::loadView('admin.attendance_pdf', [
            'reportData' => $reportData,
            'startDate' => $request->start_date,
            'endDate' => $request->end_date
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Absensi_DigiBAR.pdf');
    }

    public function rfidIndex()
    {
        return view('rfid_scanner');
    }

    public function rfidProcess(Request $request)
    {
        $request->validate([
            'uid' => 'required',
        ]);

        $sekarang = now()->timezone('Asia/Jakarta');
        $hariIni = \Carbon\Carbon::today('Asia/Jakarta');

        $employee = \App\Models\Employee::where('rfid_uid', $request->uid)->first();

        if (!$employee) {
            return back()->with('error', '❌ Kartu tidak terdaftar di sistem !');
        }

        $absenTerakhir = \App\Models\Attendance::where('employee_id', $employee->id)
            ->whereDate('created_at', $hariIni)
            ->orderBy('id', 'desc')
            ->first();

        $pesan = '';
        $tipeBaru = '';
        $keteranganRFID = 'Absen Kantor (Tap Card)';
        $isOverwrite = false;

        if (!$absenTerakhir) {
            $tipeBaru = 'Masuk';
            $pesan = "✅ Selamat bekerja, " . $employee->nama_lengkap . "!";
            
        } else {
            if (in_array($absenTerakhir->type, ['Masuk', 'Kembali ke Kantor', 'Kembali Ijin'])) {
                if ($sekarang->format('H:i') < '16:00') {
                    $tipeBaru = 'Ijin Keluar';
                    $pesan = "🚶 Hati-hati di jalan, " . $employee->nama_lengkap . ". (Keluar Sementara)";
                } else {
                    $tipeBaru = 'Pulang';
                    $pesan = "🏠 Terima kasih atas kerja keras hari ini, " . $employee->nama_lengkap . "!";
                }

            } elseif ($absenTerakhir->type == 'Ijin Keluar') {
                $waktuIjin = \Carbon\Carbon::parse($absenTerakhir->created_at)->timezone('Asia/Jakarta');
                $selisihMenit = $waktuIjin->diffInMinutes($sekarang);
                
                // BATAS 2 JAM = 120 MENIT
                if ($selisihMenit >= 120) {
                    // OVERWRITE RFID: Timpa baris yang ada
                    $absenTerakhir->update([
                        'type' => 'Pulang',
                        'foto_bukti' => 'RFID Tap',
                        'keterangan' => 'Absen Kantor (Izin > 2 Jam Ditimpa Pulang)'
                    ]);
                    $tipeBaru = 'Pulang';
                    $pesan = "🏠 Izin lebih dari 2 jam! Status ditimpa menjadi Pulang sesuai jam awal izin, " . $employee->nama_lengkap;
                    $absenBaru = clone $absenTerakhir;
                    $isOverwrite = true;
                } else {
                    $tipeBaru = 'Kembali ke Kantor';
                    $pesan = "🏢 Selamat datang kembali, " . $employee->nama_lengkap . "!";
                }

            } else {
                return back()->with('error', '⚠️ Anda sudah melakukan absen Pulang hari ini.');
            }
        }

        if ($tipeBaru != '') {
            // JIKA OVERWRITE, KITA TIDAK MEMBUAT BARIS BARU!
            if (!$isOverwrite) {
                $absenBaru = \App\Models\Attendance::create([
                    'employee_id' => $employee->id,
                    'type' => $tipeBaru,
                    'foto_bukti' => 'RFID Tap', 
                    'keterangan' => $keteranganRFID
                ]);
            }

            $statusWaktu = '';
            $jamAbsen = $sekarang->format('H:i');
            if ($tipeBaru == 'Masuk') {
                $statusWaktu = ($jamAbsen > '09:00') ? '🔴 *TELAT*' : '🟢 *On Time*';
            } elseif ($tipeBaru == 'Pulang') {
                $statusWaktu = ($jamAbsen < '17:00') ? '🟡 *Pulang Awal*' : '🟢 *On Time*';
            }
            
            $pesanWA = "📌 *ABSENSI KANTOR (TAP KARTU)*\n👤 Nama: " . $employee->nama_lengkap . "\n📅 Tipe: " . $tipeBaru;
            if ($statusWaktu != '') $pesanWA .= "\n🚦 Status: " . $statusWaktu;
            
            $this->sendWhatsappNotification($pesanWA);
            
            return back()
                ->with('rfid_success', true)
                ->with('rfid_nama', $employee->nama_lengkap) 
                ->with('rfid_tipe', $tipeBaru);
        }

        return back()->with('success', 'Proses selesai.');
    }
}