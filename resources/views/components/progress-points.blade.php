@props(['user' => auth()->user()])

@php
    $currentPoints  = $user->points ?? 0;
    $nextTierTarget = $user->nextTierTarget();
    $pointsNeeded   = $user->pointsNeeded();
    $tierName       = $user->tierName();
    $tierColor      = $user->tierColor();
    $progress       = $user->progressPercentage();

    /* Indonesian tier names */
    $tierNameId = match($tierName) {
        'Champion' => 'Juara',
        'Master'   => 'Master',
        'Pro'      => 'Pro',
        default    => 'Pemula',
    };

    /* Tier definitions */
    $tiers = [
        [
            'name'    => 'Pemula',
            'range'   => '0–20 poin',
            'min'     => 0,
            'color'   => '#6b7280',
            'bg'      => '#f3f4f6',
            'icon'    => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 22V12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M12 12C12 12 7 11 5 7C3 3 7 2 9 3C11 4 12 7 12 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 12C12 12 17 10 18 6C19 2 15 2 13 3C11 4 12 7 12 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 22H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
        ],
        [
            'name'    => 'Pro',
            'range'   => '20–50 poin',
            'min'     => 20,
            'color'   => '#1d6fcf',
            'bg'      => '#eff6ff',
            'icon'    => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13 2L3 14H12L11 22L21 10H12L13 2Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"/></svg>',
        ],
        [
            'name'    => 'Master',
            'range'   => '50–100 poin',
            'min'     => 50,
            'color'   => '#7c3aed',
            'bg'      => '#f5f3ff',
            'icon'    => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L4 6V12C4 16.4 7.4 20.5 12 22C16.6 20.5 20 16.4 20 12V6L12 2Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        ],
        [
            'name'    => 'Juara',
            'range'   => '100+ poin',
            'min'     => 100,
            'color'   => '#d97706',
            'bg'      => '#fffbeb',
            'icon'    => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 21H16M12 17V21M7 4H17V11C17 14.314 14.761 17 12 17C9.239 17 7 14.314 7 11V4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 6H4C4 6 3 10 6 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M17 6H20C20 6 21 10 18 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        ],
    ];
@endphp

<div class="ppts-wrap">
    {{-- ── Header: poin + tier badge ── --}}
    <div class="ppts-header">
        <div class="ppts-points-block">
            <div class="ppts-circle" style="border-color:{{ $tierColor }};background:{{ $tierColor }}18;">
                <span class="ppts-circle__val" style="color:{{ $tierColor }};">{{ $currentPoints }}</span>
                <span class="ppts-circle__sub">poin</span>
            </div>
            <div>
                <p class="ppts-total-label">Total Poin</p>
                <p class="ppts-tier-name" style="color:{{ $tierColor }};">{{ $tierNameId }}</p>
            </div>
        </div>

        @php
            $tierIconMap = collect($tiers)->keyBy('name');
            $badgeIcon   = $tierIconMap->get($tierNameId, $tiers[0])['icon'];
        @endphp
        <div class="ppts-tier-badge" style="background:{{ $tierColor }}18;color:{{ $tierColor }};border-color:{{ $tierColor }}55;">
            <span class="ppts-tier-badge__icon">
                {!! $badgeIcon !!}
            </span>
            Tier {{ $tierNameId }}
        </div>
    </div>

    {{-- ── Progress bar ── --}}
    <div class="ppts-progress-section">
        <div class="ppts-progress-labels">
            <span>
                Butuh <strong style="color:{{ $tierColor }};">{{ $pointsNeeded }} poin</strong> lagi
            </span>
            <span>Target: <strong>{{ $nextTierTarget }} poin</strong></span>
        </div>
        <div class="ppts-bar-track">
            <div class="ppts-bar-fill"
                 style="width:{{ $progress }}%;background:linear-gradient(90deg,{{ $tierColor }},{{ $tierColor }}bb);">
            </div>
        </div>
        <p class="ppts-motivation">
            @if($pointsNeeded > 0)
                🔥 Semangat! Kamu butuh <strong>{{ $pointsNeeded }} poin</strong> lagi untuk naik ke tier berikutnya!
            @else
                🏆 Luar biasa! Kamu sudah berada di tier tertinggi!
            @endif
        </p>
    </div>

    {{-- ── 4 Tier cards ── --}}
    <div class="ppts-tiers">
        @foreach($tiers as $t)
            @php
                $isReached  = $currentPoints >= $t['min'];
                $isCurrent  = $tierNameId === $t['name'];
            @endphp
            <div class="ppts-tier-card {{ $isReached ? 'is-reached' : '' }} {{ $isCurrent ? 'is-current' : '' }}"
                 style="
                    --tc:{{ $t['color'] }};
                    background:{{ $isReached ? $t['color'].'22' : $t['bg'] }};
                    border-color:{{ $isCurrent ? $t['color'] : ($isReached ? $t['color'].'44' : 'rgba(0,0,0,.08)') }};
                 ">
                <div class="ppts-tier-card__icon"
                     style="color:{{ $isReached ? $t['color'] : '#9ca3af' }};">
                    {!! $t['icon'] !!}
                </div>
                <p class="ppts-tier-card__name" style="color:{{ $isReached ? $t['color'] : '#6b7280' }};">
                    {{ $t['name'] }}
                </p>
                <p class="ppts-tier-card__range">{{ $t['range'] }}</p>
                @if($isCurrent)
                    <span class="ppts-tier-card__current-dot" style="background:{{ $t['color'] }};"></span>
                @endif
            </div>
        @endforeach
    </div>
