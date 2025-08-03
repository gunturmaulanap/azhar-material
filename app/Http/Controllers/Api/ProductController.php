<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Goods;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Goods::with(['category', 'brand']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id != 'all') {
            $query->where('category_id', $request->category_id);
        }

        // Filter by brand
        if ($request->has('brand_id') && $request->brand_id != 'all') {
            $query->where('brand_id', $request->brand_id);
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');

        switch ($sortBy) {
            case 'price-low':
                $query->orderBy('price', 'asc');
                break;
            case 'price-high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
            default:
                $query->orderBy('name', $sortOrder);
                break;
        }

        $products = $query->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function show($id)
    {
        $product = Goods::with(['category', 'brand'])->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    public function featured()
    {
        $featuredProducts = Goods::with(['category', 'brand'])
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $featuredProducts
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

    public function brands()
    {
        $brands = Brand::orderBy('name', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $brands
        ]);
    }
}
