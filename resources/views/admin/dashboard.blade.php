@extends('layout.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-4">

    <h1 class="mb-4">Dashboard Admin</h1>

    {{-- Ringkasan --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card p-3">
                <div class="small text-muted">Total Pengguna</div>
                <div class="h4 mb-0">{{ $totalUser }}</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3">
                <div class="small text-muted">Pelanggan</div>
                <div class="h4 mb-0">{{ $totalPelanggan }}</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3">
                <div class="small text-muted">Penjual</div>
                <div class="h4 mb-0">{{ $totalPenjual }}</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3">
                <div class="small text-muted">Kategori Kuliner</div>
                <div class="h4 mb-0">{{ $totalKategori }}</div>
                <a href="{{ route('admin.categories.index') }}" class="small mt-2 d-inline-block">
                    Kelola kategori →
                </a>
            </div>
        </div>
    </div>

    {{-- Pembayaran pending --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Pembayaran Pending (5 terbaru)</span>
            <a href="{{ route('admin.payments.pending') }}" class="btn btn-sm btn-outline-primary">
                Lihat semua
            </a>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Pelanggan</th>
                        <th>Toko</th>
                        <th>Total</th>
                        <th>Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendingPayments as $payment)
                        <tr>
                            <td>#{{ $payment->order->id }}</td>
                            <td>{{ $payment->order->user->name ?? '-' }}</td>
                            <td>{{ $payment->order->store->nama_toko ?? '-' }}</td>
                            <td>Rp {{ number_format($payment->order->total, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('admin.payments.validate', $payment) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success">Validasi</button>
                                </form>

                                <form action="{{ route('admin.payments.reject', $payment) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-danger">Tolak</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Tidak ada pembayaran pending.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Rating & ulasan terbaru --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Rating & Ulasan Terbaru</span>
            <a href="{{ route('admin.ratings.index') }}" class="btn btn-sm btn-outline-primary">
                Kelola semua rating
            </a>
        </div>
        <div class="card-body">
            @forelse ($latestRatings as $rating)
                <div class="mb-3 border-bottom pb-2">
                    <strong>{{ $rating->product->nama ?? 'Produk' }}</strong>
                    <div class="small text-muted">
                        Oleh: {{ $rating->user->name ?? 'User' }} |
                        Rating: {{ $rating->rating }}/5
                    </div>
                    <div>{{ $rating->komentar }}</div>
                </div>
            @empty
                <p class="text-muted mb-0">Belum ada rating.</p>
            @endforelse
        </div>
    </div>

    {{-- Kategori terbaru --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Kategori Terbaru</span>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-primary">
                Kelola Semua
            </a>
        </div>

        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kategoriTerbaru as $cat)
                        <tr>
                            <td>{{ $cat->nama }}</td>
                            <td>{{ $cat->slug }}</td>
                            <td>
                                <a href="{{ route('admin.categories.edit', $cat->id) }}"
                                   class="btn btn-sm btn-outline-secondary">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                Belum ada kategori.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tambah Pengguna --}}
    <div class="card mb-4">
        <div class="card-header">
            Tambah Pengguna
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="pelanggan">Pelanggan</option>
                            <option value="penjual">Penjual</option>
                        </select>
                    </div>
                </div>

                <button class="btn btn-primary mt-3">
                    Tambah Pengguna
                </button>
            </form>
        </div>
    </div>

    {{-- Manajemen akun (tabel user singkat) --}}
    <div class="card">
        <div class="card-header">
            Manajemen Akun Pengguna
        </div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <td>
                                {{-- tombol edit & hapus, sesuaikan dengan form-mu --}}
                                <form action="{{ route('admin.users.destroy', $user->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus user ini?')"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Belum ada user.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $users->links() }}
        </div>
    </div>

</div>
@endsection
