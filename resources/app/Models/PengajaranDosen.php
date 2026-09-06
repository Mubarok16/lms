<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kelas;
use App\Models\Lecturer;

class PengajaranDosen extends Model
{
    protected $table = 'pengajaran_dosen';

    protected $fillable = [
        'dosen_id',
        'kelas_id',
    ];

    public function lecturer()
    {
        return $this->belongsTo(
            Lecturer::class,
            'dosen_id',
            'id'
        );
    }

    public function kelas()
    {
        return $this->belongsTo(
            Kelas::class,
            'kelas_id',
            'id'
        );
    }
    public function materi()
    {
        return $this->hasMany(Materi::class, 'pengajaran_id');
    }
}
