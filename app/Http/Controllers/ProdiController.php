<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Prodi;


class ProdiController extends Controller
{
    public function index()
    {
        $prodiList = Prodi::all();

        return view('admin.dosen.index', compact('prodiList'));
    }
}
