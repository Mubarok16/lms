<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TugasJawaban extends Model
{
    protected $table = 'tugas_jawaban';

    protected $fillable = [
        'tugas_id',
        'mahasiswa_id',
        'waktu_submit',
        'skor',
        'catatan_koreksi',
        'dikoreksi_oleh',
        'dikoreksi_at',
        'status',
    ];

    protected $casts = [
        'waktu_submit' => 'datetime',
        'dikoreksi_at' => 'datetime',
        'skor' => 'decimal:2',
    ];

    public function tugas(): BelongsTo
    {
        return $this->belongsTo(Tugas::class);
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'mahasiswa_id');
    }

    public function dikoreksiOleh(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class, 'dikoreksi_oleh');
    }

    public function files(): HasMany
    {
        return $this->hasMany(TugasJawabanFile::class)->orderBy('urutan');
    }
}
