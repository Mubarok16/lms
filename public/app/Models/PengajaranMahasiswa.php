<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kelas;


class PengajaranMahasiswa extends Model
{
    protected $table = 'pengajaran_mahasiswa';

    protected $fillable = [
        'kelas_id',
        'mahasiswa_id',
    ];

    public function kelas()
    {
        return $this->belongsTo(
            Kelas::class,
            'kelas_id',
        );
    }

    public function student()
    {
        return $this->belongsTo(
            Student::class,
            'mahasiswa_id'
        );
    }
}
