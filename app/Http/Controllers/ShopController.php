<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryModel;
use App\Models\ItemModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    // Méthode pour afficher la page d'accueil du magasin
    public function index()
    {
        // Récupère toutes les catégories avec leurs articles
        $categories = CategoryModel::with('items')->get();

        // Récupère le contenu du panier depuis la session
        $cart = session()->get('cart', []);
        $cartItems = collect($cart)->map(function ($item, $id) {
            return (object) [
                'item_id' => $id,
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ];
        });

        // Calcule le total du panier
        $total = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        // Récupère tous les articles pour la liste déroulante
        $items = ItemModel::all();

        // Retourne la vue 'shop.index' avec les données nécessaires
        return view('shop.index', compact('categories', 'cartItems', 'total', 'items'));
    }

    // Méthode pour mettre à jour la quantité d'un article dans le panier
    public function updateCartItem(Request $request, $itemId)
    {
        // Récupère le contenu du panier depuis la session
        $cart = session()->get('cart', []);
        $item = ItemModel::findOrFail($itemId); // Trouve l'article par son ID
        $change = $request->input('quantity'); // Quantité à ajouter ou retirer

        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity'] += $change;
            if ($cart[$itemId]['quantity'] > 10) {
                $cart[$itemId]['quantity'] = 10;
                return redirect()->back()->with('error', 'Vous ne pouvez pas ajouter plus de 10 fois cet article.');
            } elseif ($cart[$itemId]['quantity'] < 1) {
                unset($cart[$itemId]); // Retire l'article du panier si la quantité est inférieure à 1
            }
        } else {
            if ($change > 0) {
                $cart[$itemId] = [
                    'name' => $item->name,
                    'price' => $item->price,
                    'quantity' => $change
                ];
            }
        }

        session()->put('cart', $cart); // Met à jour le panier dans la session

        $redirect = $request->input('redirect', route('shop.cart'));
        return redirect($redirect)->with('success', 'Panier mis à jour.');
    }

    // Méthode pour vider le panier
    public function clearCart()
    {
        session()->forget('cart'); // Supprime le panier de la session
        return redirect()->back()->with('success', 'Le panier a été vidé.');
    }

    // Méthode pour afficher le contenu du panier
    public function cart()
    {
        // Récupère le contenu du panier depuis la session
        $cart = session()->get('cart', []);
        $cartItems = collect($cart)->map(function ($item, $id) {
            return (object) [
                'item_id' => $id,
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ];
        });

        // Calcule le total du panier
        $total = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        // Retourne la vue 'shop.cart' avec les données nécessaires
        return view('shop.cart', compact('cartItems', 'total'));
    }

    // Méthode pour passer à la caisse
    public function checkout()
    {
        // Récupère le contenu du panier depuis la session
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Votre panier est vide!');
        }

        // Calcule le total du panier
        $total = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        // Crée la commande
        $order = OrderModel::create([
            'user_id' => Auth::id(),
            'total' => $total
        ]);

        // Ajoute chaque article de la commande
        foreach ($cart as $itemId => $item) {
            OrderItemModel::create([
                'order_id' => $order->order_id,
                'item_id' => $itemId,
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        session()->forget('cart'); // Vide le panier

        return redirect()->route('shop.order', ['orderId' => $order->order_id])->with('success', 'Paiement effectué avec succès!');
    }

    // Méthode pour afficher les détails d'une commande
    public function showOrder($orderId)
    {
        // Trouve la commande par son ID avec les articles associés
        $order = OrderModel::with('items.item')->findOrFail($orderId);
        // Retourne la vue 'shop.order' avec les détails de la commande
        return view('shop.order', compact('order'));
    }

    // Méthode pour afficher toutes les commandes de l'utilisateur connecté
    public function orders()
    {
        // Récupère toutes les commandes de l'utilisateur connecté avec les articles associés
        $orders = OrderModel::where('user_id', Auth::id())->with('items.item')->get();
        // Retourne la vue 'shop.order' avec les commandes
        return view('shop.order', compact('orders'));
    }

    // Méthode pour ajouter un nouvel article
    public function addItem(Request $request)
    {
        // Validation des données du formulaire
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,category_id',
        ]);

        // Crée un nouvel article avec les données validées
        ItemModel::create([
            'name' => $request->name,
            'price' => $request->price,
            'category_id' => $request->category_id,
        ]);

        // Redirige vers la page d'accueil du magasin avec un message de succès
        return redirect()->route('shop.index')->with('success', 'Article ajouté avec succès.');
    }

    // Méthode pour supprimer un article
    public function deleteItem(Request $request)
    {
        // Validation des données du formulaire
        $request->validate([
            'item_id' => 'required|exists:items,item_id',
        ]);

        // Trouve l'article par son ID et le supprime
        ItemModel::findOrFail($request->item_id)->delete();

        // Redirige vers la page d'accueil du magasin avec un message de succès
        return redirect()->route('shop.index')->with('success', 'Article supprimé avec succès.');
    }

    // Méthode pour afficher les commandes d'un utilisateur spécifique
    public function userOrders($userId)
    {
        // Trouve l'utilisateur par son ID
        $user = User::findOrFail($userId);
        // Récupère toutes les commandes de l'utilisateur avec les articles associés
        $orders = OrderModel::where('user_id', $user->user_id)->with('items.item')->get();

        // Retourne la vue 'shop.order' avec les commandes et les informations de l'utilisateur
        return view('shop.order', compact('orders', 'user'));
    }
}
