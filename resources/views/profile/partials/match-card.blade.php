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
</article>
