@extends('admin.app-admin')

@section('ketjudul')
DAFTAR
@endsection

@section('judul')
Peserta Matakuliah
@endsection

@section('content')
{{-- =========================================================
        SEARCH PENGAJARAN
    ========================================================== --}}
<div class="mb-4 flex items-center justify-between gap-3">

    <div class="flex items-center gap-2">

        <input type="text" id="searchPengajaran" placeholder="Cari MK atau dosen..."
            class="w-64 h-9 px-3 border border-gray-300 rounded-md
                       text-sm focus:outline-none focus:ring-2
                       focus:ring-blue-500 focus:border-blue-500">

        <button type="button" onclick="cariPengajaran()"
            class="h-9 px-4 inline-flex items-center justify-center
                       rounded-md bg-blue-600 text-white text-sm
                       font-medium hover:bg-blue-700 transition">
            Cari
        </button>

        <button type="button" onclick="resetPengajaran()"
            class="h-9 px-4 inline-flex items-center justify-center
                       rounded-md bg-gray-200 text-gray-700 text-sm
                       font-medium hover:bg-gray-300 transition">
            Reset
        </button>

    </div>

</div>


{{-- =========================================================
        TABEL KELAS / MATA KULIAH
    ========================================================== --}}
<div class="overflow-x-auto">

    <table class="w-full text-sm text-left">

        <thead class="bg-gray-100">

            <tr>

                <th class="px-4 py-3">
                    No
                </th>

                <th class="px-4 py-3">
                    Nama MK
                </th>

                <th class="px-4 py-3">
                    Kelas
                </th>

                <th class="px-4 py-3">
                    Prodi
                </th>

                <th class="px-4 py-3">
                    Dosen Pengampu
                </th>

                <th class="px-4 py-3 text-center">
                    Aksi
                </th>

            </tr>

        </thead>


        <tbody id="tabelPengajaran">

            @forelse ($pengajaran as $item)
            <tr class="border-b">

                {{-- NO --}}
                <td class="px-4 py-3">
                    {{ $loop->iteration }}
                </td>


                {{-- MATA KULIAH --}}
                <td class="px-4 py-3">

                    <div class="font-medium">
                        {{ $item->kelas->matakuliah->nama_mk ?? '-' }}
                    </div>

                    <div class="text-xs text-gray-500">
                        {{ $item->kelas->kode_mk ?? '-' }}
                    </div>

                </td>


                {{-- KELAS --}}
                <td class="px-4 py-3">

                    <span
                        class="inline-flex items-center px-2 py-1
                                       rounded-md bg-gray-100 text-gray-700
                                       text-xs font-medium">
                        {{ $item->kelas->kode_kelas ?? '-' }}
                    </span>

                </td>


                {{-- PRODI --}}
                <td class="px-4 py-3">

                    {{ $item->lecturer->prodi->nama_prodi ?? '-' }}

                </td>


                {{-- DOSEN --}}
                <td class="px-4 py-3">

                    {{ $item->lecturer->user->name ?? '-' }}

                </td>


                <td class="px-4 py-3 text-center">

                    <div class="flex items-center justify-center gap-2">

                        {{-- TAMBAH PESERTA --}}
                        <button type="button"
                            onclick="openPesertaModal(
                {{ $item->kelas->id }},
                @js($item->kelas->matakuliah->nama_mk . ' - Kelas ' . $item->kelas->kode_kelas)
            )"
                            class="h-9 px-3 inline-flex items-center justify-center
                   rounded-md bg-blue-600 text-white text-xs
                   font-medium leading-none
                   hover:bg-blue-700 transition">
                            Peserta
                        </button>


                        {{-- DAFTAR PESERTA --}}
                        <button type="button"
                            onclick="openDaftarPesertaModal(
                {{ $item->kelas->id }},
                @js($item->kelas->matakuliah->nama_mk . ' - Kelas ' . $item->kelas->kode_kelas)
            )"
                            class="h-9 px-3 inline-flex items-center justify-center
                   rounded-md bg-green-600 text-white text-xs
                   font-medium leading-none
                   hover:bg-green-700 transition">
                            Daftar Peserta
                        </button>

                    </div>

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                    Belum ada data pengajaran.
                </td>

            </tr>
            @endforelse

        </tbody>

    </table>

