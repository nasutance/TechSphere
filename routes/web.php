<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AdminController;

// Route de la page d'accueil
Route::get('/', function () {
    return view('Home'); // Retourne la vue 'Home'
});

// Route de la page d'inscription
Route::get('/Register', function () {
    return view('Register'); // Retourne la vue 'Register'
});

// Route de la page du blog
Route::get('/Blog', function () {
    return view('Blog'); // Retourne la vue 'Blog'
});

/* Routes pour le shop */
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index'); // Affiche la page d'accueil du shop
Route::put('/shop/cart/{itemId}', [ShopController::class, 'updateCartItem'])->middleware('auth')->name('cart.update'); // Met à jour un article dans le panier
Route::get('/shop/cart', [ShopController::class, 'cart'])->name('shop.cart'); // Affiche le panier
Route::post('/shop/checkout', [ShopController::class, 'checkout'])->middleware('auth')->name('shop.checkout'); // Effectue le paiement
Route::get('/shop/orders', [ShopController::class, 'orders'])->middleware('auth')->name('shop.orders'); // Affiche les commandes de l'utilisateur
Route::get('/shop/order/{orderId}', [ShopController::class, 'showOrder'])->middleware('auth')->name('shop.order'); // Affiche une commande spécifique
Route::post('/shop/clear-cart', [ShopController::class, 'clearCart'])->middleware('auth')->name('cart.clear'); // Vide le panier
Route::post('/shop/add-item', [ShopController::class, 'addItem'])->middleware('auth')->name('shop.addItem'); // Ajoute un article au shop
Route::delete('/shop/delete-item', [ShopController::class, 'deleteItem'])->middleware('auth')->name('shop.deleteItem'); // Supprime un article du shop
Route::get('/shop/orders/{userId}', [ShopController::class, 'userOrders'])->middleware('auth')->name('shop.userOrders'); // Affiche les commandes d'un utilisateur

/* Routes pour le blog */
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index'); // Affiche la liste des posts du blog
Route::get('/blog/{id}', [BlogController::class, 'showPost'])->name('blog.show'); // Affiche un post spécifique
Route::get('/search', [BlogController::class, 'search'])->name('blog.search'); // Effectue une recherche dans le blog
Route::put('/blog/{id}', [BlogController::class, 'update'])->name('post.update'); // Met à jour un post

Route::post('/blog/store', [BlogController::class, 'store'])->middleware('auth')->name('blog.store'); // Ajoute un nouveau post
Route::delete('/blog/delete', [BlogController::class, 'deletePost'])->middleware('auth')->name('blog.delete'); // Supprime un post

// Groupe de routes nécessitant l'authentification
Route::middleware(['auth'])->group(function () {
    // Routes pour l'admin
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index'); // Affiche le tableau de bord admin
    Route::post('/admin/create-post', [AdminController::class, 'createPost'])->name('admin.createPost'); // Crée un nouveau post admin
    Route::get('/admin/user/{id}', [AdminController::class, 'viewUser'])->name('admin.viewUser'); // Affiche les informations d'un utilisateur
    Route::get('/admin/user/{id}/orders', [AdminController::class, 'viewUserOrders'])->name('admin.viewUserOrders'); // Affiche les commandes d'un utilisateur
    Route::get('/admin/user/{id}/comments', [AdminController::class, 'viewUserComments'])->name('admin.viewUserComments'); // Affiche les commentaires d'un utilisateur
    Route::post('/admin/add-item', [AdminController::class, 'addItem'])->name('admin.addItem'); // Ajoute un article admin
    Route::delete('/admin/delete-item/{id}', [AdminController::class, 'deleteItem'])->name('admin.deleteItem'); // Supprime un article admin

    // Route pour les nouveaux commentaires non visibles
    Route::get('/admin/comments/unvisible', [AdminController::class, 'viewUnvisibleComments'])->name('admin.viewUnvisibleComments'); // Affiche les commentaires non visibles

    // Autres routes liées aux commandes et au panier
    Route::get('/orders', [ShopController::class, 'viewOrders'])->name('orders.view'); // Affiche les commandes
    Route::get('/orders/{id}', [ShopController::class, 'showOrder'])->name('order.show'); // Affiche une commande spécifique
    Route::get('/cart', [ShopController::class, 'viewCart'])->name('cart.view'); // Affiche le panier
    Route::post('/cart/remove', [ShopController::class, 'removeFromCart'])->name('cart.remove'); // Supprime un article du panier
    Route::post('/cart/checkout', [ShopController::class, 'checkout'])->name('cart.checkout'); // Effectue le paiement

    // Routes pour les commentaires
    Route::post('/comment', [CommentController::class, 'store'])->name('comment.store'); // Ajoute un commentaire
    Route::post('/comment/approve/{id}', [CommentController::class, 'approve'])->name('comment.approve'); // Approuve un commentaire
    Route::post('/comment/hide/{id}', [CommentController::class, 'hide'])->name('comment.hide'); // Cache un commentaire
    Route::get('/comment/edit/{id}', [CommentController::class, 'edit'])->name('comment.edit'); // Édite un commentaire
    Route::put('/comment/{id}', [CommentController::class, 'update'])->name('comment.update'); // Met à jour un commentaire
    Route::delete('/comment/{id}', [CommentController::class, 'destroy'])->name('comment.destroy'); // Supprime un commentaire

    // Routes pour le profil utilisateur
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show'); // Affiche le profil utilisateur
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update'); // Met à jour le profil utilisateur

    // Routes pour le chat
    Route::get('/mini-chat', [ChatController::class, 'index'])->name('chat.index'); // Affiche le mini chat
    Route::post('/mini-chat', [ChatController::class, 'store'])->name('chat.store'); // Ajoute un message au mini chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index')->middleware('auth'); // Affiche le chat (auth requis)
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store')->middleware('auth'); // Ajoute un message au chat (auth requis)
    Route::get('/chat/fetch', [ChatController::class, 'fetchMessages'])->name('chat.fetch'); // Récupère les messages du chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index'); // Affiche le chat
    Route::get('/fetch-messages', [ChatController::class, 'fetchMessages'])->name('chat.fetchMessages');

});

/* Routes pour l'authentification des utilisateurs */
Route::post('/login', [UserController::class, 'loginUser']); // Connexion de l'utilisateur
Route::get('/login', function() {
    return redirect('/'); // Redirige vers la page d'accueil si déjà connecté
})->name('login');
Route::post('/register', [UserController::class, 'register'])->name('register'); // Inscription de l'utilisateur
Route::post('/logout', [UserController::class, 'logout'])->name('logout'); // Déconnexion de l'utilisateur
Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register'); // Affiche le formulaire d'inscription
Route::post('/register', [UserController::class, 'register'])->name('register.post'); // Inscription de l'utilisateur (duplication pour éviter les conflits de nommage)

// Route pour bloquer et débloquer un utilisateur
Route::put('/admin/user/{userId}/block', [AdminController::class, 'blockUser'])->name('admin.blockUser'); // Bloque un utilisateur
Route::put('/admin/user/{userId}/unblock', [AdminController::class, 'unblockUser'])->name('admin.unblockUser'); // Débloque un utilisateur
