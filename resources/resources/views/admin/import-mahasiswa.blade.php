@extends('admin.app-admin')
@section('ketjudul')
    Selamat Datang di
@endsection

@section('judul')
    Akun Mahasiswa
@endsection

@section('content')
    <!-- Header -->
    <div class="mb-6">
        <h2 class="font-display text-lg font-semibold">Import Akun Mahasiswa</h2>
        <p class="text-sm text-ink/50 mt-0.5">Tambahkan akun mahasiswa secara massal menggunakan file Excel</p>
    </div>

    <!-- Card: Langkah-langkah -->
    <div class="border border-line rounded-xl p-5 mb-6 bg-paper/40">
        <h3 class="text-sm font-semibold mb-3">Cara Import Akun Mahasiswa</h3>
        <ol class="space-y-2 text-sm text-ink/70">
            <li class="flex gap-2">
                <span
                    class="flex-shrink-0 w-5 h-5 rounded-full bg-teal/10 text-teal text-xs font-mono flex items-center justify-center">1</span>
                Unduh template Excel terlebih dahulu dengan menekan tombol di bawah.
            </li>
            <li class="flex gap-2">
                <span
                    class="flex-shrink-0 w-5 h-5 rounded-full bg-teal/10 text-teal text-xs font-mono flex items-center justify-center">2</span>
                Isi data dosen sesuai format kolom pada template (jangan mengubah nama kolom).
            </li>
            <li class="flex gap-2">
                <span
                    class="flex-shrink-0 w-5 h-5 rounded-full bg-teal/10 text-teal text-xs font-mono flex items-center justify-center">3</span>
                Simpan file, lalu unggah kembali melalui form di bawah ini.
            </li>
        </ol>
    </div>

    <!-- Card: Download Template -->
    <div class="border border-line rounded-xl p-5 mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-teal/10 flex items-center justify-center">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" class="text-teal">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                    <path d="M14 2v6h6" />
                    <path d="M12 12v6" />
                    <path d="M9 15l3 3 3-3" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium">Template_Akun_Mahasiswa.xlsx</p>
                <p class="text-xs text-ink/50">Format kolom: NPM, Nama, Email, Program Studi</p>
            </div>
        </div>

        <a href="{{ asset('templates/template_import_mahasiswa.xlsx') }}" download
            class="inline-flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-lg border border-line hover:bg-paper/60 transition-colors">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 3v12" />
                <path d="M7 10l5 5 5-5" />
                <path d="M5 21h14" />
            </svg>
            Unduh Template
        </a>
    </div>

    <!-- Card: Upload Excel -->
    <div class="border border-line rounded-xl p-5">
        <h3 class="text-sm font-semibold mb-4">Unggah File Excel</h3>

        <form action="{{ route('admin.mahasiswa.import.process') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="dropZone"
                class="border-2 border-dashed border-line rounded-xl p-8 text-center cursor-pointer hover:border-teal/50 hover:bg-teal/5 transition-colors"
                onclick="document.getElementById('fileInput').click()">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" class="mx-auto mb-3 text-ink/30">
                    <path d="M12 16V4" />
                    <path d="M7 9l5-5 5 5" />
                    <path d="M4 20h16" />
                </svg>
                <p class="text-sm text-ink/70">
                    <span class="text-teal font-medium">Klik untuk pilih file</span> atau tarik file ke sini
                </p>
                <p class="text-xs text-ink/40 mt-1">Format .xlsx atau .xls, maksimal 5MB</p>

                <input id="fileInput" type="file" name="file_excel" accept=".xlsx,.xls" class="hidden"
                    onchange="document.getElementById('fileName').textContent = this.files[0] ? this.files[0].name : ''">
            </div>

            <!-- Nama file terpilih -->
            <p id="fileName" class="text-sm text-ink/70 mt-3 font-mono"></p>

            <div class="flex justify-end gap-2 mt-5">
                <button type="button"
                    class="px-4 py-2 text-sm font-medium rounded-lg border border-line text-ink/70 hover:bg-paper/60">
                    Batal
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-teal text-white hover:bg-teal/90">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M12 3v12" />
                        <path d="M7 10l5 5 5-5" />
                        <path d="M5 21h14" />
                    </svg>
                    Import Data
                </button>
            </div>
        </form>
    </div>
@endsection
