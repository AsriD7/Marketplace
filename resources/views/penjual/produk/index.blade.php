@extends('layout.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Produk Saya</h3>
        <a href="{{ route('penjual.produk.create') }}" class="btn btn-primary">Tambah Produk</a>
    </div>
    

    @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif

    @if($products->isEmpty())
        <div class="card p-4">
            <p>Belum ada produk. Tambahkan produk pertamamu.</p>
            <a href="{{ route('penjual.produk.create') }}" class="btn btn-primary">Tambah Produk</a>
        </div>
    @else
        <div class="row g-3">
            @foreach($products as $product)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100">
                        @if($product->gambar)
                            <img src="{{ asset('storage/'.$product->gambar) }}" class="card-img-top" style="height:180px;object-fit:cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height:180px;">
                                <span class="text-muted">No image</span>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->nama }}</h5>
                            <p class="card-text text-muted mb-1">Rp {{ number_format($product->harga,0,',','.') }}</p>
                            <p class="card-text text-muted small mb-2">Stok: {{ $product->stok }}</p>
                            <div class="mt-auto d-flex gap-2">
                                <a href="{{ route('penjual.produk.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <a href="{{ route('penjual.produk.show', $product->id) }}" class="btn btn-sm btn-outline-secondary">Lihat</a>

                                <form action="{{ route('penjual.produk.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
