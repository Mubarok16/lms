<?php

namespace Database\Seeders;

use App\Models\Lecturer;
use App\Models\Prodi;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $prodi = Prodi::firstOrCreate(['nama_prodi'=>'Teknik Komputer']);

        User::updateOrCreate(['email'=>'admin@example.com'], [
            'name'=>'Administrator','role'=>'admin','password'=>Hash::make('admin123'),
        ]);

        $lecturerUser = User::updateOrCreate(['email'=>'dosen@example.com'], [
            'name'=>'Dosen Demo','role'=>'lecturer','password'=>Hash::make('dosen12345'),
        ]);
        Lecturer::updateOrCreate(['user_id'=>$lecturerUser->id], [
            'nidn'=>'9999999999','prodi_id'=>$prodi->id,'phone'=>null,
        ]);

        $studentUser = User::updateOrCreate(['email'=>'mahasiswa@example.com'], [
            'name'=>'Mahasiswa Demo','role'=>'student','password'=>Hash::make('mahasiswa123'),
        ]);
        Student::updateOrCreate(['user_id'=>$studentUser->id], [
            'nim'=>'20260001','prodi_id'=>$prodi->id,'angkatan'=>2026,'phone'=>null,
        ]);
    }
}
