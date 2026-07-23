<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use App\Models\ItemModel;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    // Page d'accueil de la boutique : catalogue par catégorie et panier
    public function index()
    {
        $categories = CategoryModel::with('items')->get();
        $cartItems = $this->cartItems();

        $total = $cartItems->sum(fn ($item) => $item->price * $item->quantity);

        $items = ItemModel::all();

        return view('shop.index', compact('categories', 'cartItems', 'total', 'items'));
    }

    // Ajoute ou retire une quantité d'un article dans le panier (stocké en session)
    public function updateCartItem(Request $request, $itemId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|between:-10,10',
        ]);

        $cart = session()->get('cart', []);
        $item = ItemModel::findOrFail($itemId);
        $change = (int) $validated['quantity'];

        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity'] += $change;
            if ($cart[$itemId]['quantity'] > 10) {
                $cart[$itemId]['quantity'] = 10;
                session()->put('cart', $cart);

                return redirect()->back()->with('error', 'Vous ne pouvez pas ajouter plus de 10 fois cet article.');
            } elseif ($cart[$itemId]['quantity'] < 1) {
                unset($cart[$itemId]);
            }
        } elseif ($change > 0) {
            $cart[$itemId] = [
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => $change,
            ];
        }

        session()->put('cart', $cart);

        // N'accepte qu'une redirection interne au site
        $redirect = $request->input('redirect', route('shop.cart'));
        if (! str_starts_with($redirect, url('/'))) {
            $redirect = route('shop.cart');
        }

        return redirect($redirect)->with('success', 'Panier mis à jour.');
    }

    // Vide le panier
    public function clearCart()
    {
        session()->forget('cart');

        return redirect()->back()->with('success', 'Le panier a été vidé.');
    }

    // Affiche le contenu du panier
    public function cart()
    {
        $cartItems = $this->cartItems();
        $total = $cartItems->sum(fn ($item) => $item->price * $item->quantity);

        return view('shop.cart-page', compact('cartItems', 'total'));
    }

    // Transforme le panier en commande
    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Votre panier est vide !');
        }

        $total = array_reduce($cart, fn ($carry, $item) => $carry + ($item['price'] * $item['quantity']), 0);

        // La commande et ses lignes sont créées dans une même transaction
        $order = DB::transaction(function () use ($cart, $total) {
            $order = OrderModel::create([
                'user_id' => Auth::id(),
                'total' => $total,
            ]);

            foreach ($cart as $itemId => $item) {
                OrderItemModel::create([
                    'order_id' => $order->order_id,
                    'item_id' => $itemId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            return $order;
        });

        session()->forget('cart');

        return redirect()->route('shop.order', ['orderId' => $order->order_id])->with('success', 'Paiement effectué avec succès !');
    }

    // Détail d'une commande (accessible à son propriétaire ou à un admin)
    public function showOrder($orderId)
    {
        $order = OrderModel::with('items.item')->findOrFail($orderId);

        abort_unless($order->user_id === Auth::id() || Auth::user()->isAdmin(), 403);

        return view('shop.order', compact('order'));
    }

    // Historique des commandes de l'utilisateur connecté
    public function orders()
    {
        $orders = OrderModel::where('user_id', Auth::id())
            ->with('items.item')
            ->latest()
            ->get();

        return view('shop.order', compact('orders'));
    }

    // Ajoute un article au catalogue (admin)
    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,category_id',
        ]);

        ItemModel::create($validated);

        return redirect()->route('shop.index')->with('success', 'Article ajouté avec succès.');
    }

    // Supprime un article du catalogue (admin)
    public function deleteItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,item_id',
        ]);

        ItemModel::findOrFail($request->item_id)->delete();

        return redirect()->route('shop.index')->with('success', 'Article supprimé avec succès.');
    }

    // Commandes d'un utilisateur donné (admin)
    public function userOrders($userId)
    {
        $user = User::findOrFail($userId);
        $orders = OrderModel::where('user_id', $user->user_id)
            ->with('items.item')
            ->latest()
            ->get();

        return view('shop.order', compact('orders', 'user'));
    }

    // Reconstruit les lignes du panier à partir de la session
    private function cartItems()
    {
        return collect(session()->get('cart', []))->map(fn ($item, $id) => (object) [
            'item_id' => $id,
            'name' => $item['name'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ]);
    }
}
