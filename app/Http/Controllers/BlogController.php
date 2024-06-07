<?php

namespace App\Http\Controllers;

use App\Models\PostModel;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    // Méthode pour afficher la liste des posts du blog
    public function index()
    {
        // Récupère tous les posts avec leurs commentaires
        $posts = PostModel::with('comments')->get();
        // Retourne la vue 'blog' avec les posts
        return view('blog', compact('posts'));
    }

    // Méthode pour afficher un post spécifique
    public function showPost($id)
    {
        // Trouve le post par son ID avec ses commentaires ou renvoie une erreur 404
        $post = PostModel::with('comments')->findOrFail($id);
        // Enregistre une info dans les logs indiquant que le post a été récupéré
        \Log::info('Post récupéré avec succès:', ['post' => $post]);
        // Retourne la vue 'Show' avec le post
        return view('Show', compact('post'));
    }

    // Méthode pour rechercher des posts
    public function search(Request $request)
    {
        // Récupère le terme de recherche du formulaire
        $searchTerm = $request->input('search');
        // Cherche les posts dont le titre ou le lead contient le terme de recherche
        $posts = PostModel::where('title', 'LIKE', '%' . $searchTerm . '%')
                            ->orWhere('lead', 'LIKE', '%' . $searchTerm . '%')
                            ->with('comments')
                            ->get();
        // Retourne la vue 'blog' avec les posts trouvés
        return view('blog', compact('posts'));
    }

    // Méthode pour mettre à jour un post
    public function update(Request $request, $id)
    {
        // Trouve le post par son ID ou renvoie une erreur 404
        $post = PostModel::findOrFail($id);

        // Met à jour les champs du post avec les données du formulaire
        $post->title = $request->input('title');
        $post->lead = $request->input('lead');
        $post->content = $request->input('content');
        // Sauvegarde les modifications dans la base de données
        $post->save();

        // Redirige vers la page du post mis à jour avec un message de succès
        return redirect()->route('blog.show', ['id' => $post->post_id])->with('success', 'Article mis à jour avec succès');
    }

    // Méthode pour créer un nouveau post
    public function store(Request $request)
    {
        // Vérifie si l'utilisateur est authentifié et s'il a le rôle 'admin'
        if (auth()->check() && auth()->user()->role === 'admin') {
            // Valide les données du formulaire
            $request->validate([
                'title' => 'required|string|max:255',
                'lead' => 'required|string',
                'content' => 'required|string',
            ]);

            // Crée un nouveau post avec les données validées
            PostModel::create([
                'title' => $request->title,
                'lead' => $request->lead,
                'content' => $request->content,
                'author_id' => auth()->user()->user_id,
            ]);

            // Redirige vers la page d'accueil du blog avec un message de succès
            return redirect()->route('blog.index')->with('success', 'Post créé avec succès.');
        }

        // Redirige vers la page d'accueil du blog avec un message d'erreur si l'utilisateur n'est pas admin
        return redirect()->route('blog.index')->with('error', 'Accès refusé.');
    }

    // Méthode pour supprimer un post
    public function deletePost(Request $request)
    {
        // Valide que le post_id est présent et existe dans la table 'posts'
        $request->validate([
            'post_id' => 'required|exists:posts,post_id',
        ]);

        // Trouve et supprime le post par son ID
        PostModel::findOrFail($request->post_id)->delete();

        // Redirige vers la page d'accueil du blog avec un message de succès
        return redirect()->route('blog.index')->with('success', 'Article supprimé avec succès.');
    }
}
