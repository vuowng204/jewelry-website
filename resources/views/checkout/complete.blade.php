@extends('layouts.layoutmaster')

@section('title', 'Đặt hàng thành công | Luna Jewelry')

@section('content')
    <div class="bg-[#f5efe6] px-5 py-8 text-center sm:py-10">
        <h1 class="font-storefront-heading text-3xl font-medium sm:text-4xl">Thanh toán</h1>
    </div>

    <div class="mx-auto flex max-w-lg flex-col items-center gap-4 px-5 py-10 sm:py-14">
        <section class="flex w-full flex-col items-center gap-4 rounded-lg border border-[#e7ddd0] px-6 py-10 text-center sm:px-10 sm:py-14">
            <span class="grid h-16 w-16 place-items-center rounded-full bg-green-100 text-3xl font-semibold text-green-800" aria-hidden="true">✓</span>
            <h2 class="font-storefront-heading text-2xl">Đặt hàng thành công</h2>
            <p class="text-sm text-[#796652]">Đơn <strong class="text-[#342821]">{{ $order->order_code }}</strong> đang chờ cửa hàng xác nhận.</p>
            @if ($order->payment_method === \App\Enum\PaymentMethod::BankTransfer->value)
                <p class="text-sm text-[#796652]">Cửa hàng sẽ xác nhận thanh toán sau khi đối chiếu chuyển khoản.</p>
            @endif
            <a href="{{ route('home.index') }}" class="mt-2 w-full rounded-md bg-[#342821] px-4 py-3 text-sm font-semibold text-white hover:bg-[#614a36]">
                Quay về trang chủ <span class="ml-2" aria-hidden="true">→</span>
            </a>
        </section>
    </div>
@endsection
