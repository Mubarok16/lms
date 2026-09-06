<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriFile extends Model
{
    protected $table = 'materi_files';

    protected $fillable = [
        'materi_id',
        'tipe',
        'file_path',
        'nama_asli',
        'youtube_url',
        'youtube_id',
        'urutan',
    ];

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }

    // Accessor: url publik file (pdf/audio) di storage
    public function getUrlAttribute()
    {
        if (in_array($this->tipe, ['pdf', 'audio']) && $this->file_path) {
            return asset('storage/' . $this->file_path);
        }

        return null;
    }

    // Accessor: url embed youtube, siap dipakai di <iframe src="">
    public function getYoutubeEmbedUrlAttribute()
    {
        if ($this->tipe === 'video_youtube' && $this->youtube_id) {
            return 'https://www.youtube.com/embed/' . $this->youtube_id;
        }

        return null;
    }
}
