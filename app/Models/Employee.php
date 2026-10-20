<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk mengizinkan input data
    protected $fillable = [
        'nama_lengkap', 
        'jenis_kelamin', 
        'alamat_tempat_tinggal', 
        'no_whatsapp', 
        'jabatan_posisi',
        'rfid_uid'
    ];

    // Relasi ke data pengajuan cuti
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}