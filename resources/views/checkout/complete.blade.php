@extends('layouts.layoutmaster')

@section('title', 'Đặt hàng thành công | Luna Jewelry')

@section('content')
    <div class="border-b border-[#efebe4] bg-[#f8f6f2] px-5 py-8 text-center sm:py-10">
        <h1 class="font-storefront-heading text-3xl font-medium text-[#1c1714] sm:text-4xl">Thanh toán</h1>
    </div>

    <div class="bg-white">
        <div class="mx-auto flex max-w-lg flex-col items-center gap-4 px-5 py-10 sm:py-14">
            <section class="flex w-full flex-col items-center gap-4 rounded-xl border border-[#e8e3db] bg-white px-6 py-10 text-center sm:px-10 sm:py-14">
                <span class="grid h-16 w-16 place-items-center rounded-full bg-emerald-50 text-3xl font-semibold text-emerald-700" aria-hidden="true">✓</span>
                <h2 class="font-storefront-heading text-2xl text-[#1f1d1a]">Đặt hàng thành công</h2>
                <p class="text-sm text-[#6e675e]">Đơn <strong class="text-[#1f1d1a]">{{ $order->order_code }}</strong> đang chờ cửa hàng xác nhận.</p>
                @if ($order->payment_method === \App\Enum\PaymentMethod::BankTransfer->value)
                    <p class="text-sm text-[#6e675e]">Cửa hàng sẽ xác nhận thanh toán sau khi đối chiếu chuyển khoản.</p>
                @endif
                <a href="{{ route('home.index') }}" class="mt-2 w-full rounded-md bg-[#23201d] px-4 py-3 text-sm font-medium text-[#faf9f7] transition hover:bg-[#b89762]">
                    Quay về trang chủ <span class="ml-2" aria-hidden="true">→</span>
                </a>
            </section>
        </div>
    </div>
@endsection