</div>


{{-- =========================================================
        MODAL PESERTA
    ========================================================== --}}
<div id="pesertaModal" class="hidden fixed inset-0 z-50
               bg-black/50 items-center justify-center p-4">

    <div class="bg-white rounded-lg shadow-xl
                   w-full max-w-2xl">

        {{-- HEADER MODAL --}}
        <div class="flex items-center justify-between
                       px-6 py-4 border-b">

            <div>

                <h2 class="text-lg font-semibold">
                    Tambah Peserta
                </h2>

                <p id="namaMatakuliah" class="text-sm text-gray-500"></p>

            </div>


            <button type="button" onclick="closePesertaModal()"
                class="text-gray-500 hover:text-gray-700
                           text-xl">
                ✕
            </button>

        </div>


        {{-- ISI MODAL --}}
        <div class="p-6">

            <input type="text" id="searchStudent" placeholder="Cari NIM atau nama mahasiswa..."
                class="w-full border border-gray-300
                           rounded-md px-3 py-2
                           focus:outline-none
                           focus:ring-2 focus:ring-blue-500">


            {{-- HASIL PENCARIAN --}}
            <div id="hasilStudent" class="mt-4 max-h-80 overflow-y-auto">

                <div class="text-center text-gray-400 py-6">
                    Ketik untuk mencari mahasiswa...
                </div>

            </div>

        </div>


        {{-- FOOTER --}}
        <div class="flex items-center justify-between
                       px-6 py-4 border-t">

            <span id="jumlahTerpilih" class="text-sm text-gray-500">
                0 mahasiswa dipilih
            </span>


            <button type="button" onclick="tambahPesertaTerpilih()"
                class="h-9 px-4 inline-flex items-center
                           justify-center rounded-md
                           bg-blue-600 text-white text-sm
                           font-medium hover:bg-blue-700
                           transition">
                Tambahkan Terpilih
            </button>

        </div>

    </div>

</div>

{{-- =========================================================
    MODAL DAFTAR PESERTA
========================================================== --}}
<div id="daftarPesertaModal" class="hidden fixed inset-0 z-50
           bg-black/50 items-center justify-center p-4">

    <div class="bg-white rounded-lg shadow-xl
                w-full max-w-2xl">

        {{-- HEADER --}}
        <div class="flex items-center justify-between
                    px-6 py-4 border-b">

            <div>

                <h2 class="text-lg font-semibold">
                    Daftar Peserta
                </h2>

                <p id="judulDaftarPeserta" class="text-sm text-gray-500">
                </p>

            </div>

            <button type="button" onclick="closeDaftarPesertaModal()"
                class="text-gray-500 hover:text-gray-700 text-xl">

                ✕

            </button>

        </div>


        {{-- ISI --}}
        <div class="p-6">

            <div id="hasilDaftarPeserta" class="max-h-96 overflow-y-auto">

                <div class="text-center text-gray-400 py-6">

                    Memuat data...

                </div>

            </div>

        </div>


        {{-- FOOTER --}}
        <div class="flex items-center justify-between
                    px-6 py-4 border-t">

            <span id="jumlahPeserta" class="text-sm text-gray-500">

                0 mahasiswa

            </span>

            <button type="button" onclick="closeDaftarPesertaModal()"
                class="h-9 px-4 rounded-md
                       bg-gray-200 text-gray-700
                       text-sm font-medium
                       hover:bg-gray-300">

                Tutup

            </button>

        </div>

    </div>

</div>
{{-- =========================================================
        JAVASCRIPT
    ========================================================== --}}
