<?php

namespace App\Http\Controllers;

use App\Models\PostModel;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    // Liste des articles du blog
    public function index()
    {
        $posts = PostModel::with('comments')->latest()->get();

        return view('blog', compact('posts'));
    }

    // Affiche un article et ses commentaires
    public function showPost($id)
    {
        $post = PostModel::with('comments.user')->findOrFail($id);

        return view('show', compact('post'));
    }

    // Recherche dans le titre et le chapeau des articles
    public function search(Request $request)
    {
        $searchTerm = $request->input('search');

        $posts = PostModel::where(function ($query) use ($searchTerm) {
                $query->where('title', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('lead', 'LIKE', '%' . $searchTerm . '%');
            })
            ->with('comments')
            ->latest()
            ->get();

        return view('blog', compact('posts'));
    }

    // Crée un nouvel article (admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'lead' => 'required|string',
            'content' => 'required|string',
        ]);

        PostModel::create([
            ...$validated,
            'author_id' => $request->user()->user_id,
        ]);

        return redirect()->route('blog.index')->with('success', 'Article créé avec succès.');
    }

    // Met à jour un article (admin)
    public function update(Request $request, $id)
    {
        $post = PostModel::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'lead' => 'required|string',
            'content' => 'required|string',
        ]);

        $post->update($validated);

        return redirect()->route('blog.show', ['id' => $post->post_id])->with('success', 'Article mis à jour avec succès.');
    }

    // Supprime un article (admin)
    public function deletePost(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,post_id',
        ]);

        PostModel::findOrFail($request->post_id)->delete();

        return redirect()->route('blog.index')->with('success', 'Article supprimé avec succès.');
    }
}
