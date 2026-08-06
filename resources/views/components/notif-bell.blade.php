{{-- resources/views/components/notif-bell.blade.php --}}
<div class="notif-wrapper-sidebar" style="position:relative;">
    <button id="notif-toggle" class="sidebar-notif-btn">
        <i data-lucide="bell" class="w-5 h-5"></i>
        <span>Notifications</span>
        @php $nbNonLues = auth()->user()->unreadNotifications->count(); @endphp
        @if($nbNonLues > 0)
        <span class="notif-badge-count">{{ $nbNonLues > 9 ? '9+' : $nbNonLues }}</span>
        @endif
    </button>

    <div id="notif-panel" class="notif-panel-dropdown">
        <div style="padding:14px 16px;border-bottom:1px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;">
            <span style="font-weight:700;font-size:14px;">Notifications</span>
            @if($nbNonLues > 0)
            <form method="POST" action="{{ route('notifications.marquer-tout-lu') }}">
                @csrf
                <button type="submit" style="font-size:11px;color:#2563eb;background:none;border:none;cursor:pointer;">Tout marquer lu</button>
            </form>
            @endif
        </div>

        @forelse (auth()->user()->notifications()->latest()->take(10)->get() as $notif)
        <a href="{{ $notif->data['lien'] ?? '#' }}"
           onclick="event.preventDefault(); fetch('{{ route('notifications.lire', $notif->id) }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(()=>window.location.href='{{ $notif->data['lien'] ?? '#' }}');"
           style="display:flex;gap:10px;padding:12px 16px;border-bottom:1px solid #f9fafb;text-decoration:none;{{ $notif->read_at ? 'opacity:.6;' : 'background:#f8faff;' }}">
            <div style="width:32px;height:32px;border-radius:50%;background:#eff6ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i data-lucide="{{ $notif->data['icone'] ?? 'bell' }}" style="width:15px;height:15px;color:#2563eb;"></i>
            </div>
            <div style="flex:1;">
                <p style="font-size:12.5px;font-weight:600;color:#111;">{{ $notif->data['titre'] }}</p>
                <p style="font-size:11.5px;color:#6b7280;margin-top:2px;">{{ $notif->data['message'] }}</p>
                <p style="font-size:10px;color:#9ca3af;margin-top:2px;">{{ $notif->created_at->diffForHumans() }}</p>
            </div>
        </a>
        @empty
        <p style="text-align:center;color:#9ca3af;font-size:12px;padding:24px;">Aucune notification.</p>
        @endforelse
    </div>
</div>

@once
@push('scripts')
<script>
document.getElementById('notif-toggle')?.addEventListener('click', function (e) {
    e.stopPropagation();
    const panel = document.getElementById('notif-panel');
    panel.classList.toggle('is-open');
});
document.addEventListener('click', function (e) {
    const wrapper = document.querySelector('.notif-wrapper-sidebar');
    if (wrapper && !wrapper.contains(e.target)) {
        document.getElementById('notif-panel')?.classList.remove('is-open');
    }
});
</script>
@endpush
@endonce