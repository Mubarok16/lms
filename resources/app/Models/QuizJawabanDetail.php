<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizJawabanDetail extends Model
{
    protected $table = 'quiz_jawaban_detail';

    protected $fillable = [
        'quiz_jawaban_id',
        'quiz_question_id',
        'jawaban_dipilih',
        'is_benar',
    ];

    protected $casts = [
        'is_benar' => 'boolean',
    ];

    public function jawaban(): BelongsTo
    {
        return $this->belongsTo(QuizJawaban::class, 'quiz_jawaban_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }
}
