<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(private OrderService $orders) {}

    public function create(Request $request, ?Product $product = null): View|RedirectResponse
    {
        try {
            $lines = $this->orders->quote($request->user(), $product);
        } catch (ValidationException $exception) {
            return to_route($product === null ? 'cart.show' : 'products.index')
                ->withErrors($exception->errors());
        }

        return view('checkout.create', [
            'lines' => $lines,
            'subtotal' => $lines->sum('line_total'),
            'customer' => $request->user(),
            'product' => $product,
            'checkoutToken' => Str::uuid()->toString(),
            'quoteSignature' => $this->orders->quoteSignature($lines),
            'bankTransferQr' => config('checkout.bank_transfer_qr'),
        ]);
    }

    public function store(StoreOrderRequest $request, ?Product $product = null): RedirectResponse
    {
        try {
            $order = $this->orders->placeOrder($request->user(), $request->validated(), $product);
        } catch (ValidationException $exception) {
            return to_route($product === null ? 'checkout.create' : 'checkout.buy', $product ?? [])
                ->withErrors($exception->errors())
                ->withInput();
        }

        return to_route('checkout.complete', $order->order_code);
    }

    public function show(Request $request, string $orderCode): View
    {
        $order = Order::query()->whereBelongsTo($request->user())
            ->where('order_code', $orderCode)->firstOrFail();

        return view('checkout.complete', compact('order'));
    }
}