<script>
    /*
                                |--------------------------------------------------------------------------
                                | VARIABEL
                                |--------------------------------------------------------------------------
                                */

    let selectedStudents = new Set();

    let kelasId = null;


    /*
    |--------------------------------------------------------------------------
    | BUKA MODAL
    |--------------------------------------------------------------------------
    */

    function openPesertaModal(id, namaMk) {

        kelasId = id;

        // Reset mahasiswa terpilih
        selectedStudents.clear();

        // Tampilkan nama MK
        document.getElementById('namaMatakuliah').innerText = namaMk;

        // Tampilkan modal
        const modal = document.getElementById('pesertaModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Reset search
        document.getElementById('searchStudent').value = '';

        // Reset hasil
        document.getElementById('hasilStudent').innerHTML = `
                <div class="text-center text-gray-400 py-6">
                    Ketik untuk mencari mahasiswa...
                </div>
            `;

        updateJumlahTerpilih();

        // Fokus search
        document.getElementById('searchStudent').focus();
    }


    /*
    |--------------------------------------------------------------------------
    | TUTUP MODAL
    |--------------------------------------------------------------------------
    */

    function closePesertaModal() {

        const modal = document.getElementById('pesertaModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        kelasId = null;
    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH MAHASISWA
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('searchStudent')
        .addEventListener('input', function() {

            const search = this.value.trim();

            /*
            |--------------------------------------------------------------------------
            | Minimal 2 karakter
            |--------------------------------------------------------------------------
            */

            if (search.length < 2) {

                document.getElementById('hasilStudent').innerHTML = `
                        <div class="text-center text-gray-400 py-6">
                            Ketik minimal 2 karakter...
                        </div>
                    `;

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Pastikan kelas sudah dipilih
            |--------------------------------------------------------------------------
            */

            if (!kelasId) {

                console.error('kelasId tidak tersedia.');

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | URL SEARCH
            |--------------------------------------------------------------------------
            */

            const url =
                `/kelas/${kelasId}/students?search=${encodeURIComponent(search)}`;

            console.log('Mencari mahasiswa:', url);


            /*
            |--------------------------------------------------------------------------
            | REQUEST
            |--------------------------------------------------------------------------
            */

            fetch(url, {

                    method: 'GET',

                    headers: {
                        'Accept': 'application/json'
                    }

                })

                .then(async response => {

                    const data = await response.json();

                    if (!response.ok) {

                        console.error(
                            'Error server:',
                            data
                        );

                        throw new Error(
                            data.message ??
                            'Gagal mengambil data mahasiswa.'
                        );
                    }

                    return data;

                })

                .then(students => {

                    let html = '';


                    /*
                    |--------------------------------------------------------------------------
                    | Tidak ada mahasiswa
                    |--------------------------------------------------------------------------
                    */

                    if (!Array.isArray(students) || students.length === 0) {

                        html = `
                            <div class="text-center text-gray-400 py-6">
                                Mahasiswa tidak ditemukan.
                            </div>
                        `;

                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Ada mahasiswa
                    |--------------------------------------------------------------------------
                    */
                    else {

                        students.forEach(student => {

                            const studentId =
                                String(student.id);

                            const checked =
                                selectedStudents.has(studentId);


                            html += `

                                <label
                                    class="flex items-center
                                           justify-between
                                           p-3 border-b
                                           hover:bg-gray-50
                                           cursor-pointer"
                                >

                                    <div
                                        class="flex items-center gap-3"
                                    >

                                        <input
                                            type="checkbox"
                                            class="student-checkbox
                                                   w-4 h-4"
                                            value="${student.id}"
                                            ${checked ? 'checked' : ''}
                                        >

                                        <div>

                                            <div
                                                class="font-medium"
                                            >
                                                ${student.user?.name ?? '-'}
                                            </div>

                                            <div
                                                class="text-xs
                                                       text-gray-500"
                                            >
                                                ${student.nim ?? '-'}
                                                ·
                                                ${student.prodi?.nama_prodi ?? '-'}
                                            </div>

                                        </div>

                                    </div>

                                </label>

                            `;
                        });

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Tampilkan hasil
                    |--------------------------------------------------------------------------
                    */

                    document.getElementById(
                        'hasilStudent'
                    ).innerHTML = html;

                })

                .catch(error => {

                    console.error(
                        'Gagal mengambil mahasiswa:',
                        error
                    );

                    document.getElementById(
                        'hasilStudent'
                    ).innerHTML = `

                        <div
                            class="text-center
                                   text-red-500 py-6"
                        >
                            Gagal mengambil data mahasiswa.
                        </div>

                    `;

                });

        });


    /*
    |--------------------------------------------------------------------------
    | CHECKBOX MAHASISWA
    |--------------------------------------------------------------------------
    */

    document.addEventListener('change', function(e) {

        if (
            !e.target.classList.contains(
                'student-checkbox'
            )
        ) {
            return;
        }


        const studentId =
            String(e.target.value);


        if (e.target.checked) {

            selectedStudents.add(studentId);

        } else {

            selectedStudents.delete(studentId);

        }


        updateJumlahTerpilih();

    });


    /*
    |--------------------------------------------------------------------------
    | UPDATE JUMLAH MAHASISWA DIPILIH
    |--------------------------------------------------------------------------
    */

    function updateJumlahTerpilih() {

        document.getElementById(
                'jumlahTerpilih'
            ).innerText =
            `${selectedStudents.size} mahasiswa dipilih`;

    }


    /*
    |--------------------------------------------------------------------------
    | TAMBAH PESERTA
    |--------------------------------------------------------------------------
    */

    function tambahPesertaTerpilih() {

        const studentIds =
            Array.from(selectedStudents);


        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        */

        if (!kelasId) {

            alert('Kelas belum dipilih.');

            return;
        }


        if (studentIds.length === 0) {

            alert(
                'Pilih minimal satu mahasiswa.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | REQUEST
        |--------------------------------------------------------------------------
        |
        | Perhatikan:
        | Sekarang menggunakan kelas_id.
        |
        */

        fetch(
                `/kelas/${kelasId}/peserta`, {

                    method: 'POST',

                    headers: {

                        'Content-Type': 'application/json',

                        'Accept': 'application/json',

                        'X-CSRF-TOKEN': document
                            .querySelector(
                                'meta[name="csrf-token"]'
                            )
                            .getAttribute(
                                'content'
                            )

                    },

                    body: JSON.stringify({

                        student_ids: studentIds

                    })

                }
            )

            .then(async response => {

                const data =
                    await response.json();


                if (!response.ok) {

                    throw new Error(
                        data.message ??
                        'Gagal menambahkan mahasiswa.'
                    );

                }


                return data;

            })

            .then(data => {

                if (data.success) {

                    alert(data.message);

                    closePesertaModal();

                    selectedStudents.clear();

                } else {

                    alert(
                        data.message ??
                        'Gagal menambahkan mahasiswa.'
                    );

                }

            })

            .catch(error => {

                console.error(
                    'Error tambah peserta:',
                    error
                );

                alert(
                    error.message ??
                    'Terjadi kesalahan saat menambahkan mahasiswa.'
                );

            });

    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH TABEL PENGAJARAN
    |--------------------------------------------------------------------------
    */

    function cariPengajaran() {

        const keyword =
            document
            .getElementById(
                'searchPengajaran'
            )
            .value
            .toLowerCase()
            .trim();


        const rows =
            document.querySelectorAll(
                '#tabelPengajaran tr'
            );


        rows.forEach(row => {

            const text =
                row.innerText
                .toLowerCase();


            if (text.includes(keyword)) {

                row.style.display = '';

            } else {

                row.style.display = 'none';

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | RESET SEARCH TABEL
    |--------------------------------------------------------------------------
    */

    function resetPengajaran() {

        document.getElementById(
            'searchPengajaran'
        ).value = '';


        const rows =
            document.querySelectorAll(
                '#tabelPengajaran tr'
            );


        rows.forEach(row => {

            row.style.display = '';

        });

    }
    /*
    |--------------------------------------------------------------------------
    | MODAL DAFTAR PESERTA
    |--------------------------------------------------------------------------
    */

    function openDaftarPesertaModal(kelasId, namaMk) {

        const modal = document.getElementById(
            'daftarPesertaModal'
        );

        const hasil = document.getElementById(
            'hasilDaftarPeserta'
        );

        const judul = document.getElementById(
            'judulDaftarPeserta'
        );

        const jumlah = document.getElementById(
            'jumlahPeserta'
        );


        // Tampilkan modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');


        // Judul
        judul.innerText = namaMk;


        // Loading
        hasil.innerHTML = `
        <div class="text-center text-gray-400 py-6">
            Memuat daftar peserta...
        </div>
    `;


        jumlah.innerText = 'Memuat...';


        /*
        |--------------------------------------------------------------------------
        | REQUEST
        |--------------------------------------------------------------------------
        */

        fetch(`/kelas/${kelasId}/peserta`, {

                method: 'GET',

                headers: {
                    'Accept': 'application/json'
                }

            })

            .then(async response => {

                const data = await response.json();

                if (!response.ok) {

                    throw new Error(
                        data.message ??
                        'Gagal mengambil daftar peserta.'
                    );

                }

                return data;

            })

            .then(data => {

                if (!data.success) {

                    throw new Error(
                        data.message ??
                        'Gagal mengambil daftar peserta.'
                    );

                }


                const peserta = data.data;


                /*
                |--------------------------------------------------------------------------
                | TIDAK ADA PESERTA
                |--------------------------------------------------------------------------
                */

                if (!peserta || peserta.length === 0) {

                    hasil.innerHTML = `
                <div class="text-center text-gray-400 py-10">

                    <div class="text-3xl mb-2">
                        👨‍🎓
                    </div>

                    <div>
                        Belum ada peserta
                        pada kelas ini.
                    </div>

                </div>
            `;

                    jumlah.innerText =
                        '0 mahasiswa';

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | TAMPILKAN PESERTA
                |--------------------------------------------------------------------------
                */

                let html = '';


                peserta.forEach((item, index) => {

                    const student = item.student;

                    const nama = student?.user?.name ?? '-';

                    const nim = student?.nim ?? '-';

                    const prodi = student?.prodi?.nama_prodi ?? '-';

                    html += `
        <div
            class="flex items-center gap-4
                   p-3 border-b
                   hover:bg-gray-50"
        >

            <div
                class="w-8 h-8
                       flex items-center
                       justify-center
                       rounded-full
                       bg-gray-100
                       text-gray-600
                       text-sm
                       font-medium"
            >
                ${index + 1}
            </div>

            <div class="flex-1">

                <div class="font-medium">
                    ${nama}
                </div>

                <div class="text-xs text-gray-500">

                    ${nim}

                    <span class="mx-1">
                        ·
                    </span>

                    ${prodi}

                </div>

            </div>

        </div>
    `;
                });


                hasil.innerHTML = html;


                jumlah.innerText =
                    `${peserta.length} mahasiswa`;

            })

            .catch(error => {

                console.error(
                    'Gagal mengambil daftar peserta:',
                    error
                );


                hasil.innerHTML = `

            <div
                class="text-center
                       text-red-500
                       py-8"
            >

                Gagal mengambil daftar peserta.

                <div
                    class="text-xs
                           text-gray-400
                           mt-2"
                >
                    ${error.message}
                </div>

            </div>

        `;

                jumlah.innerText =
                    'Gagal memuat';

            });

    }


    /*
    |--------------------------------------------------------------------------
    | TUTUP MODAL DAFTAR PESERTA
    |--------------------------------------------------------------------------
    */

    function closeDaftarPesertaModal() {

        const modal =
            document.getElementById(
                'daftarPesertaModal'
            );

        modal.classList.add('hidden');
        modal.classList.remove('flex');

    }
</script>
@endsection