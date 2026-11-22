<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'path', 'alt_text', 'title', 'is_primary', 'sort_order'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected function url(): Attribute
    {
        return Attribute::get(function (): ?string {
            if (! $this->path) {
                return null;
            }

            if (str_starts_with($this->path, 'http://') || str_starts_with($this->path, 'https://')) {
                return $this->path;
            }

            return asset('storage/'.ltrim($this->path, '/'));
        });
    }
}
