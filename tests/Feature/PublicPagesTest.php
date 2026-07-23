<?php

use App\Models\CommentModel;
use App\Models\PostModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('affiche la page d\'accueil', function () {
    $this->get('/')->assertOk();
});

it('affiche la liste des articles du blog', function () {
    $admin = User::factory()->admin()->create();

    PostModel::create([
        'title' => 'Mon premier article',
        'lead' => 'Le chapeau de l\'article.',
        'content' => 'Le contenu complet de l\'article.',
        'author_id' => $admin->user_id,
    ]);

    $this->get('/blog')
        ->assertOk()
        ->assertSee('Mon premier article');
});

it('affiche un article avec ses commentaires approuvés uniquement', function () {
    $admin = User::factory()->admin()->create();
    $reader = User::factory()->create();

    $post = PostModel::create([
        'title' => 'Article commenté',
        'lead' => 'Le chapeau.',
        'content' => 'Le contenu.',
        'author_id' => $admin->user_id,
    ]);

    CommentModel::create([
        'content' => 'Commentaire approuvé',
        'post_id' => $post->post_id,
        'author_id' => $reader->user_id,
        'visible' => true,
    ]);

    CommentModel::create([
        'content' => 'Commentaire en attente',
        'post_id' => $post->post_id,
        'author_id' => $reader->user_id,
        'visible' => false,
    ]);

    $this->get('/blog/' . $post->post_id)
        ->assertOk()
        ->assertSee('Commentaire approuvé')
        ->assertDontSee('Commentaire en attente');
});

it('affiche la boutique', function () {
    $this->get('/shop')->assertOk();
});
