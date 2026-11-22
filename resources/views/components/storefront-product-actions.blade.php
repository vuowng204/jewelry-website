@props(['product', 'compact' => false])

@php
    $buttonSize = $compact ? 'px-1 py-2 text-[10px] tracking-[0.06em]' : 'min-h-10 px-4 py-3.5 text-xs tracking-[0.12em]';
    $button = 'inline-flex w-full items-center justify-center text-center font-medium uppercase transition focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-[#a88350] '.$buttonSize;
@endphp

@if ($product->stock > 0)
    <div class="grid grid-cols-2 {{ $compact ? 'gap-1.5' : 'gap-3' }}">
        <form method="POST" action="{{ route('cart.items.store', $product) }}" class="flex">
            @csrf
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="{{ $button }} border border-[#342821] text-[#342821] hover:bg-[#342821] hover:text-[#faf5ee]">
                {{ $compact ? 'Thêm giỏ hàng' : 'Thêm vào giỏ hàng' }}
            </button>
        </form>
        <a href="{{ route('checkout.buy', $product) }}" class="{{ $button }} bg-[#342821] text-[#faf5ee] hover:bg-[#a88350]">
            Mua ngay
        </a>
    </div>
@else
    <button type="button" disabled class="{{ $button }} cursor-not-allowed border border-[#dfd5c9] text-[#9b8d82]">
        Hết hàng
    </button>
@endif
