<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
// app/Models/SesiAbsensi.php
class SesiAbsensi extends Model
{
    protected $table = 'sesi_absensi';
    protected $fillable = [
        'kelas_id',
        'dosen_id',
        'pertemuan_ke',
        'judul',
        'token',
        'durasi_menit',
        'dibuka_pada',
        'ditutup_pada',
    ];

    protected $casts = [
        'dibuka_pada' => 'datetime',
        'ditutup_pada' => 'datetime',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Lecturer::class, 'dosen_id');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function isExpired(): bool
    {
        return now()->greaterThan($this->ditutup_pada);
    }
}
