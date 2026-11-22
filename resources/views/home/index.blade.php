@extends('layouts.layoutmaster')

@section('title', 'Trang chủ | Luna Jewelry')

@section('content')
    <section class="relative isolate overflow-hidden border-b border-[#e7ddd0] bg-[#efe5d5] aspect-auto min-h-[17rem] sm:aspect-[1439/530] sm:min-h-0" aria-labelledby="home-hero-title">
        <img
            class="absolute inset-0 -z-10 h-full w-full object-cover object-[60%_center]"
            src="{{ asset('images/anh_nguoi_mau.png') }}"
            alt="Người mẫu đeo trang sức kim cương Luna Jewelry"
            fetchpriority="high"
        >
        <div class="mx-auto flex h-full max-w-7xl items-center px-6 py-10 sm:px-8 lg:px-12">
            <div class="max-w-[58%] sm:max-w-[48%]">
                <p class="mb-2.5 text-xs font-medium uppercase tracking-[0.24em] text-[#9c7839] sm:text-sm">Luna Jewelry</p>
                <h1 id="home-hero-title" class="font-storefront-heading text-2xl leading-tight text-[#1c1714] sm:text-4xl lg:text-5xl">
                    Kiệt tác từ sự tinh tế
                </h1>
                <p class="mt-3.5 max-w-md text-xs leading-relaxed text-[#4f443b] sm:text-sm">
                    Luna là hiện thân của sự tinh tế, nơi mỗi món trang sức không chỉ là phụ kiện, mà còn là câu chuyện về cảm xúc, phong cách và những khoảnh khắc đáng nhớ.
                </p>
                <div class="mt-6">
                    <a
                        href="{{ route('products.index') }}"
                        class="inline-flex items-center gap-2.5 bg-[#1c1714] px-6 py-3 text-xs font-medium uppercase tracking-[0.16em] text-[#fdfaf5] transition hover:bg-[#b8924f]"
                    >
                        <span>Khám phá bộ sưu tập</span>
                        <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    @if ($featuredProducts->isNotEmpty())
        <section class="border-b border-[#efebe4] bg-white" aria-labelledby="featured-products-title">
            <div class="mx-auto max-w-7xl px-6 py-14 sm:px-8 lg:px-12">
                <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-[0.22em] text-[#b8924f]">Tuyển chọn từ Luna</p>
                        <h2 id="featured-products-title" class="mt-1.5 font-storefront-heading text-2xl leading-snug text-[#1c1714] sm:text-3xl">
                            Sản phẩm nổi bật
                        </h2>
                    </div>

                    <a
                        href="{{ route('products.index') }}"
                        class="inline-flex items-center gap-2 text-xs font-medium uppercase tracking-[0.14em] text-[#6a5f56] underline decoration-[#b8924f] underline-offset-8 transition hover:text-[#1c1714]"
                    >
                        <span>Xem tất cả</span>
                        <span aria-hidden="true">→</span>
                    </a>
                </div>

                <div class="grid grid-cols-2 gap-x-5 gap-y-8 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($featuredProducts as $product)
                        <article class="group flex flex-col">
                            <a
                                href="{{ route('products.show', [$product->category, $product]) }}"
                                class="relative aspect-square w-full overflow-hidden bg-[#f3f0ea] focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-[#b8924f]"
                                aria-label="Xem chi tiết {{ $product->name }}"
                            >
                                @if ($product->thumbnail_url)
                                    <img
                                        src="{{ $product->thumbnail_url }}"
                                        alt="{{ $product->name }}"
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
                                @if ($product->category)
                                    <p class="text-[10px] uppercase tracking-[0.14em] text-[#8a8278]">
                                        {{ $product->category->name }}
                                        @if ($product->material)
                                            · {{ $product->material }}
                                        @endif
                                    </p>
                                @endif

                                <h3 class="line-clamp-2 font-storefront-heading text-sm font-normal leading-snug text-[#1f1d1a] sm:text-base">
                                    <a
                                        href="{{ route('products.show', [$product->category, $product]) }}"
                                        class="transition hover:text-[#b8924f] focus-visible:outline-none focus-visible:underline"
                                    >
                                        {{ $product->name }}
                                    </a>
                                </h3>

                                <div class="flex flex-wrap items-baseline gap-2">
                                    <span class="text-sm font-medium tracking-wide text-[#9e7d47]">
                                        {{ number_format($product->display_price, 0, ',', '.') }}đ
                                    </span>
                                    @if ($product->has_discount)
                                        <span class="text-xs text-[#9c958b] line-through">
                                            {{ number_format((float) $product->price, 0, ',', '.') }}đ
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-auto pt-2.5">
                                    <x-storefront-product-actions :product="$product" compact />
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section id="ve-luna" class="bg-white">
        <div class="mx-auto grid max-w-7xl items-center gap-8 px-6 py-14 sm:px-8 md:grid-cols-2 md:gap-14 md:py-20 lg:px-12">
            <div class="max-w-lg">
                <p class="text-xs font-medium uppercase tracking-[0.22em] text-[#b8924f]">Về Luna</p>
                <h2 class="mt-2 font-storefront-heading text-2xl leading-snug text-[#1c1714] sm:text-3xl">
                    Hành trình kiến tạo những giá trị vĩnh cửu
                </h2>
                <div class="mt-4 h-px w-12 bg-[#b8924f]"></div>
                <p class="mt-5 text-sm leading-relaxed text-[#6a5f56]">
                    Thành lập với niềm đam mê cái đẹp và sự trân trọng những giá trị bền vững, Luna mong đem đến những thiết kế trang sức tinh xảo, được chế tác từ tình yêu và sự chỉn chu, lưu giữ những khoảnh khắc quý giá mãi.
                </p>
            </div>
            <div class="overflow-hidden border border-[#e7ddd0] bg-[#f4ede3] p-2">
                <img
                    class="h-full min-h-56 w-full object-cover"
                    src="{{ asset('images/anh_nhan.png') }}"
                    alt="Nhẫn kim cương tinh xảo trên nền hoa trắng"
                    loading="lazy"
                >
            </div>
        </div>
    </section>

    <section class="border-y border-[#efebe4] bg-[#f8f6f2]" aria-labelledby="why-luna-title">
        <div class="mx-auto grid max-w-7xl grid-cols-2 gap-6 px-6 py-14 sm:px-8 md:grid-cols-5 md:items-center md:gap-5 md:py-16 lg:px-12">
            <div class="col-span-2 md:col-span-1">
                <p class="text-xs font-medium uppercase tracking-[0.2em] text-[#9c7839]">Vì sao chọn Luna</p>
                <h2 id="why-luna-title" class="mt-2 font-storefront-heading text-xl leading-snug text-[#1c1714] sm:text-2xl">
                    Không chỉ là trang sức, đó là sự an tâm
                </h2>
                <p class="mt-3 text-sm leading-relaxed text-[#6a5f56]">
                    Chúng tôi luôn đặt chất lượng và trải nghiệm của khách hàng lên hàng đầu, để mỗi món trang sức trở thành bạn đồng hành tuyệt vời.
                </p>
            </div>

            <article class="flex flex-col items-center text-center md:px-2">
                <span class="flex h-11 w-11 items-center justify-center rounded-full border border-[#d8c39e] bg-[#fdfbf7] text-[#b8924f]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
                        <path d="M12 2 3 12l9 10 9-10-9-10Z" />
                        <path d="m3 12 9 2 9-2M12 2l-3 10 3 12 3-12-3-10Z" />
                    </svg>
                </span>
                <h3 class="mt-3.5 font-storefront-heading text-sm font-medium text-[#1c1714]">Chất lượng vượt trội</h3>
                <p class="mt-1.5 text-xs leading-relaxed text-[#6a5f56]">Kim cương và đá quý được tuyển chọn kỹ lưỡng.</p>
            </article>

            <article class="flex flex-col items-center text-center md:border-l md:border-[#dfd2c0] md:px-2">
                <span class="flex h-11 w-11 items-center justify-center rounded-full border border-[#d8c39e] bg-[#fdfbf7] text-[#b8924f]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
                        <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z" />
                        <path d="m9 12 2 2 4-4" />
                    </svg>
                </span>
                <h3 class="mt-3.5 font-storefront-heading text-sm font-medium text-[#1c1714]">Chế tác tinh xảo</h3>
                <p class="mt-1.5 text-xs leading-relaxed text-[#6a5f56]">Tỉ mỉ đến từng chi tiết bởi những nghệ nhân lành nghề.</p>
            </article>

            <article class="flex flex-col items-center text-center md:border-l md:border-[#dfd2c0] md:px-2">
                <span class="flex h-11 w-11 items-center justify-center rounded-full border border-[#d8c39e] bg-[#fdfbf7] text-[#b8924f]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
                        <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z" />
                    </svg>
                </span>
                <h3 class="mt-3.5 font-storefront-heading text-sm font-medium text-[#1c1714]">Thiết kế độc quyền</h3>
                <p class="mt-1.5 text-xs leading-relaxed text-[#6a5f56]">Tôn vinh nét đẹp riêng, không lỗi thời.</p>
            </article>

            <article class="flex flex-col items-center text-center md:border-l md:border-[#dfd2c0] md:px-2">
                <span class="flex h-11 w-11 items-center justify-center rounded-full border border-[#d8c39e] bg-[#fdfbf7] text-[#b8924f]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
                        <circle cx="18" cy="5" r="3" />
                        <circle cx="6" cy="12" r="3" />
                        <circle cx="18" cy="19" r="3" />
                        <path d="m8.6 10.6 6.8-4.2m-6.8 7 6.8 4.2" />
                    </svg>
                </span>
                <h3 class="mt-3.5 font-storefront-heading text-sm font-medium text-[#1c1714]">Dịch vụ tận tâm</h3>
                <p class="mt-1.5 text-xs leading-relaxed text-[#6a5f56]">Đồng hành cùng bạn trong từng trải nghiệm.</p>
            </article>
        </div>
    </section>

    <section class="bg-white">
        <div class="mx-auto grid max-w-7xl items-center gap-8 px-6 py-14 sm:px-8 md:grid-cols-2 md:gap-14 md:py-20 lg:px-12">
            <div class="max-w-lg">
                <p class="text-xs font-medium uppercase tracking-[0.22em] text-[#b8924f]">Không gian trải nghiệm</p>
                <h2 class="mt-2 font-storefront-heading text-2xl leading-snug text-[#1c1714] sm:text-3xl">
                    Nơi vẻ đẹp được lan tỏa
                </h2>
                <div class="mt-4 h-px w-12 bg-[#b8924f]"></div>
                <p class="mt-5 text-sm leading-relaxed text-[#6a5f56]">
                    Không gian trưng bày hiện đại, sang trọng cùng đội ngũ tư vấn chuyên nghiệp, sẵn sàng đồng hành để cho bạn trải nghiệm mua sắm đáng nhớ và đẳng cấp nhất.
                </p>
            </div>
            <div class="overflow-hidden border border-[#e7ddd0] bg-[#f4ede3] p-2">
                <img
                    class="h-full min-h-56 w-full object-cover"
                    src="{{ asset('images/anh_cua_hang.png') }}"
                    alt="Không gian cửa hàng Luna Jewelry sang trọng"
                    loading="lazy"
                >
            </div>
        </div>
    </section>
@endsection
