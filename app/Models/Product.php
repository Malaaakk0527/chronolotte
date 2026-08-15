<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'price', 'old_price',
        'stock', 'gender', 'image', 'hover_image', 'active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'old_price' => 'float',
            'active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function discountPercent(): ?int
    {
        if ($this->old_price > 0) {
            return (int) round((1 - $this->price / $this->old_price) * 100);
        }
        return null;
    }

    public function inStock(): bool
    {
        return $this->stock > 0;
    }

    public static function slugify(string $name): string
    {
        return Str::slug($name);
    }
}
