<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'nomor',
        'pertanyaan',
        'gambar',
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'pilihan_e',
        'kunci_jawaban',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Ambil pilihan dalam bentuk array asosiatif ['A' => '...', 'B' => '...']
     * hanya untuk pilihan yang tidak kosong.
     */
    public function pilihanTersedia(): array
    {
        $semua = [
            'A' => $this->pilihan_a,
            'B' => $this->pilihan_b,
            'C' => $this->pilihan_c,
            'D' => $this->pilihan_d,
            'E' => $this->pilihan_e,
        ];

        return array_filter($semua, fn($v) => filled($v));
    }
}
