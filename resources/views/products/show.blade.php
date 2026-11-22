@extends('layouts.layoutmaster')

@section('title', $product->name.' | Luna Jewelry')

@section('content')
    <section class="bg-white py-10 lg:py-14">
        <div class="mx-auto max-w-6xl px-5 lg:px-8">
            @if ($errors->any())
                <p role="alert" class="mb-5 rounded-md bg-rose-50 px-4 py-3 text-sm text-rose-800">{{ $errors->first() }}</p>
            @endif
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
                    <div class="relative aspect-square w-full overflow-hidden bg-[#f3f0ea]">
                        @if (count($images) > 0)
                            <img
                                :src="images[currentIndex]"
                                src="{{ $images[0] }}"
                                alt="{{ $product->name }}"
                                class="h-full w-full object-cover"
                            >
                        @else
                            <div class="flex h-full w-full items-center justify-center text-[#c9bfb2]" aria-hidden="true">
                                <svg class="h-16 w-16" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.1">
                                    <path d="M5 10h22l-11 17L5 10Zm0 0 5-6h12l5 6M10 4l6 23 6-23M5 10h22" />
                                </svg>
                            </div>
                        @endif

                        @if (count($images) > 1)
                            <button
                                type="button"
                                @click="prev()"
                                class="absolute left-3 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center bg-white/90 text-[#1f1d1a] transition hover:bg-[#23201d] hover:text-white focus-visible:outline focus-visible:outline-1 focus-visible:outline-[#b89762]"
                                aria-label="Ảnh trước"
                            >
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m15 18-6-6 6-6" />
                                </svg>
                            </button>

                            <button
                                type="button"
                                @click="next()"
                                class="absolute right-3 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center bg-white/90 text-[#1f1d1a] transition hover:bg-[#23201d] hover:text-white focus-visible:outline focus-visible:outline-1 focus-visible:outline-[#b89762]"
                                aria-label="Ảnh tiếp theo"
                            >
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </button>

                            <span
                                class="absolute bottom-3 right-3 bg-[#23201d]/80 px-2.5 py-1 text-xs tracking-widest text-[#faf9f7]"
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
                                    class="h-16 w-16 shrink-0 overflow-hidden border bg-[#f3f0ea] transition"
                                    :class="currentIndex === {{ $index }} ? 'border-[#b89762]' : 'border-transparent opacity-65 hover:opacity-100'"
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
                        <h1 class="font-storefront-heading text-2xl font-medium leading-snug text-[#1f1d1a] sm:text-3xl">
                            {{ $product->name }}
                        </h1>
                    </div>

                    <div class="flex flex-wrap items-baseline gap-3">
                        <span class="text-2xl font-medium tracking-wide text-[#9e7d47]">
                            {{ number_format($product->display_price, 0, ',', '.') }}đ
                        </span>
                        @if ($product->has_discount)
                            <span class="text-sm text-[#9c958b] line-through">
                                {{ number_format((float) $product->price, 0, ',', '.') }}đ
                            </span>
                        @endif
                    </div>

                    <dl class="grid grid-cols-[6.5rem_1fr] gap-x-4 gap-y-2.5 text-sm">
                        <dt class="text-[#8a8278]">Danh mục</dt>
                        <dd class="text-[#1f1d1a]">{{ $category->name }}</dd>

                        <dt class="text-[#8a8278]">Tình trạng</dt>
                        <dd class="{{ $product->stock > 0 ? 'text-[#1f1d1a]' : 'text-rose-700' }}">
                            {{ $product->stock > 0 ? "Còn hàng ({$product->stock})" : 'Hết hàng' }}
                        </dd>

                        @if ($product->material)
                            <dt class="text-[#8a8278]">Chất liệu</dt>
                            <dd class="text-[#1f1d1a]">{{ $product->material }}</dd>
                        @endif
                    </dl>

                    @if ($product->description)
                        <div class="flex flex-col gap-2">
                            <h2 class="text-xs font-medium uppercase tracking-[0.16em] text-[#9e7d47]">Mô tả sản phẩm</h2>
                            <p class="whitespace-pre-line text-sm leading-relaxed text-[#6e675e]">{{ $product->description }}</p>
                        </div>
                    @endif

                    <div class="flex flex-col gap-3 pt-2">
                        <x-storefront-product-actions :product="$product" />

                        <a
                            href="{{ route('products.index', ['category' => $category->slug]) }}"
                            class="inline-flex items-center gap-2 self-start pt-2 text-xs text-[#8a8278] transition hover:text-[#1f1d1a]"
                        >
                            <span aria-hidden="true">←</span>
                            <span>Quay lại danh mục {{ $category->name }}</span>
                        </a>
                    </div>
                </div>
            </div>

            @if ($relatedProducts->isNotEmpty())
                <div class="mt-16 border-t border-[#e8e3db] pt-12">
                    <h2 class="font-storefront-heading text-xl font-medium text-[#1f1d1a] sm:text-2xl">
                        Sản phẩm tương tự
                    </h2>
                    <div class="mt-6 grid grid-cols-2 gap-x-5 gap-y-8 md:grid-cols-3 lg:grid-cols-4">
                        @foreach ($relatedProducts as $relatedProduct)
                            <article class="group flex flex-col">
                                <a
                                    href="{{ route('products.show', [$category, $relatedProduct]) }}"
                                    class="relative aspect-square w-full overflow-hidden bg-[#f3f0ea] focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-[#b89762]"
                                    aria-label="Xem chi tiết {{ $relatedProduct->name }}"
                                >
                                    @if ($relatedProduct->thumbnail_url)
                                        <img
                                            src="{{ $relatedProduct->thumbnail_url }}"
                                            alt="{{ $relatedProduct->name }}"
                                            class="h-full w-full object-cover transition-transform duration-300 ease-out group-hover:scale-[1.03]"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-[#c9bfb2]" aria-hidden="true">
                                            <svg class="h-10 w-10 sm:h-12 sm:w-12" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.1">
                                                <path d="M5 10h22l-11 17L5 10Zm0 0 5-6h12l5 6M10 4l6 23 6-23M5 10h22" />
                                            </svg>
                                        </div>
                                    @endif
                                </a>

                                <div class="flex flex-1 flex-col gap-1.5 pt-3">
                                    <p class="text-[10px] uppercase tracking-[0.14em] text-[#8a8278]">
                                        {{ $category->name }}
                                        @if ($relatedProduct->material)
                                            · {{ $relatedProduct->material }}
                                        @endif
                                    </p>

                                    <h3 class="line-clamp-2 font-storefront-heading text-sm font-normal leading-snug text-[#1f1d1a] sm:text-base">
                                        <a href="{{ route('products.show', [$category, $relatedProduct]) }}" class="transition hover:text-[#b89762] focus-visible:outline-none focus-visible:underline">
                                            {{ $relatedProduct->name }}
                                        </a>
                                    </h3>

                                    <div class="flex flex-wrap items-baseline gap-2">
                                        <span class="text-sm font-medium tracking-wide text-[#9e7d47]">
                                            {{ number_format($relatedProduct->display_price, 0, ',', '.') }}đ
                                        </span>
                                        @if ($relatedProduct->has_discount)
                                            <span class="text-xs text-[#9c958b] line-through">
                                                {{ number_format((float) $relatedProduct->price, 0, ',', '.') }}đ
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-auto pt-2.5">
                                        <x-storefront-product-actions :product="$relatedProduct" compact />
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
