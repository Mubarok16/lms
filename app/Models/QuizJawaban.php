<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizJawaban extends Model
{
    protected $table = 'quiz_jawaban';

    protected $fillable = [
        'quiz_id',
        'mahasiswa_id',
        'skor',
        'waktu_submit',
    ];

    protected $casts = [
        'waktu_submit' => 'datetime',
        'skor' => 'decimal:2',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'mahasiswa_id');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(QuizJawabanDetail::class);
    }
}
