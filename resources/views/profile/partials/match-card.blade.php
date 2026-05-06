{{--
    Partial: profile/partials/match-card.blade.php
    Variabel: $match = [img, type, title, host, members, lokasi, waktu, tanggal]
              $scrollListId (opsional) – ID dari list container
--}}
<article class="profview-match-card">

    {{-- Gambar lapangan --}}
    <div class="profview-match-card__img-wrap">
        <img src="{{ $match['img'] }}"
             alt="{{ $match['title'] }}"
             class="profview-match-card__img"
             onerror="this.parentElement.innerHTML='<div class=\'profview-match-card__img-placeholder\'>Gambar Lapangan</div>'">
    </div>

    {{-- Isi --}}
    <div class="profview-match-card__body">
        <p class="profview-match-card__type">{{ $match['type'] }}</p>
        <h3 class="profview-match-card__title">{{ $match['title'] }}</h3>

        <dl class="profview-match-card__details">
            <dt>Tim Host</dt>
            <dd class="sep">:</dd>
            <dd>{{ $match['host'] }}</dd>

            <dt>Jumlah anggota</dt>
            <dd class="sep">:</dd>
            <dd>{{ $match['members'] }}</dd>

            <dt>Lokasi</dt>
            <dd class="sep">:</dd>
            <dd>{{ $match['lokasi'] }}</dd>

            <dt>Waktu</dt>
            <dd class="sep">:</dd>
            <dd>{{ $match['waktu'] }}</dd>

            <dt>Tanggal</dt>
            <dd class="sep">:</dd>
            <dd>{{ $match['tanggal'] }}</dd>
        </dl>
    </div>

    {{-- Scroll arrows --}}
    <div class="profview-match-card__arrows">
        <button type="button" class="profview-scroll-btn"
                data-scroll-dir="up"
                data-scroll-list="{{ $scrollListId ?? '' }}"
                aria-label="Scroll ke atas">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M6 15L12 9L18 15" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M6 20L12 14L18 20" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <button type="button" class="profview-scroll-btn"
                data-scroll-dir="down"
                data-scroll-list="{{ $scrollListId ?? '' }}"
                aria-label="Scroll ke bawah">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M6 4L12 10L18 4" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>

</article>
