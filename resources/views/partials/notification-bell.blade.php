@php
    $unreadCount = auth()->user()->unreadNotifications()->count();
    $allNotifs = auth()->user()->notifications()->orderBy('created_at', 'desc')->take(10)->get();
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
        @if($allNotifs->isEmpty())
            <div style="padding: 32px 18px; text-align: center; color: #9ca3af; font-size: 13px;">Belum ada notifikasi</div>
        @else
            @foreach($allNotifs as $notif)
                @php
                    $d = $notif->data;
                    $isUnread = is_null($notif->read_at);
                    $_bookingId = $d['booking_id'] ?? null;
                    $_notifType = $d['type'] ?? '';
                    $_mapsLink = $d['maps_link'] ?? null;
                    if (!$_mapsLink && $_bookingId && in_array($_notifType, ['booking_confirmed', 'booking_payment_received'])) {
                        $_mapsLink = optional(optional(\App\Models\Booking::find($_bookingId))->field)->maps_link;
                    }
                @endphp
                <div style="padding: 12px 18px; border-bottom: 1px solid rgba(0,0,77,.04); {{ $isUnread ? 'background: #f0f7ff;' : '' }} display: flex; gap: 10px; align-items: flex-start;">
                    @if($isUnread)
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #3b82f6; flex-shrink: 0; margin-top: 6px;"></span>
                    @else
                        <span style="width: 8px; height: 8px; flex-shrink: 0; margin-top: 6px;"></span>
                    @endif
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 13px; color: #1f2937; line-height: 1.3;">
                            @if(($d['type'] ?? '') === 'payment_claimed')
                                <strong>{{ $d['user_name'] ?? 'Pemain' }}</strong> mengklaim sudah bayar
                                <strong>{{ $d['match_title'] ?? '' }}</strong>
                            @elseif(($d['type'] ?? '') === 'payment_confirmed')
                                pembayaran untuk
                                <strong>{{ $d['match_title'] ?? '' }}</strong>
                                dikonfirmasi
                            @elseif(($d['type'] ?? '') === 'booking_payment_received')
                                Pembayaran untuk <strong>{{ $d['field_name'] ?? '' }}</strong> diterima, menunggu konfirmasi owner
                            @elseif(($d['type'] ?? '') === 'booking_confirmed')
                                Booking <strong>{{ $d['field_name'] ?? '' }}</strong> telah dikonfirmasi
                                @if(!empty($_mapsLink))
                                    &nbsp;<a href="{{ $_mapsLink }}" target="_blank" rel="noopener noreferrer" style="color:#3b82f6;font-weight:600;text-decoration:none;white-space:nowrap;" title="Buka Google Maps"><svg viewBox="0 0 24 24" fill="none" width="16" height="16" style="vertical-align:middle;"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="#3b82f6"/><circle cx="12" cy="9" r="2.5" fill="#fff"/></svg></a>
                                @endif
                            @else
                                {{ $d['message'] ?? '' }}
                            @endif
                        </div>
                        <div style="font-size: 11px; color: #9ca3af; margin-top: 2px;{{ $isUnread ? ' font-weight: 600;' : '' }}">{{ \Carbon\Carbon::parse($notif->created_at)->locale('id')->diffForHumans() }}</div>
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
