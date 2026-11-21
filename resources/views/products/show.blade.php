@extends('layouts.layoutmaster')

@section('title', $product->name.' | Luna Jewelry')

@section('content')
    <section class="bg-[#fcfaf7] py-10 lg:py-14">
        <div class="mx-auto max-w-6xl px-5 lg:px-8">
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-[minmax(0,476px)_1fr] lg:gap-12">
                <div
                    class="flex flex-col gap-3"
                    x-data="{
                        images: @js($images),
                        currentIndex: 0,
                        prev() {
                            if (this.images.length <= 1) return;
                            this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                        },
                        next() {
                            if (this.images.length <= 1) return;
                            this.currentIndex = (this.currentIndex + 1) % this.images.length;
                        }
                    }"
                    @keydown.arrow-left.window="prev()"
                    @keydown.arrow-right.window="next()"
                >
                    <div class="relative aspect-square w-full overflow-hidden bg-[#f4ece2]">
                        @if (count($images) > 0)
                            <img
                                :src="images[currentIndex]"
                                src="{{ $images[0] }}"
                                alt="{{ $product->name }}"
                                class="h-full w-full object-cover"
                            >
                        @else
                            <div class="flex h-full w-full items-center justify-center text-[#c5b39c]" aria-hidden="true">
                                <svg class="h-16 w-16" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.1">
                                    <path d="M5 10h22l-11 17L5 10Zm0 0 5-6h12l5 6M10 4l6 23 6-23M5 10h22" />
                                </svg>
                            </div>
                        @endif

                        @if (count($images) > 1)
                            <button
                                type="button"
                                @click="prev()"
                                class="absolute left-3 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center bg-white/85 text-[#342821] transition hover:bg-[#342821] hover:text-white focus-visible:outline focus-visible:outline-1 focus-visible:outline-[#a88350]"
                                aria-label="Ảnh trước"
                            >
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m15 18-6-6 6-6" />
                                </svg>
                            </button>

                            <button
                                type="button"
                                @click="next()"
                                class="absolute right-3 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center bg-white/85 text-[#342821] transition hover:bg-[#342821] hover:text-white focus-visible:outline focus-visible:outline-1 focus-visible:outline-[#a88350]"
                                aria-label="Ảnh tiếp theo"
                            >
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </button>

                            <span
                                class="absolute bottom-3 right-3 bg-[#342821]/80 px-2.5 py-1 text-xs tracking-widest text-[#faf5ee]"
                                x-text="`${currentIndex + 1} / ${images.length}`"
                            >1 / {{ count($images) }}</span>
                        @endif
                    </div>

                    @if (count($images) > 1)
                        <div class="flex gap-3 overflow-x-auto pb-1">
                            @foreach ($images as $index => $imageUrl)
                                <button
                                    type="button"
                                    @click="currentIndex = {{ $index }}"
                                    class="h-16 w-16 shrink-0 overflow-hidden border bg-[#f4ece2] transition"
                                    :class="currentIndex === {{ $index }} ? 'border-[#a88350]' : 'border-transparent opacity-65 hover:opacity-100'"
                                    aria-label="Xem ảnh {{ $index + 1 }}"
                                >
                                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="h-full w-full object-cover" loading="lazy">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="flex flex-col gap-6">
                    <div>
                        <h1 class="font-storefront-heading text-2xl font-medium leading-snug text-[#342821] sm:text-3xl">
                            {{ $product->name }}
                        </h1>
                    </div>

                    <div class="flex flex-wrap items-baseline gap-3">
                        <span class="text-2xl font-medium tracking-wide text-[#9c733d]">
                            {{ number_format($product->display_price, 0, ',', '.') }}đ
                        </span>
                        @if ($product->has_discount)
                            <span class="text-sm text-[#a49587] line-through">
                                {{ number_format((float) $product->price, 0, ',', '.') }}đ
                            </span>
                        @endif
                    </div>

                    <dl class="grid grid-cols-[6.5rem_1fr] gap-x-4 gap-y-2.5 text-sm">
                        <dt class="text-[#8b7355]">Danh mục</dt>
                        <dd class="text-[#342821]">{{ $category->name }}</dd>

                        <dt class="text-[#8b7355]">Tình trạng</dt>
                        <dd class="{{ $product->stock > 0 ? 'text-[#342821]' : 'text-rose-700' }}">
                            {{ $product->stock > 0 ? "Còn hàng ({$product->stock})" : 'Hết hàng' }}
                        </dd>

                        @if ($product->material)
                            <dt class="text-[#8b7355]">Chất liệu</dt>
                            <dd class="text-[#342821]">{{ $product->material }}</dd>
                        @endif
                    </dl>

                    @if ($product->description)
                        <div class="flex flex-col gap-2">
                            <h2 class="text-xs font-medium uppercase tracking-[0.16em] text-[#8b7355]">Mô tả sản phẩm</h2>
                            <p class="whitespace-pre-line text-sm leading-relaxed text-[#5c4f45]">{{ $product->description }}</p>
                        </div>
                    @endif

                    <div class="flex flex-col gap-3 pt-2">
                        @if ($product->stock > 0)
                            <div class="grid grid-cols-2 gap-3">
                                <button
                                    type="button"
                                    class="w-full border border-[#342821] bg-transparent px-4 py-3.5 text-center text-xs font-medium uppercase tracking-[0.12em] text-[#342821] transition hover:bg-[#342821] hover:text-[#faf5ee] focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-[#a88350]"
                                >
                                    Thêm vào giỏ hàng
                                </button>
                                <button
                                    type="button"
                                    class="w-full bg-[#342821] px-4 py-3.5 text-center text-xs font-medium uppercase tracking-[0.12em] text-[#faf5ee] transition hover:bg-[#a88350] focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-[#a88350]"
                                >
                                    Mua ngay
                                </button>
                            </div>
                        @else
                            <button
                                type="button"
                                disabled
                                class="w-full cursor-not-allowed border border-[#dfd5c9] bg-transparent px-6 py-3.5 text-center text-xs font-medium uppercase tracking-[0.16em] text-[#9b8d82]"
                            >
                                Hết hàng
                            </button>
                        @endif

                        <a
                            href="{{ route('products.index', ['category' => $category->slug]) }}"
                            class="inline-flex items-center gap-2 self-start pt-2 text-xs text-[#8b7355] transition hover:text-[#342821]"
                        >
                            <span aria-hidden="true">←</span>
                            <span>Quay lại danh mục {{ $category->name }}</span>
                        </a>
                    </div>
                </div>
            </div>

            @if ($relatedProducts->isNotEmpty())
                <div class="mt-16 border-t border-[#e7ddd0] pt-12">
                    <h2 class="font-storefront-heading text-xl font-medium text-[#342821] sm:text-2xl">
                        Sản phẩm tương tự
                    </h2>
                    <div class="mt-6 grid grid-cols-2 gap-x-5 gap-y-8 md:grid-cols-3 lg:grid-cols-4">
                        @foreach ($relatedProducts as $relatedProduct)
                            <article class="group flex flex-col">
                                <a
                                    href="{{ route('products.show', [$category, $relatedProduct]) }}"
                                    class="relative aspect-square w-full overflow-hidden bg-[#f4ece2] focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-[#a88350]"
                                    aria-label="Xem chi tiết {{ $relatedProduct->name }}"
                                >
                                    @if ($relatedProduct->thumbnail_url)
                                        <img
                                            src="{{ $relatedProduct->thumbnail_url }}"
                                            alt="{{ $relatedProduct->name }}"
                                            class="h-full w-full object-cover transition duration-500 ease-out group-hover:scale-[1.04]"
                                            loading="lazy"
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
                                    <p class="text-[10px] uppercase tracking-[0.14em] text-[#948374]">
                                        {{ $category->name }}
                                        @if ($relatedProduct->material)
                                            · {{ $relatedProduct->material }}
                                        @endif
                                    </p>

                                    <h3 class="line-clamp-2 font-storefront-heading text-sm font-normal leading-snug text-[#342821] sm:text-base">
                                        <a href="{{ route('products.show', [$category, $relatedProduct]) }}" class="transition hover:text-[#a88350] focus-visible:outline-none focus-visible:underline">
                                            {{ $relatedProduct->name }}
                                        </a>
                                    </h3>

                                    <div class="flex flex-wrap items-baseline gap-2">
                                        <span class="text-sm font-medium tracking-wide text-[#9c733d]">
                                            {{ number_format($relatedProduct->display_price, 0, ',', '.') }}đ
                                        </span>
                                        @if ($relatedProduct->has_discount)
                                            <span class="text-xs text-[#a49587] line-through">
                                                {{ number_format((float) $relatedProduct->price, 0, ',', '.') }}đ
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-auto pt-2.5">
                                        @if ($relatedProduct->stock > 0)
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
                </div>
            @endif
        </div>
    </section>
@endsection
