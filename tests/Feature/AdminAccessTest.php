<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirige les visiteurs du tableau de bord vers la connexion', function () {
    $this->get('/admin')->assertRedirect(route('login'));
});

it('refuse le tableau de bord aux clients', function () {
    $this->actingAs(User::factory()->create())
        ->get('/admin')
        ->assertForbidden();
});

it('autorise le tableau de bord aux administrateurs', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->get('/admin')
        ->assertOk();
});

it('empêche un client de bloquer un utilisateur', function () {
    $client = User::factory()->create();
    $cible = User::factory()->create();

    $this->actingAs($client)
        ->put(route('admin.blockUser', $cible->user_id))
        ->assertForbidden();

    expect($cible->fresh()->role)->toBe('client');
});

it('permet à un administrateur de bloquer un utilisateur', function () {
    $admin = User::factory()->admin()->create();
    $cible = User::factory()->create();

    $this->actingAs($admin)
        ->put(route('admin.blockUser', $cible->user_id))
        ->assertRedirect(route('admin.index'));

    expect($cible->fresh()->role)->toBe('blocked');
});
