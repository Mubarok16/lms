<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TugasJawabanFile extends Model
{
    protected $table = 'tugas_jawaban_file';

    protected $fillable = [
        'tugas_jawaban_id',
        'file_path',
        'file_type',
        'urutan',
    ];

    public function jawaban(): BelongsTo
    {
        return $this->belongsTo(TugasJawaban::class, 'tugas_jawaban_id');
    }
}
