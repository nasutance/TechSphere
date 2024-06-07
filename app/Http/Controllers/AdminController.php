<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ItemModel;
use App\Models\OrderModel;
use App\Models\CommentModel;
use App\Models\PostModel;
use Carbon\Carbon;

class AdminController extends Controller
{
    // Méthode pour afficher la page d'accueil de l'administration
    public function index()
    {
        // Récupère tous les utilisateurs et ajoute des informations sur leurs connexions
        $users = User::all()->map(function($user) {
            // Compte les connexions d'aujourd'hui
            // Laravel utilise des requêtes préparées implicitement ici
            $user->logins_today = $user->logins()->whereDate('date', Carbon::today())->count();
            // Compte les connexions des 7 derniers jours
            // Requête préparée implicite
            $user->logins_last_7_days = $user->logins()->whereDate('date', '>=', Carbon::now()->subDays(7))->count();
            return $user;
        });

        // Récupère les nouveaux commentaires non visibles
        // Laravel utilise des requêtes préparées implicitement ici
        $newComments = CommentModel::where('visible', false)
                                   ->orderBy('created_at', 'desc')
                                   ->get()
                                   ->map(function($comment) {
                                       // Ajoute le titre du post associé au commentaire
                                       // Requête préparée implicite
                                       $comment->post_title = PostModel::where('post_id', $comment->post_id)->value('title');
                                       // Ajoute le nom de l'auteur du commentaire
                                       // Requête préparée implicite
                                       $comment->author_name = User::where('user_id', $comment->author_id)->value('username');
                                       return $comment;
                                   });

        // Retourne la vue 'admin' avec les utilisateurs et les nouveaux commentaires
        return view('admin', compact('users', 'newComments'));
    }

    // Méthode pour afficher les commentaires d'un utilisateur spécifique
    public function viewUserComments($userId)
    {
        // Trouve l'utilisateur par son ID
        // Requête préparée implicite
        $user = User::findOrFail($userId);

        // Récupère les 5 derniers commentaires de l'utilisateur
        // Laravel utilise des requêtes préparées implicitement ici
        $comments = CommentModel::where('author_id', $userId)
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get()
                                ->map(function ($comment) {
                                    // Ajoute le titre du post associé au commentaire
                                    // Requête préparée implicite
                                    $comment->post_title = PostModel::where('post_id', $comment->post_id)->value('title');
                                    return $comment;
                                });

        // Retourne les commentaires sous forme de JSON
        return response()->json($comments);
    }

    // Méthode pour afficher les commentaires non visibles
    public function viewUnvisibleComments()
    {
        // Récupère tous les commentaires non visibles
        // Laravel utilise des requêtes préparées implicitement ici
        $comments = CommentModel::where('visible', false)
                                ->orderBy('created_at', 'desc')
                                ->get()
                                ->map(function ($comment) {
                                    // Ajoute le titre du post associé au commentaire
                                    // Requête préparée implicite
                                    $comment->post_title = PostModel::where('post_id', $comment->post_id)->value('title');
                                    // Ajoute le nom de l'auteur du commentaire
                                    // Requête préparée implicite
                                    $comment->author_name = User::where('user_id', $comment->author_id)->value('username');
                                    return $comment;
                                });

        // Retourne les commentaires sous forme de JSON
        return response()->json($comments);
    }

    // Méthode pour créer un nouveau post
    public function createPost(Request $request)
    {
        // Valide les données du formulaire
        $request->validate([
            'title' => 'required|string|max:255',
            'lead' => 'required|string',
            'content' => 'required|string',
        ]);

        // Crée un nouveau post avec les données validées
        // Laravel utilise des requêtes préparées implicitement ici
        PostModel::create([
            'title' => $request->title,
            'lead' => $request->lead,
            'content' => $request->content,
            'author_id' => auth()->user()->user_id,
        ]);

        // Redirige vers la page d'accueil de l'administration avec un message de succès
        return redirect()->route('admin.index')->with('success', 'Post créé avec succès.');
    }

    // Méthode pour afficher les détails d'un utilisateur
    public function viewUser($id)
    {
        // Trouve l'utilisateur par son ID
        // Requête préparée implicite
        $user = User::findOrFail($id);
        // Retourne la vue 'admin.view-user' avec les détails de l'utilisateur
        return view('admin.view-user', compact('user'));
    }

    // Méthode pour afficher les commandes d'un utilisateur
    public function viewUserOrders($id)
    {
        // Récupère les commandes de l'utilisateur
        // Laravel utilise des requêtes préparées implicitement ici
        $orders = OrderModel::where('user_id', $id)->orderBy('created_at', 'desc')->get();
        // Retourne la vue 'admin.view-user-orders' avec les commandes
        return view('admin.view-user-orders', compact('orders'));
    }

    // Méthode pour ajouter un nouvel article
    public function addItem(Request $request)
    {
        // Valide les données du formulaire
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        // Crée un nouvel article avec les données validées
        // Laravel utilise des requêtes préparées implicitement ici
        ItemModel::create($request->all());
        // Redirige vers la page d'accueil de l'administration avec un message de succès
        return redirect()->route('admin.index')->with('success', 'Article ajouté avec succès.');
    }

    // Méthode pour supprimer un article
    public function deleteItem($id)
    {
        // Trouve l'article par son ID
        // Requête préparée implicite
        $item = ItemModel::findOrFail($id);
        // Supprime l'article
        $item->delete();
        // Redirige vers la page d'accueil de l'administration avec un message de succès
        return redirect()->route('admin.index')->with('success', 'Article supprimé avec succès.');
    }

    // Méthode pour bloquer un utilisateur
    public function blockUser($userId)
    {
        // Trouve l'utilisateur par son ID
        // Requête préparée implicite
        $user = User::findOrFail($userId);
        // Change le rôle de l'utilisateur en 'blocked'
        $user->role = 'blocked';
        // Sauvegarde les modifications
        // Requête préparée implicite
        $user->save();

        // Redirige vers la page d'accueil de l'administration avec un message de succès
        return redirect()->route('admin.index')->with('success', "L'utilisateur a été bloqué avec succès.");
    }

    // Méthode pour débloquer un utilisateur
    public function unblockUser($userId)
    {
        // Trouve l'utilisateur par son ID
        // Requête préparée implicite
        $user = User::findOrFail($userId);
        // Change le rôle de l'utilisateur en 'client' (ou 'admin' selon le rôle précédent)
        $user->role = 'client';
        // Sauvegarde les modifications
        // Requête préparée implicite
        $user->save();

        // Redirige vers la page d'accueil de l'administration avec un message de succès
        return redirect()->route('admin.index')->with('success', "L'utilisateur a été débloqué avec succès.");
    }
}
