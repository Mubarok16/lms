<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Absensi.php
class Absensi extends Model
{
    protected $table = 'absensi';

    protected $fillable = ['sesi_absensi_id', 'mahasiswa_id', 'status', 'waktu_absen', 'ip_address', 'catatan'];

    protected $casts = [
        'waktu_absen' => 'datetime',
    ];

    public function sesi()
    {
        return $this->belongsTo(SesiAbsensi::class, 'sesi_absensi_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Student::class, 'mahasiswa_id');
    }
}
