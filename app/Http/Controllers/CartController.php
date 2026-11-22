<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function show(Request $request, CartService $cart): View
    {
        $items = $cart->items($request->user());

        return view('cart.show', [
            'items' => $items,
            'subtotal' => $items->sum(fn (CartItem $item): int => $item->lineTotal()),
            'canCheckout' => $items->isNotEmpty() && $items->every(
                fn (CartItem $item): bool => $item->product->canFulfill($item->quantity),
            ),
        ]);
    }
}
