<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Goods;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // app/Http/Controllers/Api/ProductController.php

    public function index(Request $request)
    {
        $query = Goods::with(['category', 'brand']);

        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('brand_id') && $request->brand_id !== 'all') {
            $query->where('brand_id', $request->brand_id);
        }

        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');

        switch ($sortBy) {
            case 'price-low':
                $query->orderBy('price', 'asc');
                break;
            case 'price-high':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('name', $sortOrder);
        }

        $perPage = (int) $request->get('per_page', 8);

        $products = $perPage === -1 ? $query->get() : $query->paginate($perPage);

        $mapImage = function ($product) {
            $product->image_url = $product->image
                ? url(Storage::url($product->image))
                : url('images/no-image.svg');
            return $product;
        };

        if ($perPage === -1) {
            $products = $products->transform($mapImage);
        } else {
            $products->getCollection()->transform($mapImage);
        }

        return response()->json(['success' => true, 'data' => $products]);
    }

    public function show($id)
    {
        $product = Goods::with(['category', 'brand'])->find($id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $product->image_url = $product->image
            ? url(Storage::url($product->image))
            : url('images/no-image.svg');

        return response()->json(['success' => true, 'data' => $product]);
    }

    public function featured()
    {
        $featuredProducts = Goods::with(['category', 'brand'])
            ->inRandomOrder()
            ->limit(6)
            ->get()
            ->transform(function ($product) {
                $product->image_url = $product->image
                    ? url(Storage::url($product->image))
                    : url('images/no-image.svg');
                return $product;
            });

        return response()->json(['success' => true, 'data' => $featuredProducts]);
    }

    /**
     * Endpoint khusus buat ambil URL gambar produk.
     * Bisa dipakai front-end kalau cuma perlu URL doang.
     */
    public function image($id)
    {
        $product = Goods::find($id);

        if (!$product || !$product->image) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'image_url' => url(Storage::url($product->image)),
                'filename'  => $product->image,
            ],
        ]);
    }
    public function categories()
    {
        $categories = Category::orderBy('name', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}
