@extends('layouts.layoutmaster')

@section('title', 'Trang chủ | Luna Jewelry')

@section('content')
    <section class="relative isolate overflow-hidden bg-[#eee5d7] aspect-auto min-h-[15rem] sm:aspect-[1439/530] sm:min-h-0" aria-labelledby="home-hero-title">
        <img
            class="absolute inset-0 -z-10 h-full w-full object-cover object-[60%_center]"
            src="{{ asset('images/anh_nguoi_mau.png') }}"
            alt="Người mẫu đeo trang sức kim cương Luna Jewelry"
            fetchpriority="high"
        >
        <div class="mx-auto flex h-full max-w-7xl items-center px-6 py-10 sm:px-8 lg:px-12">
            <div class="max-w-[56%] sm:max-w-[50%]">
                <p class="mb-2 text-xs font-medium uppercase tracking-[0.2em] text-[#92734f] sm:text-sm">Luna Jewelry</p>
                <h1 id="home-hero-title" class="font-storefront-heading text-2xl leading-tight text-[#241d18] sm:text-4xl">
                    Kiệt tác từ sự tinh tế
                </h1>
                <p class="mt-3 max-w-md text-xs leading-relaxed text-[#493c31] sm:text-sm">
                    Luna là hiện thân của sự tinh tế, nơi mỗi món trang sức không chỉ là phụ kiện, mà còn là câu chuyện về cảm xúc, phong cách và những khoảnh khắc đáng nhớ.
                </p>
                <a class="mt-5 inline-flex items-center gap-2 bg-[#a88350] px-4 py-3 text-xs font-medium text-white transition hover:bg-[#8f6e43] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#705638] sm:text-sm" href="#ve-luna">
                    Khám phá bộ sưu tập
                    <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </section>

    <section id="ve-luna" class="bg-[#fcfaf7]">
        <div class="mx-auto grid max-w-7xl items-center gap-8 px-6 py-12 sm:px-8 md:grid-cols-2 md:gap-12 md:py-16 lg:px-12">
            <div class="max-w-lg">
                <p class="font-storefront-heading text-base text-[#a88350]">Về Luna</p>
                <h2 class="mt-1 font-storefront-heading text-2xl leading-snug text-[#342821] sm:text-3xl">
                    Hành trình kiến tạo những giá trị vĩnh cửu
                </h2>
                <p class="mt-4 text-sm leading-relaxed text-[#62564c]">
                    Thành lập với niềm đam mê cái đẹp và sự trân trọng những giá trị bền vững, Luna mong đem đến những thiết kế trang sức tinh xảo, được chế tác từ tình yêu và sự chỉn chu, lưu giữ những khoảnh khắc quý giá mãi.
                </p>
            </div>
            <img
                class="h-full min-h-56 w-full object-cover"
                src="{{ asset('images/anh_nhan.png') }}"
                alt="Nhẫn kim cương tinh xảo trên nền hoa trắng"
                loading="lazy"
            >
        </div>
    </section>

    <section class="bg-[#f7f3ed]" aria-labelledby="why-luna-title">
        <div class="mx-auto grid max-w-7xl grid-cols-2 gap-6 px-6 py-12 sm:px-8 md:grid-cols-5 md:items-center md:gap-5 md:py-16 lg:px-12">
            <div class="col-span-2 md:col-span-1">
                <p class="font-storefront-heading text-base text-[#a88350]">Vì sao nên chọn Luna</p>
                <h2 id="why-luna-title" class="mt-1 font-storefront-heading text-xl leading-snug text-[#342821] sm:text-2xl">
                    Không chỉ là trang sức, đó là sự an tâm
                </h2>
                <p class="mt-3 text-sm leading-relaxed text-[#62564c]">
                    Chúng tôi luôn đặt chất lượng và trải nghiệm của khách hàng lên hàng đầu, để mỗi món trang sức trở thành bạn đồng hành tuyệt vời.
                </p>
            </div>

            <article class="flex flex-col items-center text-center md:px-2">
                <svg class="h-7 w-7 text-[#a88350]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
                    <path d="M12 2 3 12l9 10 9-10-9-10Z" />
                    <path d="m3 12 9 2 9-2M12 2l-3 10 3 12 3-12-3-10Z" />
                </svg>
                <h3 class="mt-3 font-storefront-heading text-sm text-[#342821]">Chất lượng vượt trội</h3>
                <p class="mt-2 text-xs leading-relaxed text-[#62564c]">Kim cương và đá quý được tuyển chọn kỹ lưỡng.</p>
            </article>

            <article class="flex flex-col items-center text-center md:border-l md:border-[#d8cec1] md:px-2">
                <svg class="h-7 w-7 text-[#a88350]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
                    <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z" />
                    <path d="m9 12 2 2 4-4" />
                </svg>
                <h3 class="mt-3 font-storefront-heading text-sm text-[#342821]">Chế tác tinh xảo</h3>
                <p class="mt-2 text-xs leading-relaxed text-[#62564c]">Tỉ mỉ đến từng chi tiết bởi những nghệ nhân lành nghề.</p>
            </article>

            <article class="flex flex-col items-center text-center md:border-l md:border-[#d8cec1] md:px-2">
                <svg class="h-7 w-7 text-[#a88350]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
                    <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z" />
                </svg>
                <h3 class="mt-3 font-storefront-heading text-sm text-[#342821]">Thiết kế độc quyền</h3>
                <p class="mt-2 text-xs leading-relaxed text-[#62564c]">Tôn vinh nét đẹp riêng, không lỗi thời.</p>
            </article>

            <article class="flex flex-col items-center text-center md:border-l md:border-[#d8cec1] md:px-2">
                <svg class="h-7 w-7 text-[#a88350]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
                    <circle cx="18" cy="5" r="3" />
                    <circle cx="6" cy="12" r="3" />
                    <circle cx="18" cy="19" r="3" />
                    <path d="m8.6 10.6 6.8-4.2m-6.8 7 6.8 4.2" />
                </svg>
                <h3 class="mt-3 font-storefront-heading text-sm text-[#342821]">Dịch vụ tận tâm</h3>
                <p class="mt-2 text-xs leading-relaxed text-[#62564c]">Đồng hành cùng bạn trong từng trải nghiệm.</p>
            </article>
        </div>
    </section>

    <section class="bg-[#fcfaf7]">
        <div class="mx-auto grid max-w-7xl items-center gap-8 px-6 py-12 sm:px-8 md:grid-cols-2 md:gap-12 md:py-16 lg:px-12">
            <div class="max-w-lg">
                <p class="font-storefront-heading text-base text-[#a88350]">Không gian trải nghiệm</p>
                <h2 class="mt-1 font-storefront-heading text-2xl leading-snug text-[#342821] sm:text-3xl">
                    Nơi vẻ đẹp được lan tỏa
                </h2>
                <p class="mt-4 text-sm leading-relaxed text-[#62564c]">
                    Không gian trưng bày hiện đại, sang trọng cùng đội ngũ tư vấn chuyên nghiệp, sẵn sàng đồng hành để cho bạn trải nghiệm mua sắm đáng nhớ và đẳng cấp nhất.
                </p>
            </div>
            <img
                class="h-full min-h-56 w-full object-cover"
                src="{{ asset('images/anh_cua_hang.png') }}"
                alt="Không gian cửa hàng Luna Jewelry sang trọng"
                loading="lazy"
            >
        </div>
    </section>
@endsection
