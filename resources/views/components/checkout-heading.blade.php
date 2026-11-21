@props(['title', 'checkout' => false, 'fromCart' => true])

<div class="bg-[#f5efe6] px-5 py-8 text-center sm:py-10">
    <h1 class="font-storefront-heading text-3xl font-medium text-[#342821] sm:text-4xl">{{ $title }}</h1>
    <nav aria-label="Đường dẫn" class="mt-2 flex justify-center gap-2 text-xs text-[#796652]">
        <a href="{{ route('home.index') }}" class="hover:underline">Trang chủ</a>
        <span aria-hidden="true">/</span>
        @if ($checkout && $fromCart)
            <a href="{{ route('cart.show') }}" class="hover:underline">Giỏ hàng</a>
            <span aria-hidden="true">/</span>
        @endif
        <span aria-current="page" class="text-[#342821]">{{ $checkout ? 'Thanh toán' : 'Giỏ hàng' }}</span>
    </nav>
</div>
