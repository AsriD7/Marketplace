@extends('layout.app')

@section('content')
<div class="container py-4">
    <h3>Detail Pesanan #{{ $order->order_number }}</h3>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> 

    @endif

    

    <div class="card mb-3 p-3">
        <p><strong>Toko:</strong> {{ $order->store->nama_toko ?? '-' }}</p>
        <p><strong>Total:</strong> Rp {{ number_format($order->total,0,',','.') }}</p>
        <p><strong>Status:</strong> {{ $order->status }} / {{ $order->payment_status }}</p>
    </div>

    {{-- List items --}}
    <div class="card mb-3 p-3">
        <h5>Items</h5>
        <table class="table mb-0">
            <thead><tr><th>Produk</th><th>Qty</th><th>Harga</th><th></th></tr></thead>
            <tbody>
                @foreach($order->items as $it)
                    <tr>
                        <td>
                            {{ $it->product->nama ?? 'Produk tidak ditemukan' }}
                            <div><small class="text-muted">{{ $it->product->category->nama ?? '' }}</small></div>
                        </td>
                        <td>{{ $it->qty }}</td>
                        <td>Rp {{ number_format($it->harga,0,',','.') }}</td>
                        <td>
                            @php
                            $already = in_array($it->product_id, $ratedProductIds ?? []);
                             @endphp

    {{-- Hanya bisa rating jika order selesai & produk belum pernah dinilai --}}
    @if(in_array($order->status, ['delivered','completed']))
        @if($already)
            <span class="badge bg-success">Sudah Dinilai</span>
        @else
            <form action="{{ route('orders.rate', $order->id) }}" method="POST" class="mt-1">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <input type="hidden" name="product_id" value="{{ $it->product_id }}">

                <div class="d-flex flex-column gap-1">
                    <select name="rating" class="form-select form-select-sm" required>
                        <option value="5">5 - Sangat Baik</option>
                        <option value="4">4 - Baik</option>
                        <option value="3">3 - Cukup</option>
                        <option value="2">2 - Kurang</option>
                        <option value="1">1 - Buruk</option>
                    </select>

                    <textarea name="komentar" class="form-control form-control-sm" rows="1"
                              placeholder="Tulis ulasan (opsional)"></textarea>

                    <button class="btn btn-sm btn-primary mt-1">Kirim</button>
                </div>
            </form>
        @endif
    @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

     {{-- upload bukti (hanya untuk pemilik & jika payment pending/failed) --}}
    @if(auth()->id() === $order->user_id && in_array($order->payment_status, ['pending','failed']))
        <div class="card mb-3 p-3">
            <h5>Unggah Bukti Pembayaran</h5>
            <form action="{{ route('orders.payment.upload', $order->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Metode Pembayaran</label>
                    <select name="metode" class="form-select" required>
                        <option value="transfer">Transfer Bank</option>
                        <option value="e-wallet">E-Wallet</option>
                        <option value="cod">COD (Cash on Delivery)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Bukti Pembayaran (image)</label>
                    <input type="file" name="bukti" class="form-control" accept="image/*" required>
                </div>

                <button class="btn btn-primary">Upload Bukti Pembayaran</button>
            </form>
        </div>
    @endif

    {{-- jika ada payment record tampil --}}
    @if($order->payment)
        <div class="card p-3 mb-3">
            <h6>Bukti Pembayaran</h6>
            <p>Status: <strong>{{ $order->payment->status }}</strong></p>
            <p>Metode: {{ $order->payment->metode }}</p>
            <p>Jumlah: Rp {{ number_format($order->payment->amount,0,',','.') }}</p>

            @if($order->payment->bukti)
                <div class="mt-2">
                    <img src="{{ asset('storage/'.$order->payment->bukti) }}" style="max-width:320px;" class="img-fluid">
                </div>
            @endif
        </div>
    @endif

    {{-- Jika ada item yang bisa dinilai, tampilkan form untuk rating multiple --}}
    @php
        $canRateAny = false;
        if(in_array($order->status, ['delivered','completed'])) {
            foreach($order->items as $it) {
                if(! in_array($it->product_id, $ratedProductIds ?? [])) {
                    $canRateAny = true; break;
                }
            }
        }
    @endphp

    @if($canRateAny)
        <div class="card p-3 mb-3">
            <h5>Berikan Ulasan untuk Produk yang Sudah Diterima</h5>
            <form action="{{ route('orders.rate', $order->id) }}" method="POST">
                @csrf

                @foreach($order->items as $it)
                    @php $already = in_array($it->product_id, $ratedProductIds ?? []); @endphp

                    <div class="mb-3">
                        <h6>{{ $it->product->nama ?? 'Produk' }} @if($already) <small class="text-success">(Sudah Dinilai)</small> @endif</h6>

                        @if(! $already)
                            <input type="hidden" name="ratings[{{ $it->id }}][product_id]" value="{{ $it->product_id }}">
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="form-label">Rating</label>
                                    <select name="ratings[{{ $it->id }}][rating]" class="form-select" required>
                                        <option value="5">5 - Sangat Baik</option>
                                        <option value="4">4 - Baik</option>
                                        <option value="3">3 - Cukup</option>
                                        <option value="2">2 - Kurang</option>
                                        <option value="1">1 - Buruk</option>
                                    </select>
                                </div>
                                <div class="col-md-10">
                                    <label class="form-label">Ulasan (opsional)</label>
                                    <textarea name="komentar[{{ $it->id }}][komentar]" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach

                <button class="btn btn-primary">Kirim Ulasan</button>
            </form>
        </div>
    @endif

    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Kembali ke Riwayat</a>
</div>
@endsection
