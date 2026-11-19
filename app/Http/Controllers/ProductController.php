<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    private const PRICE_RANGES = [
        'under-1m' => ['label' => 'Dưới 1.000.000đ', 'min' => null, 'max' => 1000000],
        '1m-3m' => ['label' => '1.000.000đ – 3.000.000đ', 'min' => 1000000, 'max' => 3000000],
        '3m-5m' => ['label' => '3.000.000đ – 5.000.000đ', 'min' => 3000000, 'max' => 5000000],
        'over-5m' => ['label' => 'Trên 5.000.000đ', 'min' => 5000000, 'max' => null],
    ];

    private const SORT_OPTIONS = [
        'default' => 'Mặc định',
        'price_asc' => 'Giá: Thấp đến cao',
        'price_desc' => 'Giá: Cao đến thấp',
    ];

    public function index(Request $request): View
    {
        $categories = Category::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'sort_order']);

        $categorySlug = trim((string) $request->query('category', ''));
        $activeCategory = $categorySlug !== ''
            ? $categories->firstWhere('slug', $categorySlug)
            : null;

        $selectedPrices = array_values(array_intersect(
            (array) $request->query('price', []),
            array_keys(self::PRICE_RANGES)
        ));

        $selectedMaterials = array_values(array_filter(
            array_map(static fn (mixed $value): string => trim((string) $value), (array) $request->query('material', [])),
            static fn (string $value): bool => $value !== ''
        ));

        $search = trim((string) $request->query('q', ''));
        $sort = (string) $request->query('sort', 'default');

        if (! array_key_exists($sort, self::SORT_OPTIONS)) {
            $sort = 'default';
        }

        $effectivePriceSql = 'CASE WHEN sale_price IS NOT NULL AND sale_price > 0 AND sale_price < price THEN sale_price ELSE price END';

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
            ->whereHas('category', function (Builder $query): void {
                $query->active();
            })
            ->with('category:id,name,slug');

        if ($categorySlug !== '') {
            if ($activeCategory !== null) {
                $productsQuery->where('category_id', $activeCategory->id);
            } else {
                $productsQuery->whereRaw('1 = 0');
            }
        }

        if ($selectedPrices !== []) {
            $productsQuery->where(function (Builder $query) use ($selectedPrices, $effectivePriceSql): void {
                foreach ($selectedPrices as $rangeKey) {
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

        if ($selectedMaterials !== []) {
            $productsQuery->whereIn('material', $selectedMaterials);
        }

        if ($search !== '') {
            $like = '%'.$search.'%';
            $productsQuery->where(function (Builder $query) use ($like): void {
                $query->where('name', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhere('material', 'like', $like);
            });
        }

        match ($sort) {
            'price_asc' => $productsQuery->orderByRaw("({$effectivePriceSql}) asc"),
            'price_desc' => $productsQuery->orderByRaw("({$effectivePriceSql}) desc"),
            default => $productsQuery->orderByDesc('is_featured')->latest(),
        };

        $products = $productsQuery->simplePaginate(3)->withQueryString();

        $materials = Product::query()
            ->active()
            ->whereNotNull('material')
            ->where('material', '!=', '')
            ->distinct()
            ->orderBy('material')
            ->pluck('material');

        $hasActiveFilters = $categorySlug !== ''
            || $selectedPrices !== []
            || $selectedMaterials !== []
            || $search !== '';

        return view('products.index', [
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'categorySlug' => $categorySlug,
            'products' => $products,
            'materials' => $materials,
            'priceRanges' => self::PRICE_RANGES,
            'sortOptions' => self::SORT_OPTIONS,
            'selectedPrices' => $selectedPrices,
            'selectedMaterials' => $selectedMaterials,
            'search' => $search,
            'sort' => $sort,
            'hasActiveFilters' => $hasActiveFilters,
        ]);
    }

    public function show(Category $category, Product $product): View
    {
        abort_unless($category->is_active && $product->is_active, 404);

        $product->load('images:id,product_id,path,alt_text,sort_order');

        $relatedProducts = Product::query()
            ->select(['id', 'category_id', 'name', 'slug', 'price', 'sale_price', 'stock', 'material', 'thumbnail'])
            ->active()
            ->where('category_id', $category->id)
            ->whereKeyNot($product->id)
            ->with('category:id,name,slug')
            ->latest()
            ->limit(3)
            ->get();

        return view('products.show', [
            'category' => $category,
            'product' => $product,
            'images' => $product->galleryUrls(),
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
