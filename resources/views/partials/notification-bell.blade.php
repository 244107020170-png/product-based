@php
    $unreadNotifs = auth()->user()->unreadNotifications;
    $unreadCount = $unreadNotifs->count();
    $latestNotifs = $unreadNotifs->take(5);
@endphp
<button type="button" class="player-dashboard-topbar__icon" id="notif-bell" style="position: relative;">
    <span class="player-inline-icon">
        <svg viewBox="0 0 24 24" fill="none"><path d="M9 18H15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M6.5 17.5H17.5L16.3 15.6C15.9 15 15.7 14.3 15.7 13.6V10.8C15.7 8.49 14.04 6.54 11.8 6.16V5.5C11.8 4.67 11.13 4 10.3 4C9.47 4 8.8 4.67 8.8 5.5V6.16C6.56 6.54 4.9 8.49 4.9 10.8V13.6C4.9 14.3 4.7 15 4.3 15.6L3.1 17.5H6.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
    </span>
    @if($unreadCount > 0)
        <span style="position: absolute; top: 2px; right: 2px; width: 10px; height: 10px; background: #ef4444; border: 2px solid #fff; border-radius: 50%;"></span>
    @endif
</button>
<div id="notif-dropdown" style="display: none; position: absolute; top: 100%; right: 0; z-index: 9999; width: 360px; max-width: calc(100vw - 24px); background: #fff; border-radius: 14px; box-shadow: 0 10px 40px rgba(0,0,0,.15); border: 1px solid rgba(0,0,77,.08); margin-top: 4px; overflow: hidden;">
    <div style="padding: 14px 18px; border-bottom: 1px solid rgba(0,0,77,.06); display: flex; justify-content: space-between; align-items: center;">
        <span style="font-weight: 800; color: #02025b; font-size: 15px;">Notifikasi</span>
        @if($unreadCount > 0)
            <span style="background: #3b82f6; color: #fff; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 999px;">{{ $unreadCount }} baru</span>
        @endif
    </div>
    <div style="max-height: 360px; overflow-y: auto;">
        @if($latestNotifs->isEmpty())
            <div style="padding: 32px 18px; text-align: center; color: #9ca3af; font-size: 13px;">Belum ada notifikasi</div>
        @else
            @foreach($latestNotifs as $notif)
                @php $d = $notif->data; @endphp
                <div style="padding: 12px 18px; border-bottom: 1px solid rgba(0,0,77,.04); background: #f0f7ff; display: flex; gap: 10px; align-items: flex-start;">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #3b82f6; flex-shrink: 0; margin-top: 6px;"></span>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 13px; color: #1f2937; line-height: 1.3;">
                            <strong>{{ $d['user_name'] ?? 'Pemain' }}</strong>
                            @if(($d['type'] ?? '') === 'payment_claimed')
                                mengklaim sudah bayar
                                <strong>{{ $d['match_title'] ?? '' }}</strong>
                            @else
                                {{ $d['message'] ?? '' }}
                            @endif
                        </div>
                        <div style="font-size: 11px; color: #9ca3af; margin-top: 2px;">{{ \Carbon\Carbon::parse($notif->created_at)->locale('id')->diffForHumans() }}</div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <a href="{{ route('notifications.index') }}" style="display: block; padding: 12px 18px; text-align: center; font-size: 13px; font-weight: 700; color: #3b82f6; text-decoration: none; border-top: 1px solid rgba(0,0,77,.06);">Lihat Semua</a>
</div>
<script>
(function() {
    var bell = document.getElementById('notif-bell');
    var dd = document.getElementById('notif-dropdown');

    function closeAllNotifDropdowns() {
        document.querySelectorAll('[id^="notif-dropdown"]').forEach(function(el) { el.style.display = 'none'; });
    }

    function closeNotifDropdownHandler(e) {
        closeAllNotifDropdowns();
        document.removeEventListener('click', closeNotifDropdownHandler);
    }

    function toggleNotifDropdown(e) {
        e.stopPropagation();
        var isOpen = dd.style.display === 'block';
        closeAllNotifDropdowns();
        if (!isOpen) {
            dd.style.display = 'block';
            setTimeout(function() { document.addEventListener('click', closeNotifDropdownHandler); }, 10);
        }
    }

    if (bell) {
        bell.addEventListener('click', toggleNotifDropdown);
    }
})();
</script>
