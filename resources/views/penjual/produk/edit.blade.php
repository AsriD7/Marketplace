@extends('layout.app')

@section('content')
<div class="container py-4">
    <h3>Edit Produk</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('penjual.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data" class="card p-4">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $produk->nama) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ (old('category_id', $produk->category_id) == $cat->id) ? 'selected':'' }}>{{ $cat->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
        </div>

        <div class="mb-3 row">
            <div class="col-md-6">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" value="{{ old('harga', $produk->harga) }}" min="0" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" value="{{ old('stok', $produk->stok) }}" min="0" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar Produk (opsional)</label>
            <input type="file" name="gambar" class="form-control" accept="image/*" onchange="previewImage(event)">
            @if($produk->gambar)
                <img id="preview" src="{{ asset('storage/'.$produk->gambar) }}" class="img-fluid mt-3" style="max-width:200px;">
            @else
                <img id="preview" class="img-fluid mt-3" style="max-width:200px; display:none;">
            @endif
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" {{ $produk->is_active ? 'checked':'' }}>
            <label for="is_active" class="form-check-label">Aktifkan produk</label>
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('penjual.produk.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
function previewImage(e) {
    const preview = document.getElementById('preview');
    const file = e.target.files[0];
    if (!file) { return; }
    preview.src = URL.createObjectURL(file);
    preview.style.display = 'block';
}
</script>
@endsection
