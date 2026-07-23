<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', fn () => view('home'))->name('home');

// Authentification
Route::get('/login', fn () => redirect('/'))->name('login'); // La connexion se fait via la modale de la page d'accueil
Route::post('/login', [UserController::class, 'loginUser'])->name('login.post');
Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UserController::class, 'register'])->name('register.post');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Blog (consultation publique)
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{id}', [BlogController::class, 'showPost'])->whereNumber('id')->name('blog.show');
Route::get('/search', [BlogController::class, 'search'])->name('blog.search');

// Boutique (consultation publique)
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/cart', [ShopController::class, 'cart'])->name('shop.cart');

// Espace membre
Route::middleware('auth')->group(function () {
    // Panier et commandes
    Route::put('/shop/cart/{itemId}', [ShopController::class, 'updateCartItem'])->name('cart.update');
    Route::post('/shop/clear-cart', [ShopController::class, 'clearCart'])->name('cart.clear');
    Route::post('/shop/checkout', [ShopController::class, 'checkout'])->name('shop.checkout');
    Route::get('/shop/orders', [ShopController::class, 'orders'])->name('shop.orders');
    Route::get('/shop/order/{orderId}', [ShopController::class, 'showOrder'])->whereNumber('orderId')->name('shop.order');

    // Commentaires
    Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');

    // Profil utilisateur
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Mini-chat
    Route::get('/mini-chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/mini-chat', [ChatController::class, 'store'])->name('chat.store');
    Route::get('/mini-chat/messages', [ChatController::class, 'fetchMessages'])->name('chat.fetchMessages');
});

// Administration (rôle admin requis)
Route::middleware(['auth', 'admin'])->group(function () {
    // Tableau de bord et gestion des utilisateurs
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/user/{id}/comments', [AdminController::class, 'viewUserComments'])->whereNumber('id')->name('admin.viewUserComments');
    Route::put('/admin/user/{userId}/block', [AdminController::class, 'blockUser'])->whereNumber('userId')->name('admin.blockUser');
    Route::put('/admin/user/{userId}/unblock', [AdminController::class, 'unblockUser'])->whereNumber('userId')->name('admin.unblockUser');

    // Gestion du blog
    Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
    Route::put('/blog/{id}', [BlogController::class, 'update'])->whereNumber('id')->name('post.update');
    Route::delete('/blog', [BlogController::class, 'deletePost'])->name('blog.delete');

    // Gestion de la boutique
    Route::post('/shop/items', [ShopController::class, 'addItem'])->name('shop.addItem');
    Route::delete('/shop/items', [ShopController::class, 'deleteItem'])->name('shop.deleteItem');
    Route::get('/shop/orders/{userId}', [ShopController::class, 'userOrders'])->whereNumber('userId')->name('shop.userOrders');

    // Modération des commentaires
    Route::post('/comment/approve/{id}', [CommentController::class, 'approve'])->whereNumber('id')->name('comment.approve');
    Route::post('/comment/hide/{id}', [CommentController::class, 'hide'])->whereNumber('id')->name('comment.hide');
    Route::put('/comment/{id}', [CommentController::class, 'update'])->whereNumber('id')->name('comment.update');
    Route::delete('/comment/{id}', [CommentController::class, 'destroy'])->whereNumber('id')->name('comment.destroy');
});
