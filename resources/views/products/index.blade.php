@extends('layouts.layoutmaster')

@section('title', ($activeCategory ? $activeCategory->name : 'Tất cả sản phẩm').' | Luna Jewelry')

@section('content')
    <section class="bg-[#fcfaf7] py-8 lg:py-12" x-data="catalogApp()">
        <div class="mx-auto max-w-7xl px-5 lg:px-8">
            <!-- Tiêu đề trang -->
            <div class="mb-4" id="page-heading-wrapper">
                <h1 class="font-storefront-heading text-3xl font-normal tracking-tight text-[#342821] sm:text-4xl" id="page-title">
                    {{ $activeCategory ? $activeCategory->name : 'Tất cả sản phẩm' }}
                </h1>
            </div>

            <!-- Hàng ngang Danh mục sản phẩm (chuyển từ cột trái lên) -->
            <div class="mb-6 flex flex-wrap items-center gap-x-6 gap-y-2 border-b border-[#e7ddd0] pb-3 text-sm" id="category-nav-bar">
                <span class="text-[11px] font-medium uppercase tracking-[0.18em] text-[#8b7355]">
                    Danh mục:
                </span>
                <a
                    href="{{ route('products.index', array_filter(['price' => $selectedPrices, 'material' => $selectedMaterials, 'sort' => $sort !== 'default' ? $sort : null, 'q' => $search !== '' ? $search : null])) }}"
                    @click.prevent="selectCategory($el.href)"
                    class="py-1 font-medium transition-colors {{ $categorySlug === '' ? 'text-[#342821] underline decoration-[#a88350] underline-offset-8' : 'text-[#6a5c50] hover:text-[#342821]' }}"
                    @if ($categorySlug === '') aria-current="page" @endif
                >
                    Tất cả
                </a>
                @foreach ($categories as $category)
                    @php
                        $isCurrentCategory = $activeCategory !== null && $activeCategory->id === $category->id;
                    @endphp
                    <a
                        href="{{ route('products.index', array_filter(['category' => $category->slug, 'price' => $selectedPrices, 'material' => $selectedMaterials, 'sort' => $sort !== 'default' ? $sort : null, 'q' => $search !== '' ? $search : null])) }}"
                        @click.prevent="selectCategory($el.href)"
                        class="py-1 font-medium transition-colors {{ $isCurrentCategory ? 'text-[#342821] underline decoration-[#a88350] underline-offset-8' : 'text-[#6a5c50] hover:text-[#342821]' }}"
                        @if ($isCurrentCategory) aria-current="page" @endif
                    >
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            <!-- Thanh bộ lọc ngang kiểu ảnh 2 -->
            <form
                method="GET"
                action="{{ route('products.index') }}"
                id="filter-form"
                @click.outside="openDropdown = null"
                class="mb-8"
            >
                <input type="hidden" name="category" id="filter-category-hidden" value="{{ $categorySlug }}">
                @if ($search !== '')
                    <input type="hidden" name="q" value="{{ $search }}">
                @endif

                <div class="flex flex-col gap-4 border-b border-[#e7ddd0] pb-4 md:flex-row md:items-center md:justify-between">
                    <!-- Bên trái: Bộ lọc + Các dropdown sổ xuống -->
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-3">
                        <span class="text-[11px] font-medium uppercase tracking-[0.18em] text-[#8b7355]">
                            Bộ lọc:
                        </span>

                        <!-- Dropdown Khoảng giá -->
                        <div class="relative">
                            <button
                                type="button"
                                @click="openDropdown = openDropdown === 'price' ? null : 'price'"
                                class="inline-flex items-center gap-1.5 text-sm font-medium transition-colors {{ count($selectedPrices) > 0 ? 'text-[#a88350]' : 'text-[#6a5c50] hover:text-[#342821]' }}"
                            >
                                <span>Khoảng giá</span>
                                <svg class="h-2 w-2 shrink-0 text-[#777] transition-transform duration-150" :class="openDropdown === 'price' ? 'rotate-180' : ''" viewBox="0 0 8 5" fill="currentColor">
                                    <path d="M0 0.5L4 4.5L8 0.5H0Z" />
                                </svg>
                            </button>

                            <div
                                x-show="openDropdown === 'price'"
                                x-cloak
                                x-transition:enter="transition-opacity ease-out duration-100"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="transition-opacity ease-in duration-75"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="absolute left-0 top-full z-30 mt-2 w-max min-w-[280px] rounded-md border border-[#e5e7eb] bg-white p-3.5 shadow-lg"
                            >
                                <div class="flex flex-col gap-2.5 text-sm text-[#4b5563]">
                                    @foreach ($priceRanges as $rangeKey => $range)
                                        <label class="flex cursor-pointer items-center gap-2.5 whitespace-nowrap hover:text-[#111]">
                                            <input
                                                type="checkbox"
                                                name="price[]"
                                                value="{{ $rangeKey }}"
                                                @checked(in_array($rangeKey, $selectedPrices, true))
                                                @change="updateFilters()"
                                                class="h-4 w-4 shrink-0 rounded border-gray-300 text-[#a88350] focus:ring-[#a88350]"
                                            >
                                            <span class="whitespace-nowrap text-sm">{{ $range['label'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Dropdown Chất liệu -->
                        @if ($materials->isNotEmpty())
                            <div class="relative">
                                <button
                                    type="button"
                                    @click="openDropdown = openDropdown === 'material' ? null : 'material'"
                                    class="inline-flex items-center gap-1.5 text-sm font-medium transition-colors {{ count($selectedMaterials) > 0 ? 'text-[#a88350]' : 'text-[#6a5c50] hover:text-[#342821]' }}"
                                >
                                    <span>Chất liệu</span>
                                    <svg class="h-2 w-2 shrink-0 text-[#777] transition-transform duration-150" :class="openDropdown === 'material' ? 'rotate-180' : ''" viewBox="0 0 8 5" fill="currentColor">
                                        <path d="M0 0.5L4 4.5L8 0.5H0Z" />
                                    </svg>
                                </button>

                                <div
                                    x-show="openDropdown === 'material'"
                                    x-cloak
                                    x-transition:enter="transition-opacity ease-out duration-100"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition-opacity ease-in duration-75"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    class="absolute left-0 top-full z-30 mt-2 w-max min-w-[200px] rounded-md border border-[#e5e7eb] bg-white p-3.5 shadow-lg"
                                >
                                    <div class="flex flex-col gap-2.5 text-sm text-[#4b5563]">
                                        @foreach ($materials as $material)
                                            <label class="flex cursor-pointer items-center gap-2.5 whitespace-nowrap hover:text-[#111]">
                                                <input
                                                    type="checkbox"
                                                    name="material[]"
                                                    value="{{ $material }}"
                                                    @checked(in_array($material, $selectedMaterials, true))
                                                    @change="updateFilters()"
                                                    class="h-4 w-4 shrink-0 rounded border-gray-300 text-[#a88350] focus:ring-[#a88350]"
                                                >
                                                <span class="whitespace-nowrap text-sm">{{ $material }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div id="clear-filter-wrapper">
                            @if ($hasActiveFilters)
                                <a
                                    href="{{ route('products.index', array_filter(['category' => $categorySlug !== '' ? $categorySlug : null])) }}"
                                    @click.prevent="selectCategory($el.href)"
                                    class="text-xs text-[#9c733d] underline underline-offset-4 transition hover:text-[#342821]"
                                >
                                    Xóa tất cả bộ lọc
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Bên phải: Sắp xếp -->
                    <div class="flex items-center gap-2.5">
                        <label for="product-sort" class="text-[11px] font-medium uppercase tracking-[0.18em] text-[#8b7355]">
                            Sắp xếp:
                        </label>
                        <select
                            id="product-sort"
                            name="sort"
                            @change="updateFilters()"
                            class="rounded border border-[#cbbba7] bg-white py-1.5 pl-3 pr-8 text-sm text-[#342821] focus:border-[#a88350] focus:outline-none focus:ring-1 focus:ring-[#a88350]"
                        >
                            @foreach ($sortOptions as $sortValue => $sortLabel)
                                <option value="{{ $sortValue }}" @selected($sort === $sortValue)>
                                    {{ $sortLabel }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            <!-- Khu vực danh sách sản phẩm & phân trang -->
            <div id="catalog-products-area">
                @if ($products->isEmpty())
                    <div class="mt-10 flex flex-col items-center justify-center border border-[#e7ddd0] bg-white px-6 py-16 text-center">
                        <p class="font-storefront-heading text-xl text-[#342821]">
                            Không tìm thấy sản phẩm phù hợp
                        </p>
                        <p class="mt-2 max-w-md text-sm text-[#7a6b5f]">
                            Thử thay đổi danh mục, khoảng giá hoặc chất liệu để khám phá thêm các thiết kế khác từ Luna Jewelry.
                        </p>
                        @if ($hasActiveFilters)
                            <a
                                href="{{ route('products.index') }}"
                                @click.prevent="selectCategory($el.href)"
                                class="mt-6 inline-flex items-center bg-[#342821] px-6 py-3 text-xs uppercase tracking-[0.14em] text-[#faf5ee] transition hover:bg-[#a88350]"
                            >
                                Xem tất cả sản phẩm
                            </a>
                        @endif
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-x-5 gap-y-8 md:grid-cols-3 lg:grid-cols-4">
                        @foreach ($products as $product)
                            <article class="group flex flex-col">
                                <a
                                    href="{{ route('products.show', [$product->category, $product]) }}"
                                    class="relative aspect-square w-full overflow-hidden bg-[#f4ece2] focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-[#a88350]"
                                    aria-label="Xem chi tiết {{ $product->name }}"
                                >
                                    @if ($product->thumbnail_url)
                                        <img
                                            src="{{ $product->thumbnail_url }}"
                                            alt="{{ $product->name }}"
                                            class="h-full w-full object-cover transition-transform duration-300 ease-out group-hover:scale-[1.03]"
                                        >
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-[#c5b39c]" aria-hidden="true">
                                            <svg class="h-10 w-10 sm:h-12 sm:w-12" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.1">
                                                <path d="M5 10h22l-11 17L5 10Zm0 0 5-6h12l5 6M10 4l6 23 6-23M5 10h22" />
                                            </svg>
                                        </div>
                                    @endif
                                </a>

                                <div class="flex flex-1 flex-col gap-1.5 pt-3">
                                    @if ($product->category)
                                        <p class="text-[10px] uppercase tracking-[0.14em] text-[#948374]">
                                            {{ $product->category->name }}
                                            @if ($product->material)
                                                · {{ $product->material }}
                                            @endif
                                        </p>
                                    @endif

                                    <h2 class="line-clamp-2 font-storefront-heading text-sm font-normal leading-snug text-[#342821] sm:text-base">
                                        <a
                                            href="{{ route('products.show', [$product->category, $product]) }}"
                                            class="transition hover:text-[#a88350] focus-visible:outline-none focus-visible:underline"
                                        >
                                            {{ $product->name }}
                                        </a>
                                    </h2>

                                    <div class="flex flex-wrap items-baseline gap-2">
                                        <span class="text-sm font-medium tracking-wide text-[#9c733d]">
                                            {{ number_format($product->display_price, 0, ',', '.') }}đ
                                        </span>
                                        @if ($product->has_discount)
                                            <span class="text-xs text-[#a49587] line-through">
                                                {{ number_format((float) $product->price, 0, ',', '.') }}đ
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-auto pt-2.5">
                                        @if ($product->stock > 0)
                                            <div class="grid grid-cols-2 gap-1.5">
                                                <button
                                                    type="button"
                                                    class="w-full border border-[#342821] bg-transparent px-1 py-2 text-center text-[10px] font-medium uppercase tracking-[0.06em] text-[#342821] transition hover:bg-[#342821] hover:text-[#faf5ee] focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-[#a88350]"
                                                >
                                                    Thêm giỏ hàng
                                                </button>
                                                <button
                                                    type="button"
                                                    class="w-full bg-[#342821] px-1 py-2 text-center text-[10px] font-medium uppercase tracking-[0.06em] text-[#faf5ee] transition hover:bg-[#a88350] focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-[#a88350]"
                                                >
                                                    Mua ngay
                                                </button>
                                            </div>
                                        @else
                                            <button
                                                type="button"
                                                disabled
                                                class="w-full cursor-not-allowed border border-[#dfd5c9] bg-transparent px-2 py-2 text-center text-[10px] font-medium uppercase tracking-[0.14em] text-[#9b8d82]"
                                            >
                                                Hết hàng
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    @if ($products->hasPages())
                        <nav role="navigation" aria-label="Phân trang" class="mt-10 flex items-center justify-between border-t border-[#e7ddd0] pt-6 text-sm">
                            @if ($products->onFirstPage())
                                <span class="inline-flex cursor-not-allowed items-center border border-[#e7ddd0] bg-[#faf8f5] px-4 py-2 text-[#a89b8f]">
                                    &laquo; Trang trước
                                </span>
                            @else
                                <a
                                    href="{{ $products->previousPageUrl() }}"
                                    @click.prevent="selectCategory($el.href)"
                                    rel="prev"
                                    class="inline-flex items-center border border-[#d6c7b8] bg-white px-4 py-2 text-[#342821] transition hover:bg-[#342821] hover:text-[#faf5ee]"
                                >
                                    &laquo; Trang trước
                                </a>
                            @endif

                            @if ($products->hasMorePages())
                                <a
                                    href="{{ $products->nextPageUrl() }}"
                                    @click.prevent="selectCategory($el.href)"
                                    rel="next"
                                    class="inline-flex items-center border border-[#d6c7b8] bg-white px-4 py-2 text-[#342821] transition hover:bg-[#342821] hover:text-[#faf5ee]"
                                >
                                    Trang sau &raquo;
                                </a>
                            @else
                                <span class="inline-flex cursor-not-allowed items-center border border-[#e7ddd0] bg-[#faf8f5] px-4 py-2 text-[#a89b8f]">
                                    Trang sau &raquo;
                                </span>
                            @endif
                        </nav>
                    @endif
                @endif
            </div>
        </div>
    </section>

    <script>
        function catalogApp() {
            return {
                openDropdown: null,
                loading: false,
                updateFilters() {
                    const form = document.getElementById('filter-form');
                    const formData = new FormData(form);
                    const params = new URLSearchParams(formData);
                    const url = `${form.action}?${params.toString()}`;
                    this.loadUrl(url);
                },
                selectCategory(url) {
                    this.loadUrl(url);
                },
                async loadUrl(url) {
                    this.loading = true;
                    try {
                        const res = await fetch(url, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        if (!res.ok) throw new Error('Network error');
                        const html = await res.text();
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        // Update product list
                        const newProducts = doc.getElementById('catalog-products-area');
                        const currentProducts = document.getElementById('catalog-products-area');
                        if (newProducts && currentProducts) {
                            currentProducts.innerHTML = newProducts.innerHTML;
                        }

                        // Update category nav
                        const newCatNav = doc.getElementById('category-nav-bar');
                        const currentCatNav = document.getElementById('category-nav-bar');
                        if (newCatNav && currentCatNav) {
                            currentCatNav.innerHTML = newCatNav.innerHTML;
                        }

                        // Update page heading
                        const newHeading = doc.getElementById('page-heading-wrapper');
                        const currentHeading = document.getElementById('page-heading-wrapper');
                        if (newHeading && currentHeading) {
                            currentHeading.innerHTML = newHeading.innerHTML;
                        }

                        // Update category input in form
                        const newCatInput = doc.getElementById('filter-category-hidden');
                        const currentCatInput = document.getElementById('filter-category-hidden');
                        if (newCatInput && currentCatInput) {
                            currentCatInput.value = newCatInput.value;
                        }

                        // Update clear filters link
                        const newClear = doc.getElementById('clear-filter-wrapper');
                        const currentClear = document.getElementById('clear-filter-wrapper');
                        if (newClear && currentClear) {
                            currentClear.innerHTML = newClear.innerHTML;
                        }

                        window.history.pushState({}, '', url);
                    } catch (e) {
                        window.location.href = url;
                    } finally {
                        this.loading = false;
                    }
                }
            };
        }
        window.addEventListener('popstate', () => window.location.reload());
    </script>
@endsection
