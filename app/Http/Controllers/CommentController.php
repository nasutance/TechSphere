<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommentModel;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Méthode pour stocker un nouveau commentaire
    public function store(Request $request)
    {
        // Vérifie si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour ajouter un commentaire');
        }

        // Validation des données du formulaire
        $request->validate([
            'content' => 'required|string', // Le contenu du commentaire est requis et doit être une chaîne de caractères
            'post_id' => 'required|integer|exists:posts,post_id' // Le post_id est requis, doit être un entier et doit exister dans la table posts
        ]);

        // Récupère l'utilisateur authentifié
        $user = Auth::user();

        // Création du commentaire
        $comment = new CommentModel();
        $comment->content = $request->input('content');
        $comment->post_id = $request->input('post_id');
        $comment->author_id = $user->user_id; // Récupère l'ID de l'utilisateur authentifié
        $comment->visible = false; // Par défaut, le commentaire doit être approuvé par un administrateur
        $comment->save();

        // Redirige vers la page du post avec un message de succès
        return redirect()->route('blog.show', $request->input('post_id'))->with('success', 'Commentaire ajouté avec succès');
    }

    // Méthode pour approuver un commentaire
    public function approve($id)
    {
        // Vérifie si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour approuver un commentaire');
        }

        // Trouve le commentaire par son ID et le rend visible
        $comment = CommentModel::findOrFail($id);
        $comment->visible = true;
        $comment->save();

        // Redirige vers la page précédente avec un message de succès
        return redirect()->back()->with('success', 'Commentaire approuvé avec succès');
    }

    // Méthode pour afficher le formulaire d'édition d'un commentaire
    public function edit($id)
    {
        // Vérifie si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour modifier un commentaire');
        }

        // Trouve le commentaire par son ID
        $comment = CommentModel::findOrFail($id);
        // Retourne la vue 'comments.edit' avec le commentaire
        return view('comments.edit', compact('comment'));
    }

    // Méthode pour mettre à jour un commentaire
    public function update(Request $request, $id)
    {
        // Vérifie si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour mettre à jour un commentaire');
        }

        // Validation des données du formulaire
        $request->validate([
            'content' => 'required|string' // Le contenu du commentaire est requis et doit être une chaîne de caractères
        ]);

        // Trouve le commentaire par son ID et met à jour son contenu
        $comment = CommentModel::findOrFail($id);
        $comment->content = $request->input('content');
        $comment->save();

        // Redirige vers la page du post avec un message de succès
        return redirect()->route('blog.show', $comment->post_id)->with('success', 'Commentaire mis à jour avec succès');
    }

    // Méthode pour supprimer un commentaire
    public function destroy($id)
    {
        // Vérifie si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour supprimer un commentaire');
        }

        // Trouve le commentaire par son ID et le supprime
        $comment = CommentModel::findOrFail($id);
        $comment->delete();

        // Redirige vers la page précédente avec un message de succès
        return redirect()->back()->with('success', 'Commentaire supprimé avec succès');
    }

    // Méthode pour masquer un commentaire (réservée aux administrateurs)
    public function hide($id)
    {
        // Vérifie si l'utilisateur est authentifié et est admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Vous devez être administrateur pour masquer un commentaire');
        }

        // Trouve le commentaire par son ID et le rend invisible
        $comment = CommentModel::findOrFail($id);
        $comment->visible = false;
        $comment->save();

        // Redirige vers la page précédente avec un message de succès
        return redirect()->back()->with('success', 'Commentaire masqué avec succès');
    }
}
