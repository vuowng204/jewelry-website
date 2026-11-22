@props(['title', 'checkout' => false, 'fromCart' => true])

<div class="border-b border-[#efebe4] bg-[#f8f6f2] px-5 py-8 text-center sm:py-10">
    <h1 class="font-storefront-heading text-3xl font-medium text-[#1c1714] sm:text-4xl">{{ $title }}</h1>
    <nav aria-label="Đường dẫn" class="mt-2 flex justify-center gap-2 text-xs text-[#6a5f56]">
        <a href="{{ route('home.index') }}" class="transition hover:text-[#1c1714] hover:underline">Trang chủ</a>
        <span aria-hidden="true">/</span>
        @if ($checkout && $fromCart)
            <a href="{{ route('cart.show') }}" class="transition hover:text-[#1c1714] hover:underline">Giỏ hàng</a>
            <span aria-hidden="true">/</span>
        @endif
        <span aria-current="page" class="text-[#1c1714]">{{ $checkout ? 'Thanh toán' : 'Giỏ hàng' }}</span>
    </nav>
</div>
