<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tugas extends Model

{
    protected $table = 'tugas';

    protected $fillable = [
        'pengajaran_dosen_id',
        'judul',
        'deskripsi',
        'deadline',
        'bobot_nilai',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function pengajaranDosen(): BelongsTo
    {
        return $this->belongsTo(PengajaranDosen::class, 'pengajaran_dosen_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(TugasFile::class)->orderBy('urutan');
    }

    public function jawaban(): HasMany
    {
        return $this->hasMany(TugasJawaban::class);
    }
}
