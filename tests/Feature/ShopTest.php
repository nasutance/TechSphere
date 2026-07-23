<?php

use App\Models\CategoryModel;
use App\Models\OrderModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('ajoute un article au panier', function () {
    $user = User::factory()->create();
    $category = CategoryModel::create(['name' => 'Composants']);
    $item = $category->items()->create(['name' => 'SSD NVMe 1 To', 'price' => 89.90]);

    $this->actingAs($user)
        ->put(route('cart.update', $item->item_id), ['quantity' => 1])
        ->assertRedirect(route('shop.cart'));

    expect(session('cart'))->toHaveKey($item->item_id);
});

it('transforme le panier en commande', function () {
    $user = User::factory()->create();
    $category = CategoryModel::create(['name' => 'Composants']);
    $item = $category->items()->create(['name' => 'SSD NVMe 1 To', 'price' => 100]);

    $this->actingAs($user)
        ->withSession(['cart' => [
            $item->item_id => ['name' => $item->name, 'price' => 100, 'quantity' => 2],
        ]])
        ->post(route('shop.checkout'))
        ->assertRedirect();

    $this->assertDatabaseHas('orders', ['user_id' => $user->user_id, 'total' => 200]);
    $this->assertDatabaseHas('order_items', ['item_id' => $item->item_id, 'quantity' => 2]);
});

it('empêche de consulter la commande d\'un autre utilisateur', function () {
    $proprietaire = User::factory()->create();
    $autre = User::factory()->create();

    $order = OrderModel::create(['user_id' => $proprietaire->user_id, 'total' => 50]);

    $this->actingAs($autre)
        ->get(route('shop.order', $order->order_id))
        ->assertForbidden();
});

it('laisse un administrateur consulter toutes les commandes', function () {
    $proprietaire = User::factory()->create();
    $admin = User::factory()->admin()->create();

    $order = OrderModel::create(['user_id' => $proprietaire->user_id, 'total' => 50]);

    $this->actingAs($admin)
        ->get(route('shop.order', $order->order_id))
        ->assertOk();
});
