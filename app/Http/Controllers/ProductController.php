<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        public ProductService $productService,
        public CategoryService $categoryService,
    ) {}

    public function index(FilterProductRequest $request): View
    {
        $filters = $request->filters();
        $categories = $this->categoryService->listActive();

        $activeCategory = $filters['category'] !== ''
            ? $categories->firstWhere('slug', $filters['category'])
            : null;

        $products = $this->productService->list($filters, $activeCategory, $categories);
        $materials = $this->productService->materials($categories);

        $hasActiveFilters = $filters['category'] !== ''
            || $filters['price'] !== []
            || $filters['material'] !== []
            || $filters['q'] !== '';

        return view('products.index', [
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'categorySlug' => $filters['category'],
            'products' => $products,
            'materials' => $materials,
            'priceRanges' => ProductService::PRICE_RANGES,
            'sortOptions' => ProductService::SORT_OPTIONS,
            'selectedPrices' => $filters['price'],
            'selectedMaterials' => $filters['material'],
            'search' => $filters['q'],
            'sort' => $filters['sort'],
            'hasActiveFilters' => $hasActiveFilters,
        ]);
    }

    public function show(Category $category, Product $product): View
    {
        $product = $this->productService->detail($category, $product);
        $relatedProducts = $this->productService->related($category, $product);

        return view('products.show', [
            'category' => $category,
            'product' => $product,
            'images' => $product->galleryUrls(),
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
