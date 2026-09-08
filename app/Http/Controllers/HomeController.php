<?php

namespace App\Http\Controllers;

use App\Models\Student;

class HomeController extends Controller
{
    public function index()
    {
        $jumlahMahasiswa = Student::count();

        return view('lamandepan', compact('jumlahMahasiswa'));
    }
}
