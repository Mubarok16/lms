<?php

namespace App\Models;
use App\Models\Prodi;
use App\Models\Matakuliah;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lecturer extends Model
{
    use HasFactory;

    protected $table = 'lecturer';

    protected $fillable = [
        'user_id',
        'nidn',
        'prodi_id',
        'phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function prodi()
{
    return $this->belongsTo(Prodi::class, 'prodi_id', 'id');
}
public function matakuliahs()
{
    return $this->belongsToMany(
        Matakuliah::class,
        'pengajaran',
        'lecturer_id',
        'kode_mk',
        'id',
        'kode_mk'
    );
}
}