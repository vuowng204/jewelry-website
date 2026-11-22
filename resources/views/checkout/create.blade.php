@extends('layouts.layoutmaster')

@section('title', 'Thanh toán | Luna Jewelry')

@section('content')
    <x-checkout-heading title="Thanh toán" checkout :from-cart="$product === null" />

    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-5 py-9 lg:px-8 lg:py-12">
            @if ($errors->any())
                <ul role="alert" class="mb-6 flex flex-col gap-1 rounded-md bg-rose-50 px-4 py-3 text-sm text-rose-800">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ $product ? route('checkout.buy.store', $product) : route('checkout.store') }}"
                class="grid items-start gap-7 md:grid-cols-2 lg:grid-cols-[minmax(0,1.12fr)_minmax(0,0.9fr)_minmax(0,1.05fr)]"
                x-data="{ payment: @js(old('payment_method', 'cod')) }">
                @csrf
                <input type="hidden" name="checkout_token" value="{{ $checkoutToken }}">
                <input type="hidden" name="quote_signature" value="{{ $quoteSignature }}">

                <section aria-labelledby="shipping-title" class="rounded-lg border border-[#e8e3db] bg-white p-6">
                    <h2 id="shipping-title" class="border-b border-[#efebe4] pb-3 font-storefront-heading text-xl text-[#1f1d1a]">Thông tin giao hàng</h2>
                    @php($inputClass = 'w-full rounded-md border border-[#ddd7cd] bg-white px-3 py-2.5 text-sm text-[#1f1d1a] placeholder:text-[#9c958b] focus:border-[#b89762] focus:ring-[#b89762]')
                    <div class="mt-4 flex flex-col gap-4">
                        <label class="flex flex-col gap-1.5 text-sm text-[#4a453e]">
                            Họ và tên <span class="sr-only">(bắt buộc)</span>
                            <input name="customer_name" type="text" value="{{ old('customer_name', $customer->name) }}" autocomplete="name" maxlength="255" placeholder="Nhập họ và tên" required class="{{ $inputClass }}">
                        </label>
                        <label class="flex flex-col gap-1.5 text-sm text-[#4a453e]">
                            Số điện thoại <span class="sr-only">(bắt buộc)</span>
                            <input name="customer_phone" type="tel" value="{{ old('customer_phone') }}" autocomplete="tel" maxlength="21" placeholder="Nhập số điện thoại" required class="{{ $inputClass }}">
                        </label>
                        <label class="flex flex-col gap-1.5 text-sm text-[#4a453e]">
                            Địa chỉ nhận hàng <span class="sr-only">(bắt buộc)</span>
                            <input name="customer_address" type="text" value="{{ old('customer_address') }}" autocomplete="street-address" maxlength="1000" placeholder="Số nhà, tên đường, phường/xã, tỉnh/thành" required class="{{ $inputClass }}">
                        </label>
                        <label class="flex flex-col gap-1.5 text-sm text-[#4a453e]">
                            Ghi chú (tùy chọn)
                            <textarea name="customer_note" rows="3" maxlength="2000" placeholder="Ghi chú đơn hàng của bạn" class="{{ $inputClass }}">{{ old('customer_note') }}</textarea>
                        </label>
                    </div>
                </section>

                <fieldset class="min-w-0 rounded-lg border border-[#e8e3db] bg-white p-6">
                    <legend class="sr-only">Phương thức thanh toán</legend>
                    <h2 class="border-b border-[#efebe4] pb-3 font-storefront-heading text-xl text-[#1f1d1a]">Phương thức thanh toán</h2>
                    <div class="mt-4 flex flex-col gap-3">
                        <label class="flex cursor-pointer items-start gap-3 rounded-md border border-[#ddd7cd] px-3.5 py-3 transition has-[:checked]:border-[#b89762] has-[:checked]:bg-[#faf8f4]">
                            <input type="radio" name="payment_method" value="cod" x-model="payment" @checked(old('payment_method', 'cod') === 'cod') required class="mt-1 text-[#b89762] focus:ring-[#b89762]">
                            <span class="flex flex-col gap-1 text-sm">
                                <strong class="text-[#1f1d1a]">Thanh toán khi nhận hàng (COD)</strong>
                                <span class="text-xs text-[#756e65]">Thanh toán trực tiếp khi nhận đơn hàng.</span>
                            </span>
                        </label>
                        <label class="flex cursor-pointer items-start gap-3 rounded-md border border-[#ddd7cd] px-3.5 py-3 transition has-[:checked]:border-[#b89762] has-[:checked]:bg-[#faf8f4]">
                            <input type="radio" name="payment_method" value="bank_transfer" x-model="payment" @checked(old('payment_method') === 'bank_transfer') class="mt-1 text-[#b89762] focus:ring-[#b89762]">
                            <span class="flex flex-col gap-1 text-sm">
                                <strong class="text-[#1f1d1a]">Chuyển khoản ngân hàng</strong>
                                <span class="text-xs text-[#756e65]">Cửa hàng xác nhận sau khi đối chiếu giao dịch.</span>
                            </span>
                        </label>
                    </div>

                    <div x-show="payment === 'bank_transfer'" x-cloak class="mt-4 overflow-hidden rounded-md border border-[#e8e3db] text-center">
                        @if ($bankTransferQr)
                            <img src="{{ asset($bankTransferQr) }}" alt="Mã QR chuyển khoản của cửa hàng" class="mx-auto w-full max-w-48 p-3">
                            <p class="bg-[#f4f1ec] px-3 py-2 text-xs text-[#6e675e]">Quét mã QR và chuyển đúng tổng tiền của đơn hàng.</p>
                        @else
                            <p class="bg-[#f4f1ec] px-3 py-3 text-sm text-[#6e675e]">Cửa hàng sẽ gửi thông tin chuyển khoản khi xác nhận đơn.</p>
                        @endif
                    </div>
                </fieldset>

                <aside class="overflow-hidden rounded-lg border border-[#e8e3db] bg-white md:col-span-2 lg:col-span-1" aria-labelledby="order-summary">
                    <h2 id="order-summary" class="border-b border-[#e8e3db] bg-[#f4f1ec] px-5 py-3.5 text-center font-storefront-heading text-lg text-[#1f1d1a]">Đơn hàng của bạn</h2>
                    <div class="flex flex-col gap-4 px-5 py-4">
                        <ul class="divide-y divide-[#efebe4]">
                            @foreach ($lines as $line)
                                <li class="flex items-center gap-3 py-3">
                                    <x-checkout-thumbnail :product="$line['product']" class="h-12 w-12" />
                                    <div class="min-w-0 flex-1">
                                        <p class="font-storefront-heading text-sm text-[#1f1d1a]">{{ $line['product']->name }}</p>
                                        <p class="text-xs text-[#756e65]">×{{ $line['quantity'] }}</p>
                                    </div>
                                    <strong class="shrink-0 text-xs text-[#9e7d47]">{{ number_format($line['line_total'], 0, ',', '.') }}đ</strong>
                                </li>
                            @endforeach
                        </ul>
                        <div class="flex justify-between gap-3 border-y border-[#efebe4] py-3 text-sm text-[#6e675e]">
                            <span>Tạm tính</span><span class="text-[#1f1d1a]">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="flex items-baseline justify-between gap-3">
                            <span class="font-storefront-heading text-[#1f1d1a]">Tổng thanh toán</span>
                            <strong class="text-lg text-[#9e7d47]">{{ number_format($subtotal, 0, ',', '.') }}đ</strong>
                        </div>
                        <button type="submit" class="rounded-md bg-[#23201d] px-4 py-3 text-sm font-medium text-[#faf9f7] transition hover:bg-[#b89762]">
                            Đặt hàng <span class="ml-2" aria-hidden="true">→</span>
                        </button>
                    </div>
                </aside>
            </form>
        </div>
    </div>
@endsection
