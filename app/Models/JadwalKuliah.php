<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalKuliah extends Model
{
    protected $table = 'jadwal_kuliah';
    protected $fillable = ['kelas_id','hari','jam_mulai','jam_selesai','ruang','keterangan'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
