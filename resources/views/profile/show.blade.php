@extends('layout.app')

@section('content')
<div class="container py-4">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-4 text-center bg-light p-4">
                        @php
                            $avatar = optional($profile)->avatar;
                            $avatarUrl = $avatar ? asset('storage/'.$avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=0D6EFD&color=fff&size=128';
                        @endphp

                        <img src="{{ $avatarUrl }}" alt="Avatar" class="rounded-circle mb-3" style="width:140px;height:140px;object-fit:cover;border:5px solid #fff;box-shadow:0 4px 12px rgba(0,0,0,0.08)">

                        <h5 class="mt-2">{{ $user->name }}</h5>
                        <div class="text-muted small">{{ $user->email }}</div>

                        <div class="mt-3">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">Edit Profil</a>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="p-4">
                            <h5 class="mb-3">Informasi Kontak</h5>

                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Alamat</div>
                                <div class="col-sm-8">{{ $profile->alamat ?? '-' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Telepon</div>
                                <div class="col-sm-8">{{ $profile->telepon ?? '-' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted">Terdaftar</div>
                                <div class="col-sm-8">{{ $user->created_at->format('d M Y') }}</div>
                            </div>

                            <hr>

                            <div class="d-flex gap-2">
                                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm">Riwayat Pesanan</a>
                                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary btn-sm">Keranjang</a>
                                <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">Edit Profil</a>
                            </div>
                        </div> <!-- p-4 -->
                    </div> <!-- col -->
                </div> <!-- row g-0 -->
            </div> <!-- card -->
        </div>
    </div>
</div>
@endsection
