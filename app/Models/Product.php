<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'stock',
        'material',
        'thumbnail',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function canFulfill(int $quantity): bool
    {
        return $this->is_active && $this->category?->is_active && $quantity > 0 && $this->stock >= $quantity;
    }

    protected function thumbnailUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            if (! $this->thumbnail) {
                return null;
            }

            if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://')) {
                return $this->thumbnail;
            }

            return asset('storage/'.ltrim($this->thumbnail, '/'));
        });
    }

    protected function hasDiscount(): Attribute
    {
        return Attribute::get(function (): bool {
            return $this->sale_price !== null
                && (float) $this->sale_price > 0
                && (float) $this->sale_price < (float) $this->price;
        });
    }

    protected function displayPrice(): Attribute
    {
        return Attribute::get(function (): float {
            return $this->has_discount ? (float) $this->sale_price : (float) $this->price;
        });
    }

    /**
     * @return list<string>
     */
    public function galleryUrls(): array
    {
        $urls = [];

        if ($this->thumbnail_url) {
            $urls[] = $this->thumbnail_url;
        }

        foreach ($this->images as $image) {
            if ($image->url && ! in_array($image->url, $urls, true)) {
                $urls[] = $image->url;
            }
        }

        return $urls;
    }
}
