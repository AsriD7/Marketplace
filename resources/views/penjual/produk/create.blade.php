@extends('layout.app')

@section('content')
<div class="container py-4">
    <h3>Tambah Produk</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('penjual.produk.store') }}" method="POST" enctype="multipart/form-data" class="card p-4">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected':'' }}>{{ $cat->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="mb-3 row">
            <div class="col-md-6">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" value="{{ old('harga', 0) }}" min="0" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" value="{{ old('stok', 0) }}" min="0" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar Produk</label>
            <input type="file" name="gambar" class="form-control" accept="image/*" onchange="previewImage(event)">
            <img id="preview" class="img-fluid mt-3" style="max-width:200px; display:none;" />
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" checked>
            <label for="is_active" class="form-check-label">Aktifkan produk</label>
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('penjual.produk.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
function previewImage(e) {
    const preview = document.getElementById('preview');
    const file = e.target.files[0];
    if (!file) { preview.style.display = 'none'; return; }
    preview.src = URL.createObjectURL(file);
    preview.style.display = 'block';
}
</script>
@endsection
