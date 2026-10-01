<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'jenis_cuti',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'status',
        'catatan_admin'
    ];

    // Relasi agar bisa memanggil nama karyawan
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}