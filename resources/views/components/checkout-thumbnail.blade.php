@props(['product'])

<div {{ $attributes->class('relative shrink-0 overflow-hidden rounded-md bg-[#f4ece2]') }} x-data="{ failed: false }">
    <div class="absolute inset-0 flex items-center justify-center text-[#a58a6d]" aria-hidden="true">
        <x-storefront-icon name="shopping-cart" class="h-5 w-5" />
    </div>
    @if ($product->thumbnail_url)
        <img class="relative h-full w-full bg-[#f4ece2] object-cover" src="{{ $product->thumbnail_url }}" alt="" x-init="failed = $el.complete && $el.naturalWidth === 0" x-show="!failed" x-on:error="failed = true">
    @endif
</div>
