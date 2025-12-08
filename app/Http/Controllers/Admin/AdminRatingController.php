<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductRating;

class AdminRatingController extends Controller
{
    //
    public function index()
    {
        $ratings = ProductRating::with('user','product','order')->latest()->paginate(30);
        return view('admin.ratings.index', compact('ratings'));
    }

    public function destroy(ProductRating $rating)
    {
        // hapus rating (moderator)
        $rating->delete();
        return back()->with('success', 'Rating telah dihapus.');
    }

}
