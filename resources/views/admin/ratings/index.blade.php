@extends('layout.app')

@section('content')
<div class="container py-4">
    <h3>Moderasi Ulasan</h3>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th><th>Produk</th><th>User</th><th>Rating</th><th>Ulasan</th><th>Tgl</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ratings as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->product->nama ?? '-' }}</td>
                <td>{{ $r->user->name ?? '-' }}</td>
                <td>{{ $r->rating }}</td>
                <td>{{ Str::limit($r->komentar, 120) }}</td>
                <td>{{ $r->created_at->format('d M Y') }}</td>
                <td>
                    <form action="{{ route('admin.ratings.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $ratings->links() }}
</div>
@endsection
