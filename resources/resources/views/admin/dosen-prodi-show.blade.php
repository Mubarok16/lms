@extends('admin.app-admin')

@section('ketjudul')
Daftar
@endsection

@section('judul')
Dosen Prodi {{ $prodi->nama_prodi }}
@endsection

@section('content')
<div class="p-6">

    {{-- Nama Prodi --}}
    <div class="mb-6">
        <h2 class="font-display text-lg font-semibold">
            {{ $prodi->nama_prodi }}
        </h2>

        <p class="py-3 pr-4 text-ink/70">
            Daftar dosen Program Studi {{ $prodi->nama_prodi }}
        </p>
    </div>

    {{-- Daftar Dosen --}}
    <div class="bg-white rounded-lg shadow">

        <div class="p-4 border-b">
            <h3 class="font-semibold">
                Daftar Dosen
            </h3>
        </div>

        <div class="p-4">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead>
                        <tr class="border-b text-left">
                            <th class="py-3 px-4 w-16">No</th>
                            <th class="py-3 px-4">Nama Dosen</th>
                            <th class="py-3 px-4 w-32">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($lecturers as $lecturer)
                        <tr class="border-b last:border-b-0 hover:bg-gray-50">

                            {{-- No --}}
                            <td class="py-3 px-4">
                                {{ $loop->iteration }}
                            </td>

                            {{-- Nama Dosen --}}
                            <td class="py-3 px-4">

                                <div class="font-semibold">
                                    {{ $lecturer->user->name }}
                                </div>

                                <div class="text-sm text-gray-500">
                                    {{ $lecturer->user->email }}
                                </div>

                            </td>

                            {{-- Aksi --}}
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">

                                    <button type="button"
                                        onclick="openMkAmpuModal({{ $lecturer->id }}, '{{ addslashes($lecturer->user->name) }}')"
                                        class="px-3 py-2 rounded-md bg-blue-600 text-white text-xs font-medium hover:bg-blue-700 transition">

                                        MK diampu

                                    </button>

                                    <button type="button"
                                        onclick="openMkModal({{ $lecturer->id }}, '{{ addslashes($lecturer->user->name) }}')"
                                        class="px-3 py-2 rounded-md bg-blue-600 text-white text-xs font-medium hover:bg-blue-700 transition">

                                        Tambah MK

                                    </button>

                                </div>
                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="3" class="py-6 text-center text-gray-500">

                                Belum ada dosen pada program studi ini.

                            </td>

                        </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

{{-- Modal Tambah MK --}}
<div id="mkModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">

    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg">

        {{-- Header --}}
        <div class="flex items-center justify-between p-4 border-b">

            <div>
                <h3 class="font-semibold text-lg">
                    Tambah Mata Kuliah
                </h3>

                <p id="namaDosenModal" class="text-sm text-gray-500">
                </p>
            </div>

            <button type="button" onclick="closeMkModal()" class="text-gray-500 hover:text-gray-700 text-xl">

                &times;

            </button>

        </div>


        {{-- Body --}}
        <div class="p-4">

            {{-- Search --}}
            <div class="mb-4">

                <label class="block text-sm font-medium mb-1">
                    Cari Mata Kuliah
                </label>

                <input type="text" id="searchMk" placeholder="Ketik nama mata kuliah..."
                    class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    onkeyup="searchMk()">

            </div>


            {{-- Hasil MK --}}
            <div id="hasilMk" class="border rounded-md max-h-64 overflow-y-auto">

                <div class="p-4 text-center text-gray-400">
                    Ketik nama mata kuliah untuk mencari.
                </div>

            </div>

        </div>

    </div>

</div>

