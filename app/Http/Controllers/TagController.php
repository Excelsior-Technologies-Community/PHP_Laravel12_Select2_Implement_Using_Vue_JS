<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    /**
     * Get tags (Supports Select2 Ajax pagination & search)
     */
    public function index(Request $request)
    {
        $query = Tag::query()->withCount('products');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->boolean('all', false)) {
            return response()->json($query->orderBy('name', 'asc')->get());
        }

        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 15);
        $tags = $query->orderBy('name', 'asc')->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'results' => $tags->items(),
            'pagination' => [
                'more' => $tags->hasMorePages()
            ],
            'total' => $tags->total()
        ]);
    }

    /**
     * Store a new tag on-the-fly
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:tags,name',
            'color' => 'nullable|string|max:20'
        ]);

        $tag = Tag::create([
            'name' => trim($request->name),
            'color' => $request->color
        ]);

        return response()->json($tag, 201);
    }

    /**
     * Tag Analytics for Charts & Radar
     */
    public function analytics()
    {
        $tags = Tag::withCount('products')
            ->orderBy('products_count', 'desc')
            ->get();

        $totalTags = $tags->count();
        $orphanCount = $tags->where('products_count', 0)->count();
        $usedCount = $totalTags - $orphanCount;

        return response()->json([
            'tags' => $tags,
            'summary' => [
                'total' => $totalTags,
                'used' => $usedCount,
                'orphans' => $orphanCount
            ]
        ]);
    }

    /**
     * Get Orphan (Unused) Tags
     */
    public function orphanTags()
    {
        $orphans = Tag::doesntHave('products')->get();
        return response()->json($orphans);
    }

    /**
     * Cleanup Orphan (Unused) Tags
     */
    public function cleanupOrphans()
    {
        $deletedCount = Tag::doesntHave('products')->delete();

        return response()->json([
            'message' => "Successfully cleaned up {$deletedCount} unused tags.",
            'deleted_count' => $deletedCount
        ]);
    }

    /**
     * Merge Source Tag into Target Tag
     */
    public function merge(Request $request)
    {
        $request->validate([
            'source_tag_id' => 'required|exists:tags,id',
            'target_tag_id' => 'required|exists:tags,id|different:source_tag_id',
        ]);

        $sourceTag = Tag::findOrFail($request->source_tag_id);
        $targetTag = Tag::findOrFail($request->target_tag_id);

        DB::transaction(function () use ($sourceTag, $targetTag) {
            // Find products attached to source tag
            $productIds = $sourceTag->products()->pluck('products.id')->toArray();

            foreach ($productIds as $productId) {
                // Attach to target tag if not already attached
                DB::table('product_tag')->updateOrInsert([
                    'product_id' => $productId,
                    'tag_id' => $targetTag->id,
                ]);
            }

            // Detach and delete source tag
            $sourceTag->products()->detach();
            $sourceTag->delete();
        });

        return response()->json([
            'message' => "Successfully merged tag '{$sourceTag->name}' into '{$targetTag->name}'.",
            'target_tag' => $targetTag->load('products')
        ]);
    }
}
