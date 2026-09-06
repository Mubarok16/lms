<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $table = 'materis';

    protected $fillable = [
        'pengajaran_id',
        'judul',
        'deskripsi',
        'urutan',
    ];

    public function pengajaran()
    {
        return $this->belongsTo(PengajaranDosen::class, 'pengajaran_id');
    }

    public function files()
    {
        return $this->hasMany(MateriFile::class, 'materi_id')
            ->orderBy('urutan');
    }
}
