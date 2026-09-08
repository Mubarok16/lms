@extends(Auth::user()->role === 'admin' ? 'admin.app-admin' : (Auth::user()->role === 'lecturer' ? 'lecturer.app-lecturer' : 'student.app-student'))

@section('ketjudul', 'Chat Mata Kuliah')
@section('judul', ($kelas->matakuliah->nama_mk ?? $kelas->kode_mk) . ' · Kelas ' . $kelas->kode_kelas)

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="border border-line rounded-2xl overflow-hidden bg-white shadow-sm">
        <div class="px-5 py-4 border-b border-line flex items-center justify-between gap-4">
            <div>
                <p class="font-semibold">{{ $kelas->matakuliah->nama_mk ?? $kelas->kode_mk }}</p>
                <p class="text-xs text-ink/45 font-mono">{{ $kelas->kode_mk }} · Kelas {{ $kelas->kode_kelas }}</p>
            </div>
            <a href="{{ route('chat.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Kembali ke daftar chat</a>
        </div>

        <div id="chat-box" class="h-[55vh] min-h-[380px] overflow-y-auto p-5 bg-slate-50 space-y-3">
            @forelse($messages as $message)
                @php($mine = $message->user_id === Auth::id())
                <div class="flex {{ $mine ? 'justify-end' : 'justify-start' }}" data-message-id="{{ $message->id }}">
                    <div class="max-w-[82%] md:max-w-[70%] {{ $mine ? 'bg-blue-600 text-white' : 'bg-white border border-line text-ink' }} rounded-2xl px-4 py-3 shadow-sm">
                        <div class="flex gap-2 items-center mb-1 text-[11px] {{ $mine ? 'text-blue-100' : 'text-ink/45' }}">
                            <span class="font-semibold">{{ $message->user->name ?? 'Pengguna' }}</span>
                            <span>·</span><span>{{ $message->user->role ?? '-' }}</span>
                        </div>
                        <p class="text-sm whitespace-pre-wrap break-words">{{ $message->message }}</p>
                        <p class="text-[10px] mt-1.5 text-right {{ $mine ? 'text-blue-100' : 'text-ink/40' }}">{{ optional($message->created_at)->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            @empty
                <div id="empty-chat" class="h-full flex items-center justify-center text-sm text-ink/40">Belum ada pesan. Mulai percakapan pertama.</div>
            @endforelse
        </div>

        <form id="chat-form" method="POST" action="{{ route('chat.store', $kelas) }}" class="p-4 border-t border-line bg-white">
            @csrf
            <div class="flex gap-3 items-end">
                <textarea id="message-input" name="message" rows="2" maxlength="3000" required placeholder="Tulis pesan..." class="flex-1 resize-none rounded-xl border-line focus:border-blue-400 focus:ring-blue-200 text-sm"></textarea>
                <button id="send-button" type="submit" class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition">Kirim</button>
            </div>
            @error('message')<p class="text-xs text-red-600 mt-2">{{ $message }}</p>@enderror
        </form>
    </div>
</div>

<script>
(() => {
    const box = document.getElementById('chat-box');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('message-input');
    const button = document.getElementById('send-button');
    const currentUserId = {{ (int) Auth::id() }};
    let lastId = {{ (int) ($messages->max('id') ?? 0) }};

    const scrollBottom = () => { box.scrollTop = box.scrollHeight; };
    scrollBottom();

    function appendMessage(message) {
        if (document.querySelector(`[data-message-id="${message.id}"]`)) return;
        document.getElementById('empty-chat')?.remove();

        const mine = Number(message.user_id) === currentUserId;
        const row = document.createElement('div');
        row.className = `flex ${mine ? 'justify-end' : 'justify-start'}`;
        row.dataset.messageId = message.id;

        const bubble = document.createElement('div');
        bubble.className = `max-w-[82%] md:max-w-[70%] ${mine ? 'bg-blue-600 text-white' : 'bg-white border border-line text-ink'} rounded-2xl px-4 py-3 shadow-sm`;

        const meta = document.createElement('div');
        meta.className = `flex gap-2 items-center mb-1 text-[11px] ${mine ? 'text-blue-100' : 'text-ink/45'}`;
        meta.textContent = `${message.name} · ${message.role}`;

        const text = document.createElement('p');
        text.className = 'text-sm whitespace-pre-wrap break-words';
        text.textContent = message.message;

        const time = document.createElement('p');
        time.className = `text-[10px] mt-1.5 text-right ${mine ? 'text-blue-100' : 'text-ink/40'}`;
        time.textContent = message.time;

        bubble.append(meta, text, time); row.appendChild(bubble); box.appendChild(row);
        lastId = Math.max(lastId, Number(message.id));
    }

    async function poll() {
        try {
            const response = await fetch(`{{ route('chat.messages', $kelas) }}?after=${lastId}`, {headers: {'Accept': 'application/json'}});
            if (!response.ok) return;
            const data = await response.json();
            if (data.messages?.length) {
                const nearBottom = box.scrollHeight - box.scrollTop - box.clientHeight < 120;
                data.messages.forEach(appendMessage);
                if (nearBottom) scrollBottom();
            }
        } catch (_) {}
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const value = input.value.trim();
        if (!value) return;
        button.disabled = true;
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {'Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},
                body: JSON.stringify({message:value})
            });
            if (!response.ok) throw new Error('Gagal mengirim pesan');
            const message = await response.json();
            appendMessage(message); input.value = ''; scrollBottom(); input.focus();
        } catch (e) { alert(e.message); }
        finally { button.disabled = false; }
    });

    setInterval(poll, 3000);
})();
</script>
@endsection
