<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'kode_mk',
        'kode_kelas',
    ];

    public function matakuliah()
    {
        return $this->belongsTo(
            //Kelas ini belongsTo satu MataKuliah.
            MataKuliah::class,
            'kode_mk',  //matakuliah.kode_mk ==>PK
            'kode_mk' //kelas.kode_mk ==> FK
        );
    }

    public function pengajaranDosen()
    {
        return $this->hasMany(
            //Kelas ini hasMany PengajaranDosen
            PengajaranDosen::class,
            'kelas_id', //ini adalah pengajaran_dosen.kelas_id ==>FK
            'id' //ini adalah kelas.id ==>PK
        );
    }
    public function pengajaranMahasiswa()
    {
        return $this->hasMany(PengajaranMahasiswa::class);
    }
    // relasi many-to-many langsung ke Student lewat tabel pivot
    public function mahasiswa()
    {
        return $this->belongsToMany(
            Student::class,
            'pengajaran_mahasiswa',
            'kelas_id',
            'mahasiswa_id'
        )->withTimestamps();
    }
}
