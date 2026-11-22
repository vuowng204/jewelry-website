<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class ProductService
{
    public const PRICE_RANGES = [
        'under-1m' => ['label' => 'Dưới 1.000.000đ', 'min' => null, 'max' => 1000000],
        '1m-3m' => ['label' => '1.000.000đ – 3.000.000đ', 'min' => 1000000, 'max' => 3000000],
        '3m-5m' => ['label' => '3.000.000đ – 5.000.000đ', 'min' => 3000000, 'max' => 5000000],
        'over-5m' => ['label' => 'Trên 5.000.000đ', 'min' => 5000000, 'max' => null],
    ];

    public const SORT_OPTIONS = [
        'default' => 'Mặc định',
        'price_asc' => 'Giá: Thấp đến cao',
        'price_desc' => 'Giá: Cao đến thấp',
    ];

    private const EFFECTIVE_PRICE_SQL = 'CASE WHEN sale_price IS NOT NULL AND sale_price > 0 AND sale_price < price THEN sale_price ELSE price END';

    public function list(
        array $filters,
        ?Category $activeCategory,
        EloquentCollection $activeCategories,
        int $perPage = 8,
    ): Paginator {
        $effectivePriceSql = self::EFFECTIVE_PRICE_SQL;

        $productsQuery = Product::query()
            ->select([
                'id',
                'category_id',
                'name',
                'slug',
                'price',
                'sale_price',
                'stock',
                'material',
                'thumbnail',
                'is_featured',
                'created_at',
            ])
            ->active()
            ->whereIn('category_id', $activeCategories->pluck('id'))
            ->with('category:id,name,slug');

        if ($filters['category'] !== '') {
            if ($activeCategory !== null) {
                $productsQuery->where('category_id', $activeCategory->id);
            } else {
                $productsQuery->whereRaw('1 = 0');
            }
        }

        if ($filters['price'] !== []) {
            $productsQuery->where(function (Builder $query) use ($filters, $effectivePriceSql): void {
                foreach ($filters['price'] as $rangeKey) {
                    $range = self::PRICE_RANGES[$rangeKey];

                    $query->orWhere(function (Builder $subQuery) use ($range, $effectivePriceSql): void {
                        if ($range['min'] !== null && $range['max'] !== null) {
                            $subQuery->whereRaw("({$effectivePriceSql}) >= ? AND ({$effectivePriceSql}) <= ?", [$range['min'], $range['max']]);
                        } elseif ($range['max'] !== null) {
                            $subQuery->whereRaw("({$effectivePriceSql}) < ?", [$range['max']]);
                        } elseif ($range['min'] !== null) {
                            $subQuery->whereRaw("({$effectivePriceSql}) > ?", [$range['min']]);
                        }
                    });
                }
            });
        }

        if ($filters['material'] !== []) {
            $productsQuery->whereIn('material', $filters['material']);
        }

        if ($filters['q'] !== '') {
            $like = '%'.$filters['q'].'%';
            $productsQuery->where(function (Builder $query) use ($like): void {
                $query->where('name', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhere('material', 'like', $like);
            });
        }

        match ($filters['sort']) {
            'price_asc' => $productsQuery->orderByRaw("({$effectivePriceSql}) asc"),
            'price_desc' => $productsQuery->orderByRaw("({$effectivePriceSql}) desc"),
            default => $productsQuery->orderByDesc('is_featured')->latest(),
        };

        return $productsQuery->simplePaginate($perPage)->withQueryString();
    }

    public function detail(Category $category, Product $product): Product
    {
        abort_unless($category->is_active && $product->is_active, 404);

        $product->load('images:id,product_id,path,alt_text,sort_order');

        return $product;
    }

    public function related(Category $category, Product $product, int $limit = 4): EloquentCollection
    {
        return Product::query()
            ->select(['id', 'category_id', 'name', 'slug', 'price', 'sale_price', 'stock', 'material', 'thumbnail'])
            ->active()
            ->where('category_id', $category->id)
            ->whereKeyNot($product->id)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function featured(int $limit = 8): EloquentCollection
    {
        return Product::query()
            ->select(['id', 'category_id', 'name', 'slug', 'price', 'sale_price', 'stock', 'material', 'thumbnail'])
            ->with(['category:id,name,slug'])
            ->active()
            ->where('is_featured', true)
            ->whereHas('category', fn (Builder $query): Builder => $query->active())
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function materials(EloquentCollection $activeCategories): Collection
    {
        return Product::query()
            ->active()
            ->whereIn('category_id', $activeCategories->pluck('id'))
            ->whereNotNull('material')
            ->where('material', '!=', '')
            ->distinct()
            ->orderBy('material')
            ->pluck('material');
    }
}
