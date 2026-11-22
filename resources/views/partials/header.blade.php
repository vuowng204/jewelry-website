<header class="border-b border-[#e5e5e5] bg-[#f3f3f3] text-[#1f1d1a]">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-x-6 gap-y-4 px-5 py-4 lg:flex-nowrap lg:px-8">
        <div class="flex shrink-0 items-center gap-3">
            <span class="flex h-9 w-9 items-center justify-center border border-[#b58d4a] bg-white text-[#b58d4a]" aria-hidden="true">
                <svg class="h-5 w-5" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.3">
                    <path d="M5 10h22l-11 17L5 10Zm0 0 5-6h12l5 6M10 4l6 23 6-23M5 10h22" />
                </svg>
            </span>
            <span class="flex flex-col leading-none">
                <span class="font-storefront-heading text-xl tracking-[0.14em] text-[#1f1d1a]">Luna Jewelry</span>
            </span>
        </div>

        <form action="{{ route('products.index') }}" method="GET" class="order-last flex w-full min-w-0 items-center gap-2.5 border-b border-[#d4d4d4] pb-1.5 text-[#5c5854] transition focus-within:border-[#b58d4a] lg:order-none lg:max-w-md lg:flex-1" role="search">
            <input
                class="min-w-0 flex-1 border-0 bg-transparent p-0 text-sm text-[#1f1d1a] placeholder:text-[#8a8680] focus:ring-0"
                type="search"
                name="q"
                value="{{ request('q') }}"
                placeholder="Tìm kiếm trang sức..."
                aria-label="Tìm kiếm sản phẩm"
            >
            <button type="submit" class="flex items-center justify-center text-[#9e783a] transition hover:text-[#1f1d1a]" aria-label="Tìm kiếm">
                <x-storefront-icon name="search" class="h-4 w-4 shrink-0" />
            </button>
        </form>

        <div class="flex shrink-0 items-center gap-4 text-[#2e2b28]" role="group" aria-label="Tài khoản và mua sắm">
            <a class="flex h-9 w-9 items-center justify-center rounded-full transition hover:bg-[#e6e6e6] hover:text-[#9e783a] focus-visible:outline focus-visible:outline-1 focus-visible:outline-[#b58d4a]" href="{{ auth()->check() ? route('profile.edit') : route('login') }}" aria-label="Tài khoản" title="Tài khoản">
                <x-storefront-icon name="user" class="h-5 w-5" />
            </a>
            <a href="{{ route('cart.show') }}" class="flex h-9 w-9 items-center justify-center rounded-full transition hover:bg-[#e6e6e6] hover:text-[#9e783a] focus-visible:outline focus-visible:outline-1 focus-visible:outline-[#b58d4a]" aria-label="Giỏ hàng" title="Giỏ hàng">
                <x-storefront-icon name="shopping-cart" class="h-5 w-5" />
            </a>
        </div>
    </div>

    <div class="border-t border-[#e6e6e6]">
        <nav class="mx-auto flex max-w-7xl flex-wrap items-center justify-center gap-x-10 gap-y-2 px-5 text-xs uppercase tracking-[0.2em] lg:px-8" aria-label="Danh mục chính">
            <a
                class="border-b-2 py-3.5 font-medium transition {{ request()->routeIs('home.index') ? 'border-[#b58d4a] text-[#1f1d1a]' : 'border-transparent text-[#66625d] hover:text-[#1f1d1a]' }}"
                href="{{ route('home.index') }}"
                @if (request()->routeIs('home.index')) aria-current="page" @endif
            >Trang chủ</a>
            <a
                class="border-b-2 py-3.5 font-medium transition {{ request()->routeIs('products.*') ? 'border-[#b58d4a] text-[#1f1d1a]' : 'border-transparent text-[#66625d] hover:text-[#1f1d1a]' }}"
                href="{{ route('products.index') }}"
                @if (request()->routeIs('products.*')) aria-current="page" @endif
            >Sản phẩm</a>
        </nav>
    </div>
</header>
