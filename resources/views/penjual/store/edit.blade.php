@extends('layout.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">Pengaturan Toko</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Perhatikan kesalahan berikut:</strong>
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('penjual.store.update') }}" method="POST" enctype="multipart/form-data" class="card p-4">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Toko</label>
            <input type="text" name="nama_toko" class="form-control"
                value="{{ old('nama_toko', $store->nama_toko ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi Toko</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $store->deskripsi ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat Toko</label>
            <input type="text" name="alamat_toko" class="form-control"
                value="{{ old('alamat_toko', $store->alamat_toko ?? '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Jam Operasional</label>
            <input type="text" name="jam_operasional" class="form-control"
                value="{{ old('jam_operasional', $store->jam_operasional ?? '') }}">
            <small class="text-muted">Contoh: 08:00 - 20:00</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Foto Toko</label>
            <input type="file" name="gambar" class="form-control">

            @if(isset($store) && $store->gambar)
                <img src="{{ asset('storage/'.$store->gambar) }}"
                     alt="Foto Toko"
                     class="img-fluid mt-3 rounded"
                     style="max-width: 200px;">
            @endif
        </div>

        <button type="submit" class="btn btn-success mt-3">Simpan</button>
        <a href="{{ route('penjual.store.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </form>

</div>
@endsection
