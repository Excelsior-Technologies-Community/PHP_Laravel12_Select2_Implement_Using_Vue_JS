<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display Products with Search & Sort
     */
    public function index(Request $request)
    {
        $query = Product::with('tags');

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sort
        switch ($request->sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;

            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;

            default:
                $query->oldest();
                break;
        }

        return response()->json($query->get());
    }

    /**
     * Store Product
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $product = Product::create([
            'sku' => $this->generateSku(),
            'name' => $request->name,
            'price' => $request->price,
        ]);

        if ($request->filled('tags')) {
            $product->tags()->attach($request->tags);
        }

        return response()->json(
            $product->load('tags'),
            201
        );
    }

    /**
     * Update Product
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        $product->tags()->sync($request->tags ?? []);

        return response()->json(
            $product->load('tags')
        );
    }

    /**
     * Delete Product
     */
    public function destroy(Product $product)
    {
        $product->tags()->detach();

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully.'
        ]);
    }

    /**
     * Dashboard Statistics
     */
    public function statistics()
    {
        $products = Product::all();

        return response()->json([

            'total_products' => Product::count(),

            'average_price' => round(
                Product::avg('price'),
                2
            ),

            'highest_price' => Product::max('price'),

            'lowest_price' => Product::min('price'),

            'total_value' => round(
                Product::sum('price'),
                2
            ),

            'latest_product' => Product::latest()->first(),

        ]);
    }

    private function generateSku()
    {
        $lastProduct = \App\Models\Product::orderBy('id', 'desc')->first();

        $nextId = $lastProduct ? $lastProduct->id + 1 : 1;

        return 'PRD-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }
}
