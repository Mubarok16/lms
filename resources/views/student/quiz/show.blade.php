@extends('student.app-student')

@section('ketjudul')
Quiz
@endsection

@section('judul')
{{ $quiz->judul }}
@endsection

@section('content')

<div class="lg:col-span-3">

    @if (session('success'))
    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        {{ session('success') }}
    </div>
    @endif

    @if ($jawabanSaya)
    {{-- Sudah pernah mengerjakan --}}
    <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">
        <h2 class="font-display text-lg font-semibold text-ink">
            Kamu sudah mengerjakan quiz ini
        </h2>
        <p class="mt-2 text-3xl font-bold text-ink">
            {{ $jawabanSaya->skor }}
            <span class="text-base font-normal text-ink/50">/ 100</span>
        </p>
        <p class="mt-1 text-sm text-ink/50">
            Dikerjakan pada {{ $jawabanSaya->waktu_submit->format('d M Y, H:i') }}
        </p>
    </div>
    @else
    {{-- Timer Countdown --}}
    @if ($waktuSelesai)
    <div id="quiz-timer"
        data-deadline="{{ $waktuSelesai->toIso8601String() }}"
        class="sticky top-4 z-10 mb-6 flex items-center justify-between rounded-2xl border border-line bg-white px-6 py-4 shadow-sm">
        <span class="text-sm font-medium text-ink/70">Sisa waktu pengerjaan</span>
        <span id="quiz-timer-value" class="font-display text-2xl font-bold text-ink">--:--</span>
    </div>
    @endif

    {{-- Form pengerjaan --}}
    <form id="quiz-form" method="POST" action="{{ route('student.quiz.submit', $quiz->id) }}">
        @csrf
        <input type="hidden" name="auto_submit" id="auto_submit_flag" value="0">

        {{-- ... card judul quiz tetap sama ... --}}

        <div class="space-y-4">
            @foreach ($quiz->questions as $q)
            <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">
                <p class="text-sm font-semibold text-ink">
                    {{ $q->nomor }}. {{ $q->pertanyaan }}
                </p>

                @if ($q->gambar)
                <img src="{{ Storage::url($q->gambar) }}" alt="Gambar soal"
                    class="mt-3 max-h-64 rounded-xl border border-line">
                @endif

                <div class="mt-4 space-y-2">
                    @foreach ($q->pilihanTersedia() as $huruf => $teks)
                    <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-line
                                      px-4 py-3 text-sm text-ink/80 transition hover:bg-paper
                                      has-[:checked]:border-ink has-[:checked]:bg-paper has-[:checked]:font-semibold">
                        <input type="radio" name="jawaban[{{ $q->id }}]" value="{{ $huruf }}"
                            class="h-4 w-4 quiz-radio" required>
                        <span>{{ $huruf }}. {{ $teks }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <button type="submit" id="quiz-submit-btn"
            class="mt-6 w-full rounded-lg bg-ink px-4 py-3 text-sm font-semibold text-white
               transition hover:bg-primaryDark"
            onclick="return confirm('Yakin ingin submit jawaban? Quiz tidak bisa diulang.')">
            Submit Jawaban
        </button>
    </form>

    @if ($waktuSelesai)
    <script>
        (function() {
            const timerEl = document.getElementById('quiz-timer');
            const valueEl = document.getElementById('quiz-timer-value');
            const form = document.getElementById('quiz-form');
            const flagInput = document.getElementById('auto_submit_flag');
            const deadline = new Date(timerEl.dataset.deadline).getTime();
            let submitted = false;

            function forceSubmit() {
                if (submitted) return;
                submitted = true;

                // Tandai sebagai auto-submit
                flagInput.value = '1';

                // Hilangkan required dari semua pilihan supaya soal
                // yang belum dijawab tidak menghalangi pengiriman
                document.querySelectorAll('.quiz-radio').forEach(el => {
                    el.required = false;
                });

                valueEl.textContent = '00:00';
                // form.submit() melewati validasi HTML5 secara native
                form.submit();
            }

            function tick() {
                const now = new Date().getTime();
                const diff = deadline - now;

                if (diff <= 0) {
                    forceSubmit();
                    return;
                }

                const totalSeconds = Math.floor(diff / 1000);
                const hours = Math.floor(totalSeconds / 3600);
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;

                const pad = (n) => String(n).padStart(2, '0');
                valueEl.textContent = hours > 0 ?
                    `${pad(hours)}:${pad(minutes)}:${pad(seconds)}` :
                    `${pad(minutes)}:${pad(seconds)}`;

                if (totalSeconds <= 300) {
                    valueEl.classList.add('text-red-600');
                    timerEl.classList.add('border-red-200', 'bg-red-50');
                }
            }

            tick();
            const interval = setInterval(() => {
                tick();
                if (submitted) clearInterval(interval);
            }, 1000);

            // Jaga-jaga kalau tab di-background lama dan setInterval di-throttle browser,
            // cek juga saat tab kembali aktif
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) tick();
            });
        })();
    </script>
    @endif
    @endif

</div>

@endsection