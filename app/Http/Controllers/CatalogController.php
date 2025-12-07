<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class CatalogController extends Controller
{
    //
    /**
     * index: list produk, search + filter + per kategori
     * Query params:
     * - q (search)
     * - category (slug)
     * - price_min, price_max
     * - sort (price_asc, price_desc, newest)
     */
    public function index(Request $request, Category $category = null)
    {
        $q = $request->query('q');
        $categorySlug = $request->query('category') ?? ($category->slug ?? null);
        $priceMin = $request->query('price_min');
        $priceMax = $request->query('price_max');
        $sort = $request->query('sort');

        // base query: only active products and eager load category & store
        $query = Product::with(['category','store'])
                        ->where('is_active', true);

        // search by name or description
        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('deskripsi', 'like', "%{$q}%");
            });
        }

        // filter by category slug (if provided)
        if ($categorySlug) {
            $query->whereHas('category', function($qb) use ($categorySlug) {
                $qb->where('slug', $categorySlug);
            });
        }

        // price filter
        if (is_numeric($priceMin)) {
            $query->where('harga', '>=', (int)$priceMin);
        }
        if (is_numeric($priceMax)) {
            $query->where('harga', '<=', (int)$priceMax);
        }

        // sorting
        if ($sort === 'price_asc') $query->orderBy('harga', 'asc');
        elseif ($sort === 'price_desc') $query->orderBy('harga', 'desc');
        else $query->orderBy('created_at', 'desc'); // newest default

        $products = $query->paginate(12)->withQueryString();

        // categories for sidebar
        $categories = Category::orderBy('nama')->get();

        return view('catalog.index', compact('products','categories','q','categorySlug','priceMin','priceMax','sort'));
    }

    /**
     * show: detail produk by slug
     */
    public function show(Product $product)
    {
        // if not active hide
        if (! $product->is_active) abort(404);

        // eager load relations
        $product->load(['category','store','ratings']);

        // calculate average rating (or via accessor)
        $averageRating = round($product->ratings()->avg('rating'), 1);

        return view('catalog.show', compact('product','averageRating'));
    }

}
