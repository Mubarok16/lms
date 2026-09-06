<?php

namespace App\Http\Controllers;
use App\Models\Lecturer;
use App\Models\Prodi;
use Illuminate\Http\Request;

class DosenController extends Controller
{
public function show(Prodi $prodi)
{
    $lecturers = Lecturer::with('user')
        ->where('prodi_id', $prodi->id)
        ->get();

    return view('admin.dosen-prodi-show', compact('prodi', 'lecturers'));
}
}
