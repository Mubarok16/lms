@extends('student.app-student')

@section('ketjudul')
Absensi
@endsection

@section('judul')
Scan QR Absensi
@endsection

@section('content')

<div class="lg:col-span-3">

    <div class="mx-auto max-w-md">

        <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">

            <h2 class="font-display text-lg font-semibold text-ink">
                Scan QR Absensi
            </h2>
            <p class="mt-1 text-sm text-ink/50">
                Arahkan kamera ke QR code yang ditampilkan dosen.
            </p>

            {{-- Area kamera --}}
            <div class="mt-5 overflow-hidden rounded-xl border border-line">
                <div id="qr-reader" class="w-full"></div>
            </div>

            {{-- Status hasil scan --}}
            <div id="qr-result" class="mt-4"></div>

        </div>

    </div>

</div>

{{-- Library QR Scanner --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const resultBox = document.getElementById('qr-result');
        let isProcessing = false;

        function showResult(success, message) {
            resultBox.innerHTML = `
                    <div class="rounded-xl border px-4 py-3 text-sm ${success
                        ? 'border-green-200 bg-green-50 text-green-700'
                        : 'border-red-200 bg-red-50 text-red-600'}">
                        ${message}
                    </div>
                `;
        }

        const scanner = new Html5Qrcode("qr-reader");

        function onScanSuccess(decodedText) {
            if (isProcessing) return;
            isProcessing = true;

            scanner.pause();

            fetch("{{ route('student.absensi.absen') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        token: decodedText
                    }),
                })
                .then(res => res.json().then(data => ({
                    status: res.status,
                    body: data
                })))
                .then(({
                    status,
                    body
                }) => {
                    showResult(body.success, body.message);

                    if (body.success) {
                        scanner.stop();
                    } else {
                        setTimeout(() => {
                            isProcessing = false;
                            scanner.resume();
                        }, 2000);
                    }
                })
                .catch(() => {
                    showResult(false, 'Terjadi kesalahan, coba lagi.');
                    setTimeout(() => {
                        isProcessing = false;
                        scanner.resume();
                    }, 2000);
                });
        }

        scanner.start({
                facingMode: "environment"
            }, {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                }
            },
            onScanSuccess
        ).catch(err => {
            showResult(false, 'Tidak bisa mengakses kamera. Pastikan izin kamera diaktifkan.');
        });
    });
</script>

@endsection