<?php

namespace App\Http\Controllers;

use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        return response()->json(
            \App\Models\Tag::orderBy('id', 'asc')->get()
        );
    }

    public function analytics()
    {
        $tags = \App\Models\Tag::withCount('products')->get();

        return response()->json($tags);
    }
}
