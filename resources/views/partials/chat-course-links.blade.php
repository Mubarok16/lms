@if(isset($chatSidebarClasses) && $chatSidebarClasses->isNotEmpty())
<div class="ml-9 mt-1 mb-2 space-y-1 border-l border-white/10 pl-3">
  @foreach($chatSidebarClasses as $chatKelas)
    <a href="{{ route('chat.show', $chatKelas) }}"
       class="block px-2 py-1.5 rounded-md text-[12px] text-paper/55 hover:bg-white/5 hover:text-paper transition-colors truncate"
       title="{{ $chatKelas->matakuliah->nama_mk ?? $chatKelas->kode_mk }} - Kelas {{ $chatKelas->kode_kelas }}">
      {{ $chatKelas->matakuliah->nama_mk ?? $chatKelas->kode_mk }}
      <span class="text-paper/30">· {{ $chatKelas->kode_kelas }}</span>
    </a>
  @endforeach
</div>
@endif
