@extends('layouts.layoutmaster')

@section('title', 'Tài khoản của tôi | Luna Jewelry')

@section('content')
    <section class="bg-white py-10 lg:py-14">
        <div class="mx-auto max-w-5xl px-5 lg:px-8">
            <nav aria-label="Breadcrumb" class="mb-4 text-xs text-[#8a8278]">
                <a href="{{ route('home.index') }}" class="transition hover:text-[#1f1d1a]">
                    Trang chủ
                </a>
                <span class="mx-2 text-[#d5cfc4]" aria-hidden="true">/</span>
                <span class="text-[#1f1d1a]">Tài khoản của tôi</span>
            </nav>

            <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
                <h1 class="font-storefront-heading text-3xl font-normal tracking-tight text-[#1f1d1a] sm:text-4xl">
                    Tài khoản của tôi
                </h1>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="border border-[#ddd7cd] bg-white px-4 py-2 text-xs font-medium uppercase tracking-[0.12em] text-[#6e675e] transition hover:border-[#23201d] hover:text-[#1f1d1a]"
                    >
                        Đăng xuất
                    </button>
                </form>
            </div>

            <div class="flex flex-col gap-6">
                {{-- Card 1: Thông tin đăng nhập --}}
                <div
                    class="rounded-xl border border-[#e8e3db] bg-white p-6 sm:p-8"
                    x-data="{ showPasswordForm: {{ $errors->updatePassword->isNotEmpty() ? 'true' : 'false' }} }"
                >
                    <h2 class="font-storefront-heading text-xl font-medium text-[#1f1d1a]">
                        Thông tin đăng nhập
                    </h2>

                    @if (session('status') === 'password-updated')
                        <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                            Mật khẩu của bạn đã được cập nhật thành công.
                        </div>
                    @endif

                    <div class="mt-6 grid grid-cols-1 gap-x-10 gap-y-6 sm:grid-cols-2">
                        <div class="flex flex-col gap-5">
                            <div>
                                <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-[#9e7d47]">
                                    Họ và tên
                                </p>
                                <p class="mt-1.5 text-sm font-medium text-[#1f1d1a]">
                                    {{ $user->name }}
                                </p>
                            </div>

                            <div>
                                <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-[#9e7d47]">
                                    Email
                                </p>
                                <p class="mt-1.5 text-sm font-medium text-[#1f1d1a]">
                                    {{ $user->email }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-[#9e7d47]">
                                Mật khẩu
                            </p>
                            <div class="mt-1.5 flex flex-wrap items-center gap-4">
                                <p class="text-sm font-medium tracking-[0.2em] text-[#1f1d1a]" aria-label="Mật khẩu đã được ẩn">
                                    ••••••••••
                                </p>
                                <button
                                    type="button"
                                    @click="showPasswordForm = !showPasswordForm"
                                    class="text-xs font-medium text-[#b89762] underline underline-offset-4 transition hover:text-[#1f1d1a]"
                                    x-text="showPasswordForm ? 'Đóng' : 'Đổi mật khẩu'"
                                ></button>
                            </div>
                        </div>
                    </div>

                    {{--Đổi mật khẩu--}}
                    <form
                        method="POST"
                        action="{{ route('password.update') }}"
                        x-show="showPasswordForm"
                        x-cloak
                        class="mt-6 border-t border-[#f0ece5] pt-6"
                    >
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <div>
                                <label for="current_password" class="block text-[11px] font-medium uppercase tracking-[0.14em] text-[#8a8278]">
                                    Mật khẩu hiện tại
                                </label>
                                <input
                                    id="current_password"
                                    name="current_password"
                                    type="password"
                                    autocomplete="current-password"
                                    required
                                    class="mt-1.5 w-full rounded-md border border-[#ddd7cd] bg-white px-3 py-2 text-sm text-[#1f1d1a] focus:border-[#b89762] focus:outline-none focus:ring-1 focus:ring-[#b89762]"
                                >
                                @if ($errors->updatePassword->has('current_password'))
                                    <p class="mt-1 text-xs text-rose-600">
                                        {{ $errors->updatePassword->first('current_password') }}
                                    </p>
                                @endif
                            </div>

                            <div>
                                <label for="password" class="block text-[11px] font-medium uppercase tracking-[0.14em] text-[#8a8278]">
                                    Mật khẩu mới
                                </label>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    autocomplete="new-password"
                                    required
                                    class="mt-1.5 w-full rounded-md border border-[#ddd7cd] bg-white px-3 py-2 text-sm text-[#1f1d1a] focus:border-[#b89762] focus:outline-none focus:ring-1 focus:ring-[#b89762]"
                                >
                                @if ($errors->updatePassword->has('password'))
                                    <p class="mt-1 text-xs text-rose-600">
                                        {{ $errors->updatePassword->first('password') }}
                                    </p>
                                @endif
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-[11px] font-medium uppercase tracking-[0.14em] text-[#8a8278]">
                                    Nhập lại mật khẩu mới
                                </label>
                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    autocomplete="new-password"
                                    required
                                    class="mt-1.5 w-full rounded-md border border-[#ddd7cd] bg-white px-3 py-2 text-sm text-[#1f1d1a] focus:border-[#b89762] focus:outline-none focus:ring-1 focus:ring-[#b89762]"
                                >
                                @if ($errors->updatePassword->has('password_confirmation'))
                                    <p class="mt-1 text-xs text-rose-600">
                                        {{ $errors->updatePassword->first('password_confirmation') }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <button
                                type="submit"
                                class="bg-[#23201d] px-5 py-2.5 text-xs font-medium uppercase tracking-[0.12em] text-[#faf9f7] transition hover:bg-[#b89762]"
                            >
                                Lưu mật khẩu mới
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Card 2: Đơn hàng gần đây --}}
                <div class="rounded-xl border border-[#e8e3db] bg-white p-6 sm:p-8" id="recent-orders-card">
                    <h2 class="font-storefront-heading text-xl font-medium text-[#1f1d1a]">
                        Đơn hàng gần đây
                    </h2>

                    @if ($orders->isEmpty())
                        <div class="mt-6 border-t border-[#f0ece5] py-10 text-center">
                            <p class="text-sm text-[#756e65]">
                                Bạn chưa có đơn hàng nào.
                            </p>
                            <a
                                href="{{ route('products.index') }}"
                                class="mt-4 inline-flex items-center bg-[#23201d] px-5 py-2.5 text-xs font-medium uppercase tracking-[0.12em] text-[#faf9f7] transition hover:bg-[#b89762]"
                            >
                                Khám phá sản phẩm
                            </a>
                        </div>
                    @else
                        <div class="mt-6 overflow-x-auto">
                            <table class="w-full min-w-[540px] border-collapse text-left text-sm">
                                <thead>
                                    <tr class="border-b border-[#ece7df] text-xs font-medium text-[#8a8278]">
                                        <th class="pb-3.5 pr-4 font-medium">Mã đơn hàng</th>
                                        <th class="px-4 pb-3.5 font-medium">Ngày đặt hàng</th>
                                        <th class="px-4 pb-3.5 text-right font-medium">Trạng thái</th>
                                        <th class="pb-3.5 pl-4 text-right font-medium">Tổng tiền</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#f2eee7]">
                                    @php
                                        $statusBadgeClasses = [
                                            'warning' => 'bg-amber-100 text-amber-800',
                                            'info' => 'bg-sky-100 text-sky-700',
                                            'primary' => 'bg-purple-100 text-purple-700',
                                            'success' => 'bg-emerald-100 text-emerald-700',
                                            'danger' => 'bg-rose-100 text-rose-700',
                                            'gray' => 'bg-stone-200 text-stone-700',
                                        ];
                                    @endphp
                                    @foreach ($orders as $order)
                                        @php
                                            $statusEnum = \App\Enum\OrderStatus::tryFrom($order->status);
                                            $statusLabel = $statusEnum?->getLabel() ?? $order->status;
                                            $statusClass = $statusBadgeClasses[$statusEnum?->getColor() ?? 'gray'] ?? 'bg-stone-100 text-stone-700';
                                        @endphp
                                        <tr class="transition hover:bg-[#faf9f7]">
                                            <td class="py-4 pr-4 font-medium text-[#1f1d1a]">
                                                <a
                                                    href="{{ route('checkout.complete', $order->order_code) }}"
                                                    class="transition hover:text-[#b89762]"
                                                >
                                                    #{{ $order->order_code }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-4 text-[#6e675e]">
                                                {{ $order->created_at?->format('d/m/Y') }}
                                            </td>
                                            <td class="px-4 py-4 text-right">
                                                <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-medium {{ $statusClass }}">
                                                    {{ $statusLabel }}
                                                </span>
                                            </td>
                                            <td class="py-4 pl-4 text-right font-medium text-[#9e7d47]">
                                                {{ number_format((float) $order->total_amount, 0, ',', '.') }}đ
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($hasMoreOrders)
                            <div class="mt-6 flex justify-end">
                                <a
                                    href="{{ route('profile.edit', ['orders_limit' => $nextOrdersLimit]) }}"
                                    class="text-xs font-semibold text-[#1f1d1a] transition hover:text-[#b89762]"
                                >
                                    Hiển thị thêm đơn hàng
                                </a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
