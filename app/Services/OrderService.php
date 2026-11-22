<?php

namespace App\Services;

use App\Enum\OrderStatus;
use App\Enum\PaymentStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function quote(User $user, ?Product $product = null, bool $lockProducts = false): Collection
    {
        $quantities = $product === null
            ? ($user->cart()->first()?->items()->orderBy('product_id')->pluck('quantity', 'product_id') ?? collect())
            : collect([$product->id => 1]);

        if ($quantities->isEmpty()) {
            throw ValidationException::withMessages(['checkout' => 'Giỏ hàng đang trống.']);
        }

        $products = Product::query()->with('category')->whereKey($quantities->keys()->all())
            ->orderBy('id')->when($lockProducts, fn ($query) => $query->lockForUpdate())->get()->keyBy('id');

        return $quantities->map(function (int $quantity, int $productId) use ($products): array {
            $item = $products->get($productId);

            if (! $item?->canFulfill($quantity)) {
                throw ValidationException::withMessages([
                    'checkout' => $item
                        ? "Sản phẩm «{$item->name}» không còn bán hoặc không đủ số lượng."
                        : 'Một sản phẩm không còn bán. Vui lòng kiểm tra lại giỏ hàng.',
                ]);
            }

            $price = (int) $item->display_price;

            return [
                'product' => $item,
                'quantity' => $quantity,
                'unit_price' => $price,
                'line_total' => $price * $quantity,
            ];
        });
    }

    public function quoteSignature(Collection $lines): string
    {
        return hash('sha256', json_encode(
            $lines->map(fn (array $line): array => [$line['product']->id, $line['quantity'], $line['unit_price']])
                ->values()->all(),
            JSON_THROW_ON_ERROR,
        ));
    }


    public function placeOrder(User $user, array $data, ?Product $product = null): Order
    {
        return DB::transaction(function () use ($user, $data, $product): Order {
            $user = User::query()->lockForUpdate()->findOrFail($user->id);
            $existingOrder = Order::query()->whereBelongsTo($user)
                ->where('checkout_token', $data['checkout_token'])->first();

            if ($existingOrder !== null) {
                return $existingOrder;
            }

            $lines = $this->quote($user, $product, lockProducts: true);

            if (! hash_equals($this->quoteSignature($lines), $data['quote_signature'])) {
                throw ValidationException::withMessages([
                    'checkout' => 'Sản phẩm, số lượng hoặc giá đã thay đổi. Vui lòng kiểm tra lại trước khi đặt hàng.',
                ]);
            }

            $order = Order::query()->create([
                'order_code' => 'LJ-'.Str::upper(Str::random(12)),
                'checkout_token' => $data['checkout_token'],
                'user_id' => $user->id,
                'customer_name' => $data['customer_name'],
                'customer_email' => $user->email,
                'customer_phone' => $data['customer_phone'],
                'customer_address' => $data['customer_address'],
                'customer_note' => $data['customer_note'] ?? null,
                'total_amount' => $lines->sum('line_total'),
                'payment_method' => $data['payment_method'],
                'payment_status' => PaymentStatus::Unpaid->value,
                'status' => OrderStatus::Pending->value,
            ]);

            foreach ($lines as $line) {
                $item = $line['product'];
                $order->items()->create([
                    'product_id' => $item->id,
                    'product_name' => $item->name,
                    'product_slug' => $item->slug,
                    'product_thumbnail' => $item->thumbnail,
                    'product_material' => $item->material,
                    'unit_price' => $line['unit_price'],
                    'original_price' => $item->price,
                    'quantity' => $line['quantity'],
                    'line_total' => $line['line_total'],
                ]);
                $item->decrement('stock', $line['quantity']);
            }

            if ($product === null) {
                $user->cart()->firstOrFail()->items()->delete();
            }

            return $order;
        }, 3);
    }

    //Lấy đơn hàng ở profile user
    public function recentForUser(User $user, int $limit = 5): EloquentCollection
    {
        return $user->orders()
            ->select(['id', 'order_code', 'status', 'total_amount', 'created_at'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function countForUser(User $user): int
    {
        return $user->orders()->count();
    }
}
