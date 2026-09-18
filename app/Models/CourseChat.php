<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseChat extends Model
{
    protected $fillable = ['kelas_id', 'user_id', 'message'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
