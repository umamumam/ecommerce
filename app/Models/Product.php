<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['category_id', 'name', 'slug', 'images', 'description', 'variant_1_name', 'variant_1_options', 'variant_2_name', 'variant_2_options', 'price', 'old_price', 'stock', 'weight', 'rating', 'sold_count', 'is_active'])]
class Product extends Model
{
    protected function casts(): array
    {
        return [
            'images' => 'array',
            'variant_1_options' => 'array',
            'variant_2_options' => 'array',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
