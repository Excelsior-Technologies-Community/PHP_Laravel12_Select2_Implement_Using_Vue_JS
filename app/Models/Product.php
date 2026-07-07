<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = ['sku', 'name', 'price'];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}