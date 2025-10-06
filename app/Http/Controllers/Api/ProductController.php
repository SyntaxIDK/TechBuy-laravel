<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->category, function ($query, $category) {
                return $query->whereHas('category', function ($q) use ($category) {
                    $q->where('slug', $category);
                });
            })
            ->when($request->sort_by, function ($query, $sortBy) use ($request) {
                $direction = $request->sort_direction === 'desc' ? 'desc' : 'asc';
                return $query->orderBy($sortBy, $direction);
            }, function ($query) {
                return $query->latest();
            })
            ->paginate($request->per_page ?? 15);

        return new ProductCollection($products);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if (!$product->is_active) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->load('category');

        return new ProductResource($product);
    }

    /**
     * Get featured products (products on sale)
     */
    public function featured(Request $request)
    {
        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->whereNotNull('sale_price')
            ->where('sale_price', '<', DB::raw('price'))
            ->orderByRaw('(price - sale_price) / price DESC')
            ->limit($request->limit ?? 10)
            ->get();

        return ProductResource::collection($products);
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return response()->json([
                'data' => [],
                'message' => 'Search query is required'
            ], 400);
        }

        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('short_description', 'like', "%{$query}%")
                    ->orWhere('brand', 'like', "%{$query}%")
                    ->orWhere('model', 'like', "%{$query}%");
            })
            ->paginate($request->per_page ?? 15);

        return new ProductCollection($products);
    }

    /**
     * Get products by category
     */
    public function byCategory(Request $request, $categorySlug)
    {
        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug)->where('is_active', true);
            })
            ->when($request->sort_by, function ($query, $sortBy) use ($request) {
                $direction = $request->sort_direction === 'desc' ? 'desc' : 'asc';
                return $query->orderBy($sortBy, $direction);
            }, function ($query) {
                return $query->latest();
            })
            ->paginate($request->per_page ?? 15);

        return new ProductCollection($products);
    }
}
