<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display Products with Search, Tag Filter & Sort
     */
    public function index(Request $request)
    {
        $query = Product::with('tags');

        // Search by name or SKU
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('sku', 'like', '%' . $search . '%');
            });
        }

        // Filter by Tag ID
        if ($request->filled('tag_id')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag_id);
            });
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
                $query->latest();
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
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'tags' => 'nullable|array',
        ]);

        $product = Product::create([
            'sku' => $this->generateSku(),
            'name' => $request->name,
            'price' => $request->price,
        ]);

        if ($request->filled('tags')) {
            $validTagIds = array_filter($request->tags, function ($id) {
                return is_numeric($id) && Tag::where('id', $id)->exists();
            });
            $product->tags()->attach($validTagIds);
        }

        return response()->json($product->load('tags'), 201);
    }

    /**
     * Update Product
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'tags' => 'nullable|array',
        ]);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        $validTagIds = [];
        if ($request->filled('tags')) {
            $validTagIds = array_filter($request->tags, function ($id) {
                return is_numeric($id) && Tag::where('id', $id)->exists();
            });
        }

        $product->tags()->sync($validTagIds);

        return response()->json($product->load('tags'));
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
     * Bulk Tag Assignment (Attach/Detach/Sync)
     */
    public function bulkTag(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
            'tag_ids' => 'required|array|min:1',
            'tag_ids.*' => 'exists:tags,id',
            'action' => 'required|in:attach,detach,sync',
        ]);

        $products = Product::whereIn('id', $request->product_ids)->get();

        foreach ($products as $product) {
            if ($request->action === 'attach') {
                $product->tags()->syncWithoutDetaching($request->tag_ids);
            } elseif ($request->action === 'detach') {
                $product->tags()->detach($request->tag_ids);
            } elseif ($request->action === 'sync') {
                $product->tags()->sync($request->tag_ids);
            }
        }

        return response()->json([
            'message' => "Bulk tags updated successfully for " . count($products) . " products."
        ]);
    }

    /**
     * Bulk Delete Products
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
        ]);

        $products = Product::whereIn('id', $request->product_ids)->get();

        foreach ($products as $product) {
            $product->tags()->detach();
            $product->delete();
        }

        return response()->json([
            'message' => count($products) . " products deleted successfully."
        ]);
    }

    /**
     * Dashboard Statistics
     */
    public function statistics()
    {
        return response()->json([
            'total_products' => Product::count(),
            'total_tags' => Tag::count(),
            'average_price' => round(Product::avg('price') ?? 0, 2),
            'highest_price' => Product::max('price') ?? 0,
            'lowest_price' => Product::min('price') ?? 0,
            'total_value' => round(Product::sum('price') ?? 0, 2),
            'latest_product' => Product::latest()->first(),
        ]);
    }

    /**
     * Generate Unique SKU
     */
    private function generateSku()
    {
        $maxId = Product::max('id') ?? 0;
        $nextId = $maxId + 1;

        do {
            $sku = 'PRD-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            $nextId++;
        } while (Product::where('sku', $sku)->exists());

        return $sku;
    }
}