{{-- Modal MK Diampu --}}
<div id="mkAmpuModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">

    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">

        {{-- Header --}}
        <div class="flex items-center justify-between p-4 border-b">

            <div>
                <h3 class="font-semibold text-lg">
                    Mata Kuliah Diampu
                </h3>

                <p id="namaDosenAmpu" class="text-sm text-gray-500">
                </p>
            </div>

            <button type="button" onclick="closeMkAmpuModal()" class="text-gray-500 hover:text-gray-700 text-2xl">

                &times;

            </button>

        </div>


        {{-- Body --}}
        <div class="p-4">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead>
                        <tr class="border-b text-left">

                            <th class="py-3 px-4 w-16">
                                No
                            </th>

                            <th class="py-3 px-4">
                                Kode MK
                            </th>

                            <th class="py-3 px-4">
                                Nama Mata Kuliah
                            </th>

                            <th class="py-3 px-4 text-center">
                                SKS
                            </th>

                            <th class="py-3 px-4 text-center">
                                Aksi
                            </th>

                        </tr>
                    </thead>

                    <tbody id="daftarMkAmpu">

                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-400">

                                Memuat data...

                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Footer --}}
        <div class="p-4 border-t text-right">

            <button type="button" onclick="closeMkAmpuModal()"
                class="px-4 py-2 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">

                Tutup

            </button>

        </div>

    </div>

</div>

