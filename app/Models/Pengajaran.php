<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajaran extends Model
{
    protected $table = 'pengajaran';

    protected $fillable = [
        'lecturer_id',
        'kode_mk',
    ];

    public function lecturer()
    {
        return $this->belongsTo(
            Lecturer::class,
            'lecturer_id',
            'id'
        );
    }

    public function matakuliah()
    {
        return $this->belongsTo(
            Matakuliah::class,
            'kode_mk',
            'kode_mk'
        );
    }
}