</div>

<style>
/* ── Wrapper ── */
.ppts-wrap {
    width: 100%;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px solid rgba(0,0,0,.08);
    border-radius: 20px;
    padding: 24px 28px;
    box-shadow: 0 4px 18px rgba(0,0,0,.06);
}

/* ── Header ── */
.ppts-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 22px;
    flex-wrap: wrap;
}

.ppts-points-block {
    display: flex;
    align-items: center;
    gap: 16px;
}

.ppts-circle {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 68px;
    height: 68px;
    border-radius: 50%;
    border: 2.5px solid;
    flex-shrink: 0;
}

.ppts-circle__val {
    font-size: 1.4rem;
    font-weight: 800;
    line-height: 1;
}

.ppts-circle__sub {
    font-size: 0.62rem;
    font-weight: 600;
    color: #6b7280;
    margin-top: 1px;
}

.ppts-total-label {
    margin: 0 0 3px;
    font-size: 0.78rem;
    font-weight: 600;
    color: #64748b;
}

.ppts-tier-name {
    margin: 0;
    font-size: 1.15rem;
    font-weight: 800;
}

.ppts-tier-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 16px;
    border-radius: 999px;
    border: 1.5px solid;
    font-size: 0.85rem;
    font-weight: 700;
    flex-shrink: 0;
}

.ppts-tier-badge__icon {
    display: inline-flex;
    align-items: center;
    width: 18px;
    height: 18px;
}

.ppts-tier-badge__icon svg {
    width: 18px;
    height: 18px;
}

/* ── Progress ── */
.ppts-progress-section {
    margin-bottom: 24px;
}

.ppts-progress-labels {
    display: flex;
    justify-content: space-between;
    font-size: 0.82rem;
    font-weight: 600;
    color: #475569;
    margin-bottom: 8px;
    flex-wrap: wrap;
    gap: 4px;
}

.ppts-bar-track {
    width: 100%;
    height: 12px;
    background: #e2e8f0;
    border-radius: 999px;
    overflow: hidden;
    box-shadow: inset 0 1px 3px rgba(0,0,0,.08);
    margin-bottom: 10px;
}

.ppts-bar-fill {
    height: 100%;
    border-radius: 999px;
    transition: width .6s cubic-bezier(.4,0,.2,1);
    min-width: 4px;
}

.ppts-motivation {
    margin: 0;
    font-size: 0.85rem;
    font-weight: 600;
    color: #475569;
    text-align: center;
}

/* ── 4 Tier Cards ── */
.ppts-tiers {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}

.ppts-tier-card {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    gap: 8px;
    padding: 18px 10px 14px;
    border-radius: 16px;
    border: 1.5px solid;
    cursor: default;
    transition: transform .2s ease, box-shadow .2s ease;
    min-height: 130px;
}

.ppts-tier-card.is-current {
    box-shadow: 0 4px 18px color-mix(in srgb, var(--tc) 30%, transparent);
    transform: translateY(-2px);
}

.ppts-tier-card__icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    flex-shrink: 0;
}

.ppts-tier-card__icon svg {
    width: 100%;
    height: 100%;
}

.ppts-tier-card__name {
    margin: 0;
    font-size: 0.82rem;
    font-weight: 800;
    text-align: center;
    line-height: 1.2;
}

.ppts-tier-card__range {
    margin: 0;
    font-size: 0.7rem;
    font-weight: 600;
    color: #94a3b8;
    text-align: center;
}

.ppts-tier-card__current-dot {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    animation: ppts-pulse 1.5s ease-in-out infinite;
}

@keyframes ppts-pulse {
    0%,100% { opacity: 1; transform: scale(1); }
    50%      { opacity: .6; transform: scale(1.35); }
}

/* ── Responsive ── */
@media (max-width: 640px) {
    .ppts-wrap { padding: 18px 16px; }

    .ppts-tiers {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .ppts-tier-card {
        min-height: 110px;
        padding: 14px 8px 12px;
    }

    .ppts-tier-card__icon {
        width: 38px;
        height: 38px;
    }

    .ppts-header { gap: 12px; }

    .ppts-circle {
        width: 58px;
        height: 58px;
    }

    .ppts-circle__val { font-size: 1.2rem; }
}

@media (max-width: 400px) {
    .ppts-tiers {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
