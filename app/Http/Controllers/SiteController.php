<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function info(string $page)
    {
        $pages = [
            'tentang' => ['Tentang UNWIR','Informasi singkat mengenai platform E-Learning Universitas Wiralodra.'],
            'fakultas-prodi' => ['Fakultas & Program Studi','Informasi akademik dan program studi dikelola melalui sistem E-Learning.'],
            'kalender-akademik' => ['Kalender Akademik','Jadwal akademik ditampilkan oleh administrator pada menu Jadwal Kuliah setelah data tersedia.'],
            'bantuan' => ['Pusat Bantuan','Gunakan menu Pesan untuk menghubungi administrator apabila membutuhkan bantuan akun atau pembelajaran.'],
            'privasi' => ['Kebijakan Privasi','Data akun dan aktivitas pembelajaran digunakan untuk kebutuhan layanan akademik E-Learning.'],
            'baak' => ['Hubungi BAAK','Silakan gunakan menu Pesan pada akun Anda untuk menyampaikan pertanyaan kepada administrator.'],
        ];
        abort_unless(isset($pages[$page]),404);
        [$title,$description]=$pages[$page];
        return view('site.info', compact('title','description'));
    }
}
