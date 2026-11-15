<header class="bg-[#342821] text-[#faf5ee]">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-x-3 gap-y-4 px-5 py-4 sm:gap-x-6 lg:flex-nowrap lg:gap-x-10 lg:px-8">
        <a class="flex shrink-0 items-center gap-3" href="{{ route('home.index') }}" aria-label="Luna Jewelry - Trang chủ">
            <span class="flex h-10 w-10 items-center justify-center bg-white text-[#ae9270]" aria-hidden="true">
                <svg class="h-7 w-7" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.2">
                    <path d="M5 10h22l-11 17L5 10Zm0 0 5-6h12l5 6M10 4l6 23 6-23M5 10h22" />
                </svg>
            </span>
            <span class="font-serif text-xl tracking-wide text-white">Luna Jewelry</span>
        </a>

        <div class="order-last flex min-h-10 w-full min-w-0 items-center gap-3 rounded-full bg-[#f7f0ed] px-4 text-[#705d51] focus-within:ring-2 focus-within:ring-[#d6b48a] lg:order-none lg:max-w-xl lg:flex-1" role="search">
            <x-storefront-icon name="menu-2" class="h-4 w-4 shrink-0" />
            <input class="min-w-0 flex-1 border-0 bg-transparent p-0 text-base text-[#342821] placeholder:text-[#705d51] focus:ring-0 sm:text-sm" type="search" placeholder="Tìm kiếm sản phẩm" aria-label="Tìm kiếm sản phẩm">
            <x-storefront-icon name="search" class="h-5 w-5 shrink-0" />
        </div>

        <div class="flex shrink-0 items-center gap-3 text-[#e2cbb0] sm:gap-5" role="group" aria-label="Tài khoản và mua sắm">
            <a class="flex h-10 w-9 items-center justify-center rounded hover:bg-white/10 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-[#e2cbb0]" href="{{ auth()->check() ? route('profile.edit') : route('login') }}" aria-label="Tài khoản" title="Tài khoản">
                <x-storefront-icon name="user" class="h-6 w-6" />
            </a>
            <span role="img" aria-label="Yêu thích" title="Yêu thích">
                <x-storefront-icon name="heart" class="h-6 w-6" />
            </span>
            <span role="img" aria-label="Giỏ hàng" title="Giỏ hàng">
                <x-storefront-icon name="shopping-cart" class="h-6 w-6" />
            </span>
        </div>
    </div>

    <nav class="mx-auto flex max-w-7xl flex-wrap justify-center gap-2 px-5 pb-3 text-center text-sm font-medium leading-5 text-[#342821] sm:justify-between sm:gap-4 lg:px-8" aria-label="Danh mục chính">
        <a class="rounded bg-[#e8d1b4] px-3 py-2 hover:bg-[#f0dbc0] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white sm:min-w-[7rem]" href="{{ route('home.index') }}" @if (request()->routeIs('home.index')) aria-current="page" @endif>Trang chủ</a>
        @foreach (['Sản phẩm', 'Nhẫn · Dây', 'Vòng tay', 'Bông tai'] as $category)
            <span class="rounded bg-[#f6eee5] px-3 py-2 sm:min-w-[7rem]">{{ $category }}</span>
        @endforeach
    </nav>
</header>
