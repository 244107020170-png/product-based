@php
    $unreadCount = auth()->user()->unreadNotifications()->count();
    $allNotifs = auth()->user()->notifications()->orderBy('created_at', 'desc')->take(10)->get();
    $hasUnread = $unreadCount > 0;
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
            <span data-ntf-badge="1" style="background: #3b82f6; color: #fff; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 999px;">{{ $unreadCount }} baru</span>
        @endif
    </div>
    <div id="notif-scroll" style="max-height: 360px; overflow-y: auto;">
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
                <div data-ntf-item="{{ $isUnread ? 'unread' : 'read' }}" style="padding: 12px 18px; border-bottom: 1px solid rgba(0,0,77,.04); {{ $isUnread ? 'background: #f0f7ff;' : '' }} display: flex; gap: 10px; align-items: flex-start;">
                    @if($isUnread)
                        <span data-ntf-dot="1" style="width: 8px; height: 8px; border-radius: 50%; background: #3b82f6; flex-shrink: 0; margin-top: 6px;"></span>
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
                            @elseif(($d['type'] ?? '') === 'community_joined')
                                <strong>{{ $d['user_name'] ?? 'Anggota baru' }}</strong> bergabung ke komunitas <strong>{{ $d['community_name'] ?? '' }}</strong>
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
    @if($allNotifs->isNotEmpty())
    <button type="button" id="notif-mark-read-btn" style="display: block; width: 100%; padding: 12px 18px; text-align: center; font-size: 13px; font-weight: 700; color: {{ $hasUnread ? '#3b82f6' : '#9ca3af' }}; border: none; background: none; cursor: {{ $hasUnread ? 'pointer' : 'default' }}; border-top: 1px solid rgba(0,0,77,.06); font-family: inherit;" {{ $hasUnread ? '' : 'disabled' }}>{{ $hasUnread ? 'Tandai Sudah Dibaca' : 'Sudah Dibaca' }}</button>
    @endif
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

    // Mark all notifications as read
    var markReadBtn = document.getElementById('notif-mark-read-btn');
    if (markReadBtn) {
        markReadBtn.addEventListener('click', function () {
            if (markReadBtn.disabled) return;
            markReadBtn.disabled = true;
            var csrf = document.querySelector('meta[name="csrf-token"]');
            if (!csrf) return;
            var formData = new FormData();
            formData.append('_token', csrf.getAttribute('content'));
            fetch('{{ route('notifications.markAllRead') }}', {
                method: 'POST',
                body: formData,
            }).then(function () {
                // Remove unread background from notification items
                dd.querySelectorAll('[data-ntf-item="unread"]').forEach(function (el) {
                    el.style.background = '';
                    el.setAttribute('data-ntf-item', 'read');
                });
                // Make blue dots transparent
                dd.querySelectorAll('[data-ntf-dot]').forEach(function (el) {
                    el.style.background = 'transparent';
                });
                // Remove bold from timestamps
                dd.querySelectorAll('[data-ntf-item] [style*="font-weight: 600;"]').forEach(function (el) {
                    el.style.fontWeight = '';
                });
                // Remove badge
                var badge = dd.querySelector('[data-ntf-badge]');
                if (badge) badge.remove();
                // Remove bell red dot indicator
                var bellDot = bell.querySelector('span:last-child');
                if (bellDot && bellDot.style.background === 'rgb(239, 68, 68)') bellDot.remove();
                // Update button state
                markReadBtn.textContent = 'Sudah Dibaca';
                markReadBtn.style.color = '#9ca3af';
                markReadBtn.style.cursor = 'default';
            }).catch(function () {
                markReadBtn.disabled = false;
            });
        });
    }
})();
</script>
<style>
@media (max-width: 640px) {
    #notif-dropdown {
        position: fixed !important;
        top: 60px !important;
        left: 12px !important;
        right: 12px !important;
        width: auto !important;
        max-width: none !important;
        z-index: 99999 !important;
    }
    #notif-scroll {
        max-height: calc(100vh - 170px) !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch;
    }
}
</style>
