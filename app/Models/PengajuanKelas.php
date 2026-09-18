<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanKelas extends Model
{
    protected $table = 'pengajuan_kelas';

    protected $fillable = [
        'kelas_id',
        'mahasiswa_id',
        'status',
        'catatan_dosen',
        'diproses_oleh',
        'diproses_at',
    ];

    protected $casts = [
        'diproses_at' => 'datetime',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Student::class, 'mahasiswa_id');
    }

    public function diprosesOleh()
    {
        return $this->belongsTo(Lecturer::class, 'diproses_oleh');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
