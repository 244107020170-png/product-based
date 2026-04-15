@extends('layouts.app')

@section('content')

<h1 class="text-center mb-4">🚀 Laravel + Bootstrap Berhasil!</h1>

<div class="row">
    @foreach(range(1,3) as $item)
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5>Lapangan {{ $item }}</h5>
                    <p>Contoh data dummy</p>
                    <button class="btn btn-primary">Booking</button>
                </div>
            </div>
        </div>
    @endforeach
</div>

@endsection