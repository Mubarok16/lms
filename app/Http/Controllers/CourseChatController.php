<?php

namespace App\Http\Controllers;

use App\Models\CourseChat;
use App\Models\Kelas;
use Illuminate\Http\Request;

class CourseChatController extends Controller
{
    public function index(Request $request)
    {
        $classes = $this->accessibleClasses($request)->get();
        return view('chat.index', compact('classes'));
    }

    public function show(Request $request, Kelas $kelas)
    {
        $this->authorizeClass($request, $kelas);
        $kelas->load('matakuliah');
        $messages = CourseChat::with('user')
            ->where('kelas_id', $kelas->id)
            ->latest('id')->limit(200)->get()->sortBy('id')->values();

        return view('chat.show', compact('kelas', 'messages'));
    }

    public function store(Request $request, Kelas $kelas)
    {
        $this->authorizeClass($request, $kelas);
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $message = CourseChat::create([
            'kelas_id' => $kelas->id,
            'user_id' => $request->user()->id,
            'message' => trim($validated['message']),
        ]);

        $message->load('user');

        if ($request->expectsJson()) {
            return response()->json($this->messagePayload($message), 201);
        }

        return back();
    }

    public function messages(Request $request, Kelas $kelas)
    {
        $this->authorizeClass($request, $kelas);
        $after = max(0, (int) $request->query('after', 0));

        $messages = CourseChat::with('user')
            ->where('kelas_id', $kelas->id)
            ->when($after > 0, fn ($q) => $q->where('id', '>', $after))
            ->orderBy('id')
            ->limit(200)
            ->get()
            ->map(fn ($message) => $this->messagePayload($message));

        return response()->json(['messages' => $messages]);
    }

    private function accessibleClasses(Request $request)
    {
        $user = $request->user();
        $query = Kelas::with('matakuliah')->orderBy('kode_mk')->orderBy('kode_kelas');

        if ($user->role === 'admin') {
            return $query;
        }

        if ($user->role === 'lecturer') {
            $lecturerId = optional($user->lecturer)->id;
            return $query->whereHas('pengajaranDosen', fn ($q) => $q->where('dosen_id', $lecturerId ?? 0));
        }

        if ($user->role === 'student') {
            $studentId = optional($user->student)->id;
            return $query->whereHas('pengajaranMahasiswa', fn ($q) => $q->where('mahasiswa_id', $studentId ?? 0));
        }

        return $query->whereRaw('1 = 0');
    }

    private function authorizeClass(Request $request, Kelas $kelas): void
    {
        if (!$this->accessibleClasses($request)->whereKey($kelas->id)->exists()) {
            abort(403, 'Anda tidak memiliki akses ke chat mata kuliah ini.');
        }
    }

    private function messagePayload(CourseChat $message): array
    {
        return [
            'id' => $message->id,
            'user_id' => $message->user_id,
            'name' => $message->user?->name ?? 'Pengguna',
            'role' => $message->user?->role ?? '-',
            'message' => $message->message,
            'time' => optional($message->created_at)->format('d/m/Y H:i'),
        ];
    }
}
