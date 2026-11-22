@extends('layouts.layoutmaster')

@section('title', 'Giỏ hàng | Luna Jewelry')

@section('content')
    <x-checkout-heading title="Giỏ hàng của bạn" />

    <div data-cart data-login-url="{{ route('login') }}" class="bg-white">
        <p data-cart-announcement class="sr-only" role="status"></p>
        <div class="mx-auto max-w-7xl px-5 lg:px-8">
            <p data-cart-network-error hidden role="alert" class="mt-4 rounded-md bg-rose-50 px-4 py-3 text-sm text-rose-800">Không cập nhật được giỏ hàng. Vui lòng thử lại.</p>
        </div>
        <div data-cart-content class="mx-auto grid max-w-7xl gap-8 px-5 py-9 lg:grid-cols-[minmax(0,1fr)_minmax(280px,0.50fr)] lg:px-8 lg:py-12">
            <section aria-label="Sản phẩm trong giỏ">
                @if (session('status'))
                    <p role="status" class="mb-4 rounded-md bg-emerald-50 px-4 py-3 text-sm text-emerald-900">{{ session('status') }}</p>
                @endif
                @if ($errors->any())
                    <p role="alert" class="mb-4 rounded-md bg-rose-50 px-4 py-3 text-sm text-rose-800">{{ $errors->first() }}</p>
                @endif

                @if ($items->isEmpty())
                    <p class="rounded-lg border border-[#e8e3db] bg-white p-8 text-center text-sm text-[#756e65]">Giỏ hàng của bạn đang trống.</p>
                @else
                    <div class="overflow-hidden rounded-lg border border-[#e8e3db] bg-white">
                        <div class="hidden grid-cols-[minmax(0,1fr)_7rem_7rem_8rem] gap-3 border-b border-[#e8e3db] bg-[#f4f1ec] px-4 py-3 font-storefront-heading text-sm text-[#1f1d1a] sm:grid">
                            <span>Sản phẩm</span>
                            <span class="text-right">Đơn giá</span>
                            <span class="text-center">Số lượng</span>
                            <span class="pr-6 text-right">Thành tiền</span>
                        </div>
                        <ul class="divide-y divide-[#efebe4]">
                            @foreach ($items as $item)
                                @php($product = $item->product)
                                <li class="grid grid-cols-2 items-center gap-3 px-3 py-4 sm:grid-cols-[minmax(0,1fr)_7rem_7rem_8rem] sm:px-4">
                                    <div class="col-span-2 flex min-w-0 items-center gap-3 sm:col-span-1">
                                        <x-checkout-thumbnail :product="$product" class="h-14 w-14" />
                                        <div class="min-w-0">
                                            @if ($product->is_active && $product->category?->is_active)
                                                <a href="{{ route('products.show', [$product->category, $product]) }}" class="font-storefront-heading text-base text-[#1f1d1a] transition hover:text-[#b89762]">{{ $product->name }}</a>
                                            @else
                                                <p class="font-storefront-heading text-base text-[#1f1d1a]">{{ $product->name }}</p>
                                            @endif
                                            @if ($product->material)
                                                <p class="text-xs text-[#756e65]">{{ $product->material }}</p>
                                            @endif
                                            @unless ($product->canFulfill($item->quantity))
                                                <p class="mt-1 text-xs text-rose-800">Số lượng hiện tại không còn khả dụng.</p>
                                            @endunless
                                        </div>
                                    </div>
                                    <p class="text-sm text-[#1f1d1a] sm:text-right"><span class="sm:sr-only">Đơn giá </span>{{ number_format($product->display_price, 0, ',', '.') }}đ</p>
                                    <div class="justify-self-end sm:justify-self-center">
                                        <form data-cart-form="quantity" action="{{ route('cart.items.update', $item) }}" method="POST" class="inline-flex items-center overflow-hidden rounded border border-[#ddd7cd] bg-white" aria-label="Số lượng {{ $product->name }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" data-cart-action="decrease" name="quantity" value="{{ max(1, min($item->quantity - 1, $product->stock)) }}" @disabled($item->quantity <= 1 || ! $product->canFulfill(1)) class="h-8 w-8 text-[#1f1d1a] hover:bg-[#f4f1ec] disabled:text-[#b0a99f]" aria-label="Giảm số lượng {{ $product->name }}">−</button>
                                            <span class="min-w-8 border-x border-[#ddd7cd] px-2 text-center text-sm leading-8">{{ $item->quantity }}</span>
                                            <button type="submit" data-cart-action="increase" name="quantity" value="{{ $item->quantity + 1 }}" @disabled(! $product->canFulfill($item->quantity + 1)) class="h-8 w-8 text-[#1f1d1a] hover:bg-[#f4f1ec] disabled:text-[#b0a99f]" aria-label="Tăng số lượng {{ $product->name }}">+</button>
                                        </form>
                                    </div>
                                    <div class="col-span-2 flex items-center justify-between gap-2 text-sm sm:col-span-1 sm:justify-end">
                                        <strong class="text-[#9e7d47]"><span class="sm:sr-only">Thành tiền </span>{{ number_format($item->lineTotal(), 0, ',', '.') }}đ</strong>
                                        <form data-cart-form="remove" action="{{ route('cart.items.destroy', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" data-cart-action="remove" title="Xóa sản phẩm" class="grid h-8 w-7 place-items-center rounded text-lg text-[#756e65] hover:bg-[#f4f1ec] hover:text-rose-800" aria-label="Xóa {{ $product->name }} khỏi giỏ">×</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <a data-cart-continue href="{{ route('products.index') }}" class="mt-8 inline-flex items-center gap-2 text-sm text-[#1f1d1a] underline decoration-[#b89762] underline-offset-4 transition hover:text-[#b89762]">
                    <span aria-hidden="true">←</span> Tiếp tục mua hàng
                </a>
            </section>

            <aside class="self-start overflow-hidden rounded-lg border border-[#e8e3db] bg-white" aria-labelledby="cart-total">
                <h2 id="cart-total" class="border-b border-[#e8e3db] bg-[#f4f1ec] px-5 py-3.5 text-center font-storefront-heading text-lg text-[#1f1d1a]">Tổng cộng</h2>
                <div class="flex flex-col gap-4 p-5 sm:p-6">
                    <div class="flex justify-between gap-3 border-b border-[#efebe4] pb-3 text-sm text-[#6e675e]">
                        <span>Tạm tính</span><span class="text-[#1f1d1a]">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="flex items-baseline justify-between gap-3">
                        <span class="font-storefront-heading text-[#1f1d1a]">Tổng thanh toán</span>
                        <strong data-cart-subtotal class="text-lg text-[#9e7d47]">{{ number_format($subtotal, 0, ',', '.') }}đ</strong>
                    </div>
                    @if ($canCheckout)
                        <a data-cart-checkout href="{{ route('checkout.create') }}" class="rounded-md bg-[#23201d] px-4 py-3 text-center text-sm font-medium text-[#faf9f7] transition hover:bg-[#b89762] aria-disabled:opacity-60">Tiến hành thanh toán <span class="ml-2" aria-hidden="true">→</span></a>
                    @else
                        <button type="button" disabled class="rounded-md bg-[#ebe6de] px-4 py-3 text-sm font-medium text-[#8a8278]">Tiến hành thanh toán</button>
                    @endif
                </div>
            </aside>
        </div>
    </div>
@endsection
