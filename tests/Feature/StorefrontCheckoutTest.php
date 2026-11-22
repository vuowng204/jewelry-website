<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_shopping_requires_login_and_empty_cart_cannot_checkout(): void
    {
        $product = $this->product('ring');

        $this->get(route('cart.show'))->assertRedirect(route('login'));
        $this->get(route('checkout.buy', $product))->assertRedirect(route('login'));
        $this->assertSame(route('checkout.buy', $product), session('url.intended'));
        $this->post(route('cart.items.store', $product), ['quantity' => 1])->assertRedirect(route('login'));
        $this->assertDatabaseEmpty('carts');

        $this->actingAs($this->customer())->get(route('checkout.create'))
            ->assertRedirect(route('cart.show'))->assertSessionHasErrors('checkout');
    }

    public function test_cart_accumulates_items_and_quantity_edits_and_removals_are_scoped(): void
    {
        $product = $this->product('ring');
        $customer = $this->customer();

        $this->actingAs($customer)->post(route('cart.items.store', $product), ['quantity' => 1])
            ->assertRedirect(route('cart.show'));
        $this->post(route('cart.items.store', $product), ['quantity' => 1])->assertRedirect(route('cart.show'));
        $item = CartItem::query()->sole();
        $this->assertSame(2, $item->quantity);
        $this->get(route('cart.show'))->assertOk()->assertSee($product->name)->assertSee('100.000đ');
        $this->post(route('cart.items.store', $product), ['quantity' => 10])->assertSessionHasErrors('quantity');
        $this->patch(route('cart.items.update', $item), ['quantity' => 0])->assertSessionHasErrors('quantity');

        $this->actingAs($this->customer('other@example.test'));
        $this->get(route('cart.show'))->assertOk()->assertDontSee($product->name);
        $this->patch(route('cart.items.update', $item), ['quantity' => 3])->assertNotFound();
        $this->delete(route('cart.items.destroy', $item))->assertNotFound();
        $this->assertSame(2, $item->fresh()->quantity);

        $this->actingAs($customer)->patch(route('cart.items.update', $item), ['quantity' => 3])
            ->assertRedirect(route('cart.show'));
        $this->assertSame(3, $item->fresh()->quantity);
        $this->delete(route('cart.items.destroy', $item))->assertRedirect(route('cart.show'));
        $this->assertDatabaseEmpty('cart_items');
    }

    public function test_cart_checkout_saves_one_order_at_the_server_price_and_clears_the_cart(): void
    {
        $customer = $this->customer();
        $product = $this->product('ring');
        $this->cartItem($customer, $product, 2);
        $this->actingAs($customer);

        $checkout = $this->get(route('checkout.create'))->assertOk()->assertSee('100.000đ');
        $data = $this->checkoutData($checkout->viewData('checkoutToken'), $checkout->viewData('quoteSignature'));
        $data['total_amount'] = 1;
        $this->post(route('checkout.store'), $data)->assertRedirect();

        $order = Order::query()->sole();
        $this->post(route('checkout.store'), $data)->assertRedirect(route('checkout.complete', $order->order_code));
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id, 'total_amount' => 100000, 'payment_status' => 'unpaid', 'status' => 'pending',
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id, 'product_id' => $product->id, 'quantity' => 2, 'unit_price' => 50000,
        ]);
        $this->assertDatabaseEmpty('cart_items');
        $this->assertEquals(3, $product->fresh()->stock);
        $this->get(route('checkout.complete', $order->order_code))->assertOk()->assertSee($order->order_code);
        $this->actingAs($this->customer('other@example.test'))
            ->get(route('checkout.complete', $order->order_code))->assertNotFound();
    }

    public function test_buy_now_orders_only_the_selected_product_and_preserves_cart(): void
    {
        $customer = $this->customer();
        $inCart = $this->product('ring');
        $item = $this->cartItem($customer, $inCart, 2);
        $direct = $this->product('necklace');
        $this->actingAs($customer);

        $checkout = $this->get(route('checkout.buy', $direct))->assertOk()
            ->assertSee($direct->name)->assertDontSee($inCart->name);
        $data = $this->checkoutData($checkout->viewData('checkoutToken'), $checkout->viewData('quoteSignature'));
        $data['payment_method'] = 'bank_transfer';
        $this->post(route('checkout.buy.store', $direct), $data)->assertRedirect();

        $order = Order::query()->sole();
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'payment_method' => 'bank_transfer', 'payment_status' => 'unpaid']);
        $this->assertDatabaseHas('order_items', ['order_id' => $order->id, 'product_id' => $direct->id, 'quantity' => 1]);
        $this->assertDatabaseCount('order_items', 1);
        $this->assertSame(2, $item->fresh()->quantity);
    }

    public function test_checkout_requires_shipping_details_and_rejects_changed_price_or_stock(): void
    {
        $customer = $this->customer();
        $product = $this->product('ring');
        $item = $this->cartItem($customer, $product, 2);
        $checkout = $this->actingAs($customer)->get(route('checkout.create'))->assertOk();
        $data = $this->checkoutData($checkout->viewData('checkoutToken'), $checkout->viewData('quoteSignature'));
        $this->post(route('checkout.store'), array_replace($data, ['customer_phone' => 'invalid']))
            ->assertSessionHasErrors('customer_phone');

        $product->update(['sale_price' => 45000]);
        $this->post(route('checkout.store'), $data)->assertSessionHasErrors('checkout');
        $product->update(['stock' => 1]);
        $this->post(route('checkout.store'), $data)->assertSessionHasErrors('checkout');
        $this->assertDatabaseEmpty('orders');
        $this->assertSame(2, $item->fresh()->quantity);
    }

    private function customer(string $email = 'shopper@example.test'): User
    {
        return User::query()->create(['name' => 'Test Shopper', 'email' => $email, 'password' => 'test-password']);
    }

    private function product(string $slug): Product
    {
        $category = Category::query()->firstOrCreate(['slug' => 'jewelry'], ['name' => 'Jewelry']);

        return $category->products()->create([
            'name' => 'Product '.ucfirst($slug),
            'slug' => $slug,
            'price' => 70000,
            'sale_price' => 50000,
            'stock' => 5,
        ]);
    }

    private function cartItem(User $customer, Product $product, int $quantity): CartItem
    {
        $cart = Cart::factory()->for($customer)->create();

        return CartItem::factory()->for($cart)->for($product)->create(['quantity' => $quantity]);
    }

    private function checkoutData(string $token, string $signature): array
    {
        return [
            'checkout_token' => $token,
            'quote_signature' => $signature,
            'customer_name' => 'Test Shopper',
            'customer_phone' => '0900000000',
            'customer_address' => 'Test address',
            'payment_method' => 'cod',
        ];
    }
}