<script>
    // ============================================
    // State dosen yang sedang aktif di modal MK Diampu
    // Disimpan supaya bisa reload data setelah hapus,
    // tanpa perlu memanggil fungsi yang tidak ada (loadMkAmpu)
    // ============================================
    let currentLecturerId = null;
    let currentLecturerName = null;

    // State dosen yang sedang aktif di modal Tambah MK
    let selectedLecturerId = null;


    // ============================================
    // MODAL: MK DIAMPU
    // ============================================

    function openMkAmpuModal(lecturerId, namaDosen) {

        // simpan state dosen aktif, dipakai lagi setelah hapus data
        currentLecturerId = lecturerId;
        currentLecturerName = namaDosen;

        const modal = document.getElementById('mkAmpuModal');
        const daftar = document.getElementById('daftarMkAmpu');
        const namaDosenElement = document.getElementById('namaDosenAmpu');

        namaDosenElement.textContent = namaDosen;

        daftar.innerHTML = `
            <tr>
                <td colspan="5" class="py-6 text-center text-gray-400">
                    Memuat data...
                </td>
            </tr>
        `;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        fetch(`{{ url('/pengajaran') }}/${lecturerId}/matakuliah`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(async response => {

                const data = await response.json();

                if (!response.ok) {
                    console.error('Error dari server:', data);
                    throw new Error(data.message ?? 'Gagal mengambil data mata kuliah.');
                }

                return data;
            })
            .then(data => {

                console.log('Data MK:', data);

                if (data.length === 0) {

                    daftar.innerHTML = `
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-400">
                                Dosen ini belum memiliki mata kuliah yang diampu.
                            </td>
                        </tr>
                    `;

                    return;
                }

                daftar.innerHTML = data.map((mk, index) => `

                    <tr class="border-b hover:bg-gray-50">

                        <td class="py-3 px-4">
                            ${index + 1}
                        </td>

                        <td class="py-3 px-4 font-medium">
                            ${mk.kode_mk}
                        </td>

                        <td class="py-3 px-4">
                            ${mk.nama_mk}
                        </td>

                        <td class="py-3 px-4 text-center">
                            ${mk.sks}
                        </td>

                        <td class="py-3 px-4 text-center">

                            <button
                                type="button"
                                class="px-3 py-1 rounded-md bg-red-500 text-white hover:bg-red-600"
                                onclick="hapusMkAmpu(${mk.kelas_id})">

                                Hapus

                            </button>

                        </td>

                    </tr>

                `).join('');

            })
            .catch(error => {

                console.error('Gagal mengambil MK:', error);

                daftar.innerHTML = `
                    <tr>
                        <td colspan="5" class="py-6 text-center text-red-500">
                            Gagal menampilkan mata kuliah.
                        </td>
                    </tr>
                `;
            });
    }


    function closeMkAmpuModal() {
        const modal = document.getElementById('mkAmpuModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        currentLecturerId = null;
        currentLecturerName = null;
    }


    function hapusMkAmpu(id) {
        if (!confirm('Yakin ingin menghapus mata kuliah ini dari daftar ampu?')) {
            return;
        }

        fetch(`/kelas/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            })
            .then(async response => {

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message ?? 'Gagal menghapus data.');
                }

                return data;
            })
            .then(data => {

                if (data.success) {
                    // FIX: sebelumnya memanggil loadMkAmpu() yang tidak pernah didefinisikan,
                    // sehingga selalu masuk ke .catch() walau hapus di server berhasil.
                    // Solusinya: reload ulang modal pakai data dosen yang sedang aktif.
                    openMkAmpuModal(currentLecturerId, currentLecturerName);
                } else {
                    alert('Gagal menghapus data');
                }
            })
            .catch(error => {
                console.error(error);
                alert(error.message ?? 'Terjadi kesalahan saat menghapus data');
            });
    }


    // ============================================
    // MODAL: TAMBAH MK
    // ============================================

    function openMkModal(lecturerId, lecturerName) {
        selectedLecturerId = lecturerId;

        document.getElementById('namaDosenModal').innerText =
            'Dosen: ' + lecturerName;

        document.getElementById('searchMk').value = '';

        document.getElementById('hasilMk').innerHTML = `
            <div class="p-4 text-center text-gray-400">
                Ketik nama mata kuliah untuk mencari.
            </div>
        `;

        const modal = document.getElementById('mkModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.getElementById('searchMk').focus();
    }


    function closeMkModal() {
        const modal = document.getElementById('mkModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        selectedLecturerId = null;
    }


    function searchMk() {
        const keyword = document.getElementById('searchMk').value.trim();
        const hasil = document.getElementById('hasilMk');

        if (keyword.length < 2) {

            hasil.innerHTML = `
                <div class="p-4 text-center text-gray-400">
                    Ketik minimal 2 karakter.
                </div>
            `;

            return;
        }

        hasil.innerHTML = `
            <div class="p-4 text-center text-gray-400">
                Mencari...
            </div>
        `;

        fetch(`{{ route('admin.matakuliah.search') }}?keyword=${encodeURIComponent(keyword)}`)
            .then(response => response.json())
            .then(data => {

                if (data.length === 0) {

                    hasil.innerHTML = `
                        <div class="p-4 text-center text-gray-400">
                            Mata kuliah tidak ditemukan.
                        </div>
                    `;

                    return;
                }

                hasil.innerHTML = data.map(mk => `

                    <button
                        type="button"
                        onclick="pilihMk('${mk.kode_mk}')"
                        class="w-full text-left p-3 border-b last:border-b-0 hover:bg-blue-50 transition">

                        <div class="font-medium">
                            ${mk.kode_mk}
                        </div>

                        <div class="text-sm text-gray-600">
                            ${mk.nama_mk}
                        </div>

                    </button>

                `).join('');

            })
            .catch(error => {

                console.error(error);

                hasil.innerHTML = `
                    <div class="p-4 text-center text-red-500">
                        Terjadi kesalahan saat mencari mata kuliah.
                    </div>
                `;

            });
    }


    function pilihMk(kodeMk) {
        if (!selectedLecturerId) {
            alert('Dosen belum dipilih.');
            return;
        }

        fetch(`{{ route('admin.pengajaran.store') }}`, {

                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },

                body: JSON.stringify({
                    lecturer_id: selectedLecturerId,
                    kode_mk: kodeMk
                })

            })
            .then(async response => {

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message ?? 'Gagal menyimpan data.');
                }

                return data;

            })
            .then(data => {

                if (data.success) {
                    closeMkModal();
                    alert(data.message);
                }

            })
            .catch(error => {
                console.error(error);
                alert(error.message);
            });
    }
</script>
@endsection