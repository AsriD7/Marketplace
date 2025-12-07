@extends('layout.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">Informasi Toko</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Jika store belum ada --}}
    @if(!$store)
        <div class="card p-4">
            <p class="mb-3">Anda belum memiliki toko. Buat toko terlebih dahulu.</p>
            <a href="{{ route('penjual.store.edit') }}" class="btn btn-primary">Buat Toko</a>
        </div>
    @else
        <div class="card p-4">
            <h4>{{ $store->nama_toko }}</h4>

            @if($store->gambar)
                <img src="{{ asset('storage/'.$store->gambar) }}"
                     alt="Foto Toko"
                     class="img-fluid mt-3 mb-3 rounded"
                     style="max-width: 250px;">
            @endif

            <p><strong>Deskripsi:</strong> {{ $store->deskripsi ?? '-' }}</p>
            <p><strong>Alamat Toko:</strong> {{ $store->alamat_toko ?? '-' }}</p>
            <p><strong>Jam Operasional:</strong> {{ $store->jam_operasional ?? '-' }}</p>

            <a href="{{ route('penjual.store.edit') }}" class="btn btn-primary mt-3">Edit Toko</a>
        </div>
    @endif

</div>
@endsection
