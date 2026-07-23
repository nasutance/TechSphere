<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('inscrit un nouvel utilisateur et le connecte', function () {
    $response = $this->post('/register', [
        'username' => 'jdupont',
        'email' => 'jean.dupont@example.com',
        'password' => 'motdepasse',
        'password_confirmation' => 'motdepasse',
        'nom' => 'Dupont',
        'prenom' => 'Jean',
        'adresse' => '1 rue de la Paix, Paris',
        'code_postal' => '75001',
        'date_de_naissance' => '1995-04-12',
    ]);

    $response->assertRedirect('/');
    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', ['username' => 'jdupont', 'role' => 'client']);
});

it('connecte un utilisateur avec les bons identifiants', function () {
    $user = User::factory()->create(['username' => 'demo']);

    $this->post('/login', ['username' => 'demo', 'password' => 'password'])
        ->assertRedirect('/');

    $this->assertAuthenticatedAs($user);
});

it('rejette de mauvais identifiants', function () {
    User::factory()->create(['username' => 'demo']);

    $this->post('/login', ['username' => 'demo', 'password' => 'mauvais-mot-de-passe'])
        ->assertSessionHasErrors('username');

    $this->assertGuest();
});

it('refuse la connexion d\'un compte bloqué', function () {
    User::factory()->create(['username' => 'bloque', 'role' => 'blocked']);

    $this->post('/login', ['username' => 'bloque', 'password' => 'password'])
        ->assertSessionHasErrors('username');

    $this->assertGuest();
});
