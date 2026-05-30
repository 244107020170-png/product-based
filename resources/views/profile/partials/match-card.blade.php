<article class="profview-match-card">
    <div class="profview-match-card__img-wrap">
        <img src="{{ $match['img'] }}"
             alt="{{ $match['title'] }}"
             class="profview-match-card__img"
             onerror="this.parentElement.innerHTML='<div class=\'profview-match-card__img-placeholder\'>Gambar Lapangan</div>'">
    </div>
    <div class="profview-match-card__body">
        <p class="profview-match-card__type">{{ $match['type'] }}</p>
        <h3 class="profview-match-card__title">{{ $match['title'] }}</h3>

        <dl class="profview-match-card__details">
            <dt>Nama Tim</dt>
            <dd class="sep">:</dd>
            <dd>{{ $match['team'] }}</dd>

            <dt>Olahraga</dt>
            <dd class="sep">:</dd>
            <dd>{{ $match['sport'] }}</dd>

            <dt>Lokasi</dt>
            <dd class="sep">:</dd>
            <dd>{{ $match['lokasi'] }}</dd>

            <dt>Waktu</dt>
            <dd class="sep">:</dd>
            <dd>{{ $match['waktu'] }}</dd>

            <dt>Tanggal</dt>
            <dd class="sep">:</dd>
            <dd>{{ $match['tanggal'] }}</dd>

            <dt>Status</dt>
            <dd class="sep">:</dd>
            <dd><span class="history-status {{ $match['statusClass'] }}">{{ $match['status'] }}</span></dd>
        </dl>
    </div>
</article>
