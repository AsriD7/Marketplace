<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Routing\Controller as Controller;
use App\Models\Payment;
use App\Models\ProductRating;
use App\Models\Category;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin'); // pastikan admin
    }

    /**
     * Dashboard admin (satu page berisi ringkasan:
     * - manajemen akun
     * - pembayaran pending
     * - rating & ulasan terbaru
     */
    public function index()
    {
        // --- ringkasan user ---
        $totalUser      = User::count();
        $totalPelanggan = User::where('role', 'pelanggan')->count();
        $totalPenjual   = User::where('role', 'penjual')->count();

        // --- ringkasan kategori ---
        $totalKategori       = Category::count();
        $kategoriTerbaru     = Category::latest()->take(5)->get();

        // --- pembayaran pending ---
        $pendingPayments = Payment::with('order.user', 'order.store')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // --- rating terbaru ---
        $latestRatings = ProductRating::with('user', 'product')
            ->latest()
            ->take(5)
            ->get();

        // --- tabel user untuk admin (opsional) ---
        $users = User::latest()->paginate(8);

        return view('admin.dashboard', compact(
            'totalUser',
            'totalPelanggan',
            'totalPenjual',

            'totalKategori',
            'kategoriTerbaru',

            'pendingPayments',
            'latestRatings',

            'users'
        ));
    }


    // ==== CRUD USER (bisa tetap di controller ini) ====

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:pelanggan,penjual',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => $request->role,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role'  => 'required|in:penjual,pelanggan',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect('/admin')
            ->with('success', 'Pengguna berhasil dihapus!');
    }
}

