@extends('layout.app')

@section('content')
<div class="container py-4">
    <div class="card p-4">
        <div class="row g-3">
            <div class="col-md-4">
                @if($produk->gambar)
                    <img src="{{ asset('storage/'.$produk->gambar) }}" class="img-fluid">
                @else
                    <div class="bg-light" style="height:250px;"></div>
                @endif
            </div>
            <div class="col-md-8">
                <h3>{{ $produk->nama }}</h3>
                <p class="text-muted">Kategori: {{ $produk->category->nama ?? '-' }}</p>
                <h5>Rp {{ number_format($produk->harga,0,',','.') }}</h5>
                <p>Stok: {{ $produk->stok }}</p>
                <p>{{ $produk->deskripsi }}</p>

                <a href="{{ route('penjual.produk.edit', $produk->id) }}" class="btn btn-primary">Edit</a>
                <a href="{{ route('penjual.produk.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
