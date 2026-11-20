@extends('layouts.layoutmaster')

@section('title', ($activeCategory ? $activeCategory->name : 'Tất cả sản phẩm').' | Luna Jewelry')

@section('content')
    <section class="bg-[#fcfaf7] py-10 lg:py-14">
        <div class="mx-auto max-w-7xl px-5 lg:px-8">
            <div class="flex flex-col gap-10 lg:flex-row lg:items-start lg:gap-14">
                <aside class="w-full shrink-0 lg:w-56" aria-label="Bộ lọc sản phẩm">
                    <div class="flex flex-col gap-7">
                        <div>
                            <h2 class="text-[11px] font-medium uppercase tracking-[0.18em] text-[#8b7355]">
                                Danh mục sản phẩm
                            </h2>
                            <ul class="mt-4 flex flex-col gap-2.5 text-sm">
                                <li>
                                    <a
                                        href="{{ route('products.index', array_filter(['price' => $selectedPrices, 'material' => $selectedMaterials, 'sort' => $sort !== 'default' ? $sort : null, 'q' => $search !== '' ? $search : null])) }}"
                                        class="flex items-center justify-between py-0.5 transition {{ $categorySlug === '' ? 'font-medium text-[#342821] underline decoration-[#a88350] underline-offset-4' : 'text-[#6a5c50] hover:text-[#342821]' }}"
                                        @if ($categorySlug === '') aria-current="page" @endif
                                    >
                                        <span>Tất cả</span>
                                    </a>
                                </li>
                                @foreach ($categories as $category)
                                    @php
                                        $isCurrentCategory = $activeCategory !== null && $activeCategory->id === $category->id;
                                    @endphp
                                    <li>
                                        <a
                                            href="{{ route('products.index', array_filter(['category' => $category->slug, 'price' => $selectedPrices, 'material' => $selectedMaterials, 'sort' => $sort !== 'default' ? $sort : null, 'q' => $search !== '' ? $search : null])) }}"
                                            class="flex items-center justify-between gap-2 py-0.5 transition {{ $isCurrentCategory ? 'font-medium text-[#342821] underline decoration-[#a88350] underline-offset-4' : 'text-[#6a5c50] hover:text-[#342821]' }}"
                                            @if ($isCurrentCategory) aria-current="page" @endif
                                        >
                                            <span>{{ $category->name }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <form method="GET" action="{{ route('products.index') }}" class="flex flex-col gap-7">
                            @if ($categorySlug !== '')
                                <input type="hidden" name="category" value="{{ $categorySlug }}">
                            @endif
                            @if ($sort !== 'default')
                                <input type="hidden" name="sort" value="{{ $sort }}">
                            @endif
                            @if ($search !== '')
                                <input type="hidden" name="q" value="{{ $search }}">
                            @endif

                            <div class="border-t border-[#e7ddd0] pt-6">
                                <h2 class="text-[11px] font-medium uppercase tracking-[0.18em] text-[#8b7355]">
                                    Khoảng giá
                                </h2>
                                <div class="mt-4 flex flex-col gap-3 text-sm text-[#5c4f45]">
                                    @foreach ($priceRanges as $rangeKey => $range)
                                        <label class="flex cursor-pointer items-center gap-2.5 transition hover:text-[#342821]">
                                            <input
                                                type="checkbox"
                                                name="price[]"
                                                value="{{ $rangeKey }}"
                                                @checked(in_array($rangeKey, $selectedPrices, true))
                                                onchange="this.form.submit()"
                                                class="h-3.5 w-3.5 rounded-none border-[#cbbba7] text-[#342821] focus:ring-[#a88350]"
                                            >
                                            <span>{{ $range['label'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            @if ($materials->isNotEmpty())
                                <div class="border-t border-[#e7ddd0] pt-6">
                                    <h2 class="text-[11px] font-medium uppercase tracking-[0.18em] text-[#8b7355]">
                                        Chất liệu
                                    </h2>
                                    <div class="mt-4 flex flex-col gap-3 text-sm text-[#5c4f45]">
                                        @foreach ($materials as $material)
                                            <label class="flex cursor-pointer items-center gap-2.5 transition hover:text-[#342821]">
                                                <input
                                                    type="checkbox"
                                                    name="material[]"
                                                    value="{{ $material }}"
                                                    @checked(in_array($material, $selectedMaterials, true))
                                                    onchange="this.form.submit()"
                                                    class="h-3.5 w-3.5 rounded-none border-[#cbbba7] text-[#342821] focus:ring-[#a88350]"
                                                >
                                                <span>{{ $material }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <noscript>
                                <button
                                    type="submit"
                                    class="w-full bg-[#342821] px-4 py-2.5 text-xs uppercase tracking-[0.14em] text-[#faf5ee] transition hover:bg-[#a88350]"
                                >
                                    Áp dụng bộ lọc
                                </button>
                            </noscript>
                        </form>

                        @if ($hasActiveFilters)
                            <div class="border-t border-[#e7ddd0] pt-5">
                                <a
                                    href="{{ route('products.index') }}"
                                    class="inline-flex items-center gap-1.5 text-xs tracking-wide text-[#9c733d] underline underline-offset-4 transition hover:text-[#342821]"
                                >
                                    Xóa tất cả bộ lọc
                                </a>
                            </div>
                        @endif
                    </div>
                </aside>

                <div class="min-w-0 flex-1">
                    <div class="flex flex-col gap-4 border-b border-[#e7ddd0] pb-5 sm:flex-row sm:items-end sm:justify-between">
                        <h1 class="font-storefront-heading text-3xl font-normal tracking-tight text-[#342821] sm:text-4xl">
                            {{ $activeCategory ? $activeCategory->name : 'Tất cả sản phẩm' }}
                        </h1>

                        <form method="GET" action="{{ route('products.index') }}" class="shrink-0">
                            @if ($categorySlug !== '')
                                <input type="hidden" name="category" value="{{ $categorySlug }}">
                            @endif
                            @foreach ($selectedPrices as $priceKey)
                                <input type="hidden" name="price[]" value="{{ $priceKey }}">
                            @endforeach
                            @foreach ($selectedMaterials as $materialItem)
                                <input type="hidden" name="material[]" value="{{ $materialItem }}">
                            @endforeach
                            @if ($search !== '')
                                <input type="hidden" name="q" value="{{ $search }}">
                            @endif

                            <label for="product-sort" class="sr-only">Sắp xếp sản phẩm</label>
                            <select
                                id="product-sort"
                                name="sort"
                                onchange="this.form.submit()"
                                class="border-0 border-b border-[#cbbba7] bg-transparent py-1.5 pl-0 pr-8 text-sm text-[#342821] focus:border-[#a88350] focus:ring-0"
                            >
                                @foreach ($sortOptions as $sortValue => $sortLabel)
                                    <option value="{{ $sortValue }}" @selected($sort === $sortValue)>
                                        Sắp xếp: {{ $sortLabel }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

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
                                    class="mt-6 inline-flex items-center bg-[#342821] px-6 py-3 text-xs uppercase tracking-[0.14em] text-[#faf5ee] transition hover:bg-[#a88350]"
                                >
                                    Xem tất cả sản phẩm
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="mt-8 grid grid-cols-1 gap-x-7 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
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
                                                class="h-full w-full object-cover transition duration-500 ease-out group-hover:scale-[1.04]"
                                                loading="lazy"
                                            >
                                        @else
                                            <div class="flex h-full w-full items-center justify-center text-[#c5b39c]" aria-hidden="true">
                                                <svg class="h-14 w-14" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.1">
                                                    <path d="M5 10h22l-11 17L5 10Zm0 0 5-6h12l5 6M10 4l6 23 6-23M5 10h22" />
                                                </svg>
                                            </div>
                                        @endif
                                    </a>

                                    <div class="flex flex-1 flex-col gap-2 pt-4">
                                        @if ($product->category)
                                            <p class="text-[11px] uppercase tracking-[0.14em] text-[#948374]">
                                                {{ $product->category->name }}
                                                @if ($product->material)
                                                    · {{ $product->material }}
                                                @endif
                                            </p>
                                        @endif

                                        <h2 class="line-clamp-2 font-storefront-heading text-lg leading-snug text-[#342821]">
                                            <a
                                                href="{{ route('products.show', [$product->category, $product]) }}"
                                                class="transition hover:text-[#a88350] focus-visible:outline-none focus-visible:underline"
                                            >
                                                {{ $product->name }}
                                            </a>
                                        </h2>

                                        <div class="flex flex-wrap items-baseline gap-2.5">
                                            <span class="text-sm font-medium tracking-wide text-[#9c733d]">
                                                {{ number_format($product->display_price, 0, ',', '.') }}đ
                                            </span>
                                            @if ($product->has_discount)
                                                <span class="text-xs text-[#a49587] line-through">
                                                    {{ number_format((float) $product->price, 0, ',', '.') }}đ
                                                </span>
                                            @endif
                                        </div>

                                        <div class="mt-auto pt-3">
                                            @if ($product->stock > 0)
                                                <div class="grid grid-cols-2 gap-2">
                                                    <button
                                                        type="button"
                                                        class="w-full border border-[#342821] bg-transparent px-2 py-2.5 text-center text-[11px] font-medium uppercase tracking-[0.08em] text-[#342821] transition hover:bg-[#342821] hover:text-[#faf5ee] focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-[#a88350]"
                                                    >
                                                        Thêm giỏ hàng
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="w-full bg-[#342821] px-2 py-2.5 text-center text-[11px] font-medium uppercase tracking-[0.08em] text-[#faf5ee] transition hover:bg-[#a88350] focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-[#a88350]"
                                                    >
                                                        Mua ngay
                                                    </button>
                                                </div>
                                            @else
                                                <button
                                                    type="button"
                                                    disabled
                                                    class="w-full cursor-not-allowed border border-[#dfd5c9] bg-transparent px-4 py-2.5 text-center text-[11px] font-medium uppercase tracking-[0.14em] text-[#9b8d82]"
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
                                    <a href="{{ $products->previousPageUrl() }}" rel="prev" class="inline-flex items-center border border-[#d6c7b8] bg-white px-4 py-2 text-[#342821] transition hover:bg-[#342821] hover:text-[#faf5ee]">
                                        &laquo; Trang trước
                                    </a>
                                @endif

                                @if ($products->hasMorePages())
                                    <a href="{{ $products->nextPageUrl() }}" rel="next" class="inline-flex items-center border border-[#d6c7b8] bg-white px-4 py-2 text-[#342821] transition hover:bg-[#342821] hover:text-[#faf5ee]">
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
        </div>
    </section>
@endsection
