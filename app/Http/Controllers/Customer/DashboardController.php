<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    //
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Ringkasan keranjang
        $cart = \App\Models\Cart::where('user_id', $user->id)->with('items.product')->first();
        $cartCount = $cart ? $cart->items->sum('qty') : 0;
        $cartTotal = $cart ? $cart->items->sum(function($it){ return $it->qty * $it->harga; }) : 0;

        // 2. Recent orders (5 latest)
        $recentOrders = Order::where('user_id', $user->id)
            ->with('store','items.product','payment')
            ->latest()
            ->take(5)
            ->get();

        // 3. Quick actions: orders needing payment (pending upload or pending validation)
        $needPayment = Order::where('user_id', $user->id)
            ->whereIn('payment_status', ['pending','failed'])
            ->with('payment','store')
            ->orderBy('created_at','desc')
            ->get();

        // 4. Recommended products (simple: top selling or latest, exclude user's own products)
        $recommended = Product::where('is_active', true)
            ->whereHas('store') // ensure product has store
            ->withCount(['ratings as ratings_count'])
            ->withAvg('ratings','rating')
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        // 5. small stats
        $totalOrders = Order::where('user_id', $user->id)->count();
        $lastOrderAt = optional($recentOrders->first())->created_at;

        return view('pelanggan.dashboard', compact(
            'user','cart','cartCount','cartTotal',
            'recentOrders','needPayment','recommended',
            'totalOrders','lastOrderAt'
        ));
    }

}
