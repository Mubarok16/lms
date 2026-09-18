<?php

namespace App\Providers;

use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer([
            'partials.admin.sidebar',
            'partials.lecturer.sidebar',
            'partials.lecturer.sidebar-tambah-materi',
            'partials.student.sidebar',
            'partials.student.sidebar1',
        ], function ($view) {
            $user = Auth::user();
            $classes = collect();

            if ($user) {
                $query = Kelas::query()
                    ->with('matakuliah')
                    ->orderBy('kode_mk')
                    ->orderBy('kode_kelas');

                if ($user->role === 'admin') {
                    $classes = $query->get();
                } elseif ($user->role === 'lecturer') {
                    $lecturerId = optional($user->lecturer)->id;
                    $classes = $query
                        ->whereHas('pengajaranDosen', fn ($q) => $q->where('dosen_id', $lecturerId ?? 0))
                        ->get();
                } elseif ($user->role === 'student') {
                    $studentId = optional($user->student)->id;
                    $classes = $query
                        ->whereHas('pengajaranMahasiswa', fn ($q) => $q->where('mahasiswa_id', $studentId ?? 0))
                        ->get();
                }
            }

            $view->with('chatSidebarClasses', $classes);
        });
    }
}
