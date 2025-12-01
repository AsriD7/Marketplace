@extends('layout.app')
@section('title', 'Dashboard')
@section('content')
<div>
    <!-- Waste no more time arguing what a good man should be, be one. - Marcus Aurelius -->
    <div class="container mt-5">
        <h1 class="text-center mb-4">Dashboard</h1>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm p-4">
                    <h2>Welcome, {{ auth()->user()->name }}!</h2>
                    <p>This is your dashboard where you can manage your account and view your data.</p>
                </div>
            </div>
        </div>
        <div class="container mt-5">
        <h2>Manajemen Pengguna</h2>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Form Tambah Pengguna -->
        <form method="POST" action="{{ route('admin.store') }}" class="mb-4">
            @csrf
            <div class="row">
                <div class="col-md-3 mb-3">
                    <input type="text" class="form-control" name="name" placeholder="Nama" required>
                </div>
                <div class="col-md-3 mb-3">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
                <div class="col-md-2 mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                <div class="col-md-2 mb-3">
                    <select name="role" class="form-control" required>
                        <option value="admin">Admin</option>
                        <option value="user">Penjual</option>
                        <option value="user">Pelanggan</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <button type="submit" class="btn btn-primary w-100">Tambah</button>
                </div>
            </div>
        </form>

        <!-- Daftar Pengguna -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <!-- Form Edit (Inline) -->
                            <form method="POST" action="{{ route('admin.update', $user->id) }}" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $user->name }}" style="width: 100px; display:inline;">
                                <input type="email" name="email" value="{{ $user->email }}" style="width: 150px; display:inline;">
                                <select name="role" style="width: 100px; display:inline;">
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Penjual</option>
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Pelanggan</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-warning">Update</button>
                            </form>
                            <!-- Form Hapus -->
                            <form action="{{ route('admin.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection