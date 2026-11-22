@props(['product', 'compact' => false])

@php
    $buttonSize = $compact ? 'px-1 py-2 text-[10px] tracking-[0.06em]' : 'min-h-10 px-4 py-3.5 text-xs tracking-[0.12em]';
    $button = 'inline-flex w-full items-center justify-center text-center font-medium uppercase transition focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-[#b8924f] '.$buttonSize;
@endphp

@if ($product->stock > 0)
    <div class="grid grid-cols-2 {{ $compact ? 'gap-1.5' : 'gap-3' }}">
        <form method="POST" action="{{ route('cart.items.store', $product) }}" class="flex">
            @csrf
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="{{ $button }} border border-[#1c1714] bg-white text-[#1c1714] hover:bg-[#1c1714] hover:text-[#fdfaf5]">
                {{ $compact ? 'Thêm giỏ hàng' : 'Thêm vào giỏ hàng' }}
            </button>
        </form>
        <a href="{{ route('checkout.buy', $product) }}" class="{{ $button }} bg-[#1c1714] text-[#fdfaf5] hover:bg-[#b8924f]">
            Mua ngay
        </a>
    </div>
@else
    <button type="button" disabled class="{{ $button }} cursor-not-allowed border border-[#e7ddd0] bg-[#f7f2ea] text-[#9c8f82]">
        Hết hàng
    </button>
@endif
