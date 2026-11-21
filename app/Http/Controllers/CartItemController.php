<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function store(StoreCartItemRequest $request, Product $product): RedirectResponse
    {
        $this->cart->addItem($request->user(), $product, $request->integer('quantity'));

        return to_route('cart.show')->with('status', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function update(UpdateCartItemRequest $request, int $item): RedirectResponse
    {
        $this->cart->updateItem($request->user(), $item, $request->integer('quantity'));

        return to_route('cart.show');
    }

    public function destroy(Request $request, int $item): RedirectResponse
    {
        $this->cart->removeItem($request->user(), $item);

        return to_route('cart.show')->with('status', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }
}
