@extends('layout.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Dashboard Toko</h3>
        <form method="GET" class="d-flex align-items-center">
            <label class="me-2 small text-muted">Periode:</label>
            <select name="days" onchange="this.form.submit()" class="form-select form-select-sm" style="width:120px;">
                <option value="7" {{ $days==7?'selected':'' }}>7 hari</option>
                <option value="30" {{ $days==30?'selected':'' }}>30 hari</option>
                <option value="90" {{ $days==90?'selected':'' }}>90 hari</option>
                <option value="365" {{ $days==365?'selected':'' }}>1 tahun</option>
            </select>
        </form>
    </div>

    {{-- KPI Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card p-3">
                <div class="text-muted small">Omzet (Rp)</div>
                <div class="h4 mt-2">Rp {{ number_format($totalOmzet,0,',','.') }}</div>
                <div class="small text-muted">Periode: last {{ $days }} hari</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3">
                <div class="text-muted small">Jumlah Pesanan</div>
                <div class="h4 mt-2">{{ $totalOrders }}</div>
                <div class="small text-muted">Dalam {{ $days }} hari</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3">
                <div class="text-muted small">Produk Low-stock</div>
                <div class="h4 mt-2">{{ $lowStockProducts->count() }}</div>
                <div class="small text-muted">Threshold: {{ $lowStockThreshold }}</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3">
                <div class="text-muted small">Status Orders (count)</div>
                <div class="mt-2">
                    @foreach($ordersPerStatus as $status => $count)
                        <div class="small">{{ $status }}: <strong>{{ $count }}</strong></div>
                    @endforeach
                    @if(empty($ordersPerStatus))
                        <div class="small text-muted">No data</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Product Monitor --}}
<div class="card mb-4 p-3">
    <h5>Product Monitor</h5>
    <p class="text-muted small">Pantau stok, terjual, dan rating produk Anda. Klik Edit untuk perbaiki listing.</p>

    <div class="table-responsive">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Terjual ({{ $days }}d)</th>
                  
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($productMonitor as $p)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($p->gambar)
                                    <img src="{{ asset('storage/'.$p->gambar) }}" style="width:56px;height:56px;object-fit:cover" class="me-2 rounded">
                                @endif
                                <div>
                                    <div class="fw-bold">{{ $p->nama }}</div>
                                    <div class="small text-muted">{{ Str::limit($p->deskripsi,60) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($p->stok <= 0)
                                <span class="badge bg-danger">Out</span>
                            @elseif($p->stok <= $lowStockThreshold)
                                <span class="badge bg-warning">{{ $p->stok }}</span>
                            @else
                                <span>{{ $p->stok }}</span>
                            @endif
                        </td>
                        <td>Rp {{ number_format($p->harga,0,',','.') }}</td>
                        <td>{{ $p->total_sold ?? 0 }}</td>
                        
                        <td>
                            <a href="{{ route('penjual.produk.edit', $p->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $productMonitor->links() }}
    </div>
</div>

{{-- Recent Reviews --}}
<div class="card mb-4 p-3">
    <h5>Ulasan Terbaru</h5>
    @if($recentReviews->isEmpty())
        <p class="text-muted">Belum ada ulasan.</p>
    @else
        @foreach($recentReviews as $r)
            <div class="border p-2 mb-2">
                <div class="d-flex justify-content-between">
                    <div>
                        <strong>{{ $r->product->nama ?? 'Produk' }}</strong> — <small class="text-muted">{{ $r->user->name ?? 'User' }}</small>
                        <div class="small text-muted">{{ $r->created_at->format('d M Y') }}</div>
                    </div>
                    <div class="text-end">
                        <div class="text-warning">{{ $r->rating }}/5</div>
                        <a href="{{ route('penjual.produk.edit', $r->product_id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    </div>
                </div>
                @if($r->review)
                    <p class="mb-0 mt-2">{{ Str::limit($r->review, 200) }}</p>
                @endif
            </div>
        @endforeach
    @endif
</div>

{{-- Alerts --}}
@if($outOfStockCount > 0 || $lowRatedProducts->count() > 0)
    <div class="card mb-4 p-3">
        <h5>Peringatan</h5>
        <ul class="mb-0">
            @if($outOfStockCount > 0)
                <li><strong>{{ $outOfStockCount }}</strong> produk sedang <span class="text-danger">Out of Stock</span>.</li>
            @endif
            @if($lowRatedProducts->count() > 0)
                <li><strong>{{ $lowRatedProducts->count() }}</strong> produk memiliki rating rendah (≤ 2). Periksa ulasan dan perbaiki listing/produk.</li>
            @endif
        </ul>
    </div>
@endif


    {{-- Top Products & Low Stock --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card p-3">
                <h5>Produk Terlaris (Top 5)</h5>
                @if($topProducts->isEmpty())
                    <p class="text-muted">Belum ada penjualan pada periode ini.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($topProducts as $tp)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div><strong>{{ $tp->product->nama ?? 'Produk' }}</strong></div>
                                    <div class="small text-muted">Terjual: {{ $tp->qty_sold }} — Pendapatan: Rp {{ number_format($tp->revenue,0,',','.') }}</div>
                                </div>
                                <div class="text-end">
                                    <a href="{{ route('penjual.produk.edit', $tp->product_id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-3">
                <h5>Produk Stok Rendah</h5>
                @if($lowStockProducts->isEmpty())
                    <p class="text-muted">Semua stok sehat.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($lowStockProducts as $p)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div><strong>{{ $p->nama }}</strong></div>
                                    <div class="small text-muted">Stok: {{ $p->stok }}</div>
                                </div>
                                <div>
                                    <a href="{{ route('penjual.produk.edit', $p->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="card p-3">
        <h5>Pesanan Terbaru</h5>
        @if($recentOrders->isEmpty())
            <p class="text-muted">Belum ada pesanan.</p>
        @else
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tgl</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $o)
                            <tr>
                                <td>{{ $o->order_number }}</td>
                                <td>{{ $o->user->name ?? '-' }}</td>
                                <td>Rp {{ number_format($o->total,0,',','.') }}</td>
                                <td>{{ $o->status }}</td>
                                <td>{{ $o->created_at->format('d M Y H:i') }}</td>
                                <td><a href="{{ route('penjual.orders.show', $o->id) }}" class="btn btn-sm btn-outline-secondary">Detail</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
