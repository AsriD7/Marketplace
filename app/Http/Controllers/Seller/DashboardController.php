<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $store = $user->store;

        if (! $store) {
            return redirect()->route('penjual.store.edit')->with('error', 'Buat toko dulu untuk melihat dashboard.');
        }

        $storeId = $store->id;

        // periode default: 30 hari
        $days = (int) $request->query('days', 30);
        $since = Carbon::now()->subDays($days);

        // 1) Total omzet (sum order total) untuk store, periode X
        $totalOmzet = Order::where('store_id', $storeId)
            ->where('status', '!=', 'created') // exclude drafts if any
            ->where('created_at', '>=', $since)
            ->sum('total');

        // 2) Jumlah order (periode)
        $totalOrders = Order::where('store_id', $storeId)
            ->where('created_at', '>=', $since)
            ->count();

        // 3) Orders per status (counts)
        $ordersPerStatus = Order::where('store_id', $storeId)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // 4) Produk terlaris (top 5) berdasarkan qty sold (periode)
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(qty) as qty_sold'), DB::raw('SUM(qty*harga) as revenue'))
            ->whereHas('order', function ($q) use ($storeId, $since) {
                $q->where('store_id', $storeId)
                    ->where('created_at', '>=', $since);
            })
            ->groupBy('product_id')
            ->orderByDesc('qty_sold')
            ->with('product')
            ->take(5)
            ->get();

        // 5) Produk dengan stok rendah (threshold 5)
        $lowStockThreshold = 5;
        $lowStockProducts = Product::where('store_id', $storeId)
            ->where('stok', '<=', $lowStockThreshold)
            ->orderBy('stok', 'asc')
            ->take(10)
            ->get();

        // 6) Recent orders (latest 10)
        $recentOrders = Order::where('store_id', $storeId)
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        /**
         * Product Monitor (safe approach):
         * - Ambil produk dengan withCount & withAvg
         * - Ambil total_sold lewat agregasi OrderItem dan gabungkan ke collection
         * - Paginate hasil collection
         */
        // ambil semua product milik store (atau bisa diganti query paginate dulu)
        $products = Product::where('store_id', $storeId)
            ->withCount(['ratings as ratings_count'])
            ->withAvg('ratings', 'rating')
            ->get();

        // ambil total_sold per product (periode)
        $soldMap = OrderItem::select('product_id', DB::raw('SUM(qty) as total_sold'))
            ->whereHas('order', function ($q) use ($storeId, $since) {
                $q->where('store_id', $storeId)
                  ->where('created_at', '>=', $since);
            })
            ->groupBy('product_id')
            ->pluck('total_sold', 'product_id');

        // tambahkan atribut total_sold & normalize rating attributes
        $products->transform(function ($p) use ($soldMap, $lowStockThreshold) {
            $p->total_sold = isset($soldMap[$p->id]) ? (int) $soldMap[$p->id] : 0;
            $p->ratings_count = $p->ratings_count ?? 0;
            $p->ratings_avg = $p->ratings_avg ? round($p->ratings_avg, 1) : 0.0;
            return $p;
        });

        // paginate collection (12 per page)
        $page = (int) $request->query('page', 1);
        $perPage = 12;
        $itemsForCurrentPage = $products->slice(($page - 1) * $perPage, $perPage)->values();
        $productMonitor = new LengthAwarePaginator(
            $itemsForCurrentPage,
            $products->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        /**
         * Recent Reviews for this store (5 latest)
         */
        $recentReviews = \App\Models\ProductRating::with('user', 'product')
            ->whereHas('product', function ($q) use ($storeId) {
                $q->where('store_id', $storeId);
            })
            ->latest()
            ->take(5)
            ->get();

        // Alerts: ambil produk yang rating rata-rata <= 2 (safely via product avg)
        $lowRatedProducts = Product::where('store_id', $storeId)
            ->withAvg('ratings', 'rating')
            ->get()
            ->filter(function ($p) {
                return ($p->ratings_avg ?? 0) <= 2;
            });

        $outOfStockCount = Product::where('store_id', $storeId)->where('stok', '<=', 0)->count();

        // pass all data to view
        return view('penjual.dashboard.index', compact(
            'totalOmzet',
            'totalOrders',
            'ordersPerStatus',
            'topProducts',
            'lowStockProducts',
            'recentOrders',
            'productMonitor',
            'recentReviews',
            'lowStockThreshold',
            'days',
            'lowRatedProducts',
            'outOfStockCount'
        ));
    }

}
