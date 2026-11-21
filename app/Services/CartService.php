<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function getOrCreateForUser(User $user): Cart
    {
        return $user->cart()->firstOrCreate();
    }

    /** @return Collection<int, CartItem> */
    public function items(User $user): Collection
    {
        return $user->cart()->first()?->items()
            ->with('product.category')->orderBy('id')->get()
            ->filter(fn (CartItem $item): bool => $item->product !== null) ?? new Collection;
    }

    public function addItem(User $user, Product $product, int $quantity): CartItem
    {
        return DB::transaction(function () use ($user, $product, $quantity): CartItem {
            $user = User::query()->lockForUpdate()->findOrFail($user->id);
            $cart = $this->getOrCreateForUser($user);
            $item = $cart->items()->firstOrNew(['product_id' => $product->id]);
            $newQuantity = (int) $item->quantity + $quantity;
            $product = Product::query()->with('category')->findOrFail($product->id);
            $this->checkQuantity($product, $newQuantity);

            $item->quantity = $newQuantity;
            $item->save();

            return $item;
        }, 3);
    }

    public function updateItem(User $user, int $itemId, int $quantity): CartItem
    {
        return DB::transaction(function () use ($user, $itemId, $quantity): CartItem {
            $user = User::query()->lockForUpdate()->findOrFail($user->id);
            $item = $user->cart()->firstOrFail()->items()
                ->with('product.category')->findOrFail($itemId);
            $this->checkQuantity($item->product, $quantity);
            $item->update(['quantity' => $quantity]);

            return $item;
        }, 3);
    }

    public function removeItem(User $user, int $itemId): void
    {
        DB::transaction(function () use ($user, $itemId): void {
            $user = User::query()->lockForUpdate()->findOrFail($user->id);
            $user->cart()->firstOrFail()->items()->findOrFail($itemId)->delete();
        }, 3);
    }

    private function checkQuantity(Product $product, int $quantity): void
    {
        if (! $product->canFulfill($quantity)) {
            throw ValidationException::withMessages([
                'quantity' => 'Sản phẩm không còn bán hoặc số lượng vượt quá tồn kho.',
            ]);
        }
    }
}
