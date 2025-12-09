@extends('layout.app')
@section('content')
<div class="container py-4">
    <h3>Tambah Kategori</h3>

    @if($errors->any())
        <div class="alert alert-danger">{{ implode(', ', $errors->all()) }}</div>
    @endif

    <div class="card p-3">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @include('admin.categories._form')
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
