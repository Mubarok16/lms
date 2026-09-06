<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TugasFile extends Model
{
    protected $table = 'tugas_file';

    protected $fillable = [
        'tugas_id',
        'file_path',
        'file_type',
        'urutan',
    ];

    public function tugas(): BelongsTo
    {
        return $this->belongsTo(Tugas::class);
    }
}
