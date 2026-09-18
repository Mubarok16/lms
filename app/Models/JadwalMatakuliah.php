<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalMatakuliah extends Model
{
    protected $table = 'jadwal_matakuliah';

    protected $fillable = ['kelas_id', 'hari', 'jam_mulai', 'jam_selesai', 'ruangan'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
