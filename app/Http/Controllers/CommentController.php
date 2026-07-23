<?php

namespace App\Http\Controllers;

use App\Models\CommentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Ajoute un commentaire, invisible tant qu'un admin ne l'a pas approuvé
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'post_id' => 'required|integer|exists:posts,post_id',
        ]);

        CommentModel::create([
            'content' => $validated['content'],
            'post_id' => $validated['post_id'],
            'author_id' => Auth::id(),
            'visible' => false,
        ]);

        return redirect()->route('blog.show', $validated['post_id'])->with('success', 'Commentaire ajouté avec succès. Il sera visible après modération.');
    }

    // Approuve un commentaire (admin)
    public function approve($id)
    {
        $comment = CommentModel::findOrFail($id);
        $comment->visible = true;
        $comment->save();

        return redirect()->back()->with('success', 'Commentaire approuvé avec succès.');
    }

    // Masque un commentaire (admin)
    public function hide($id)
    {
        $comment = CommentModel::findOrFail($id);
        $comment->visible = false;
        $comment->save();

        return redirect()->back()->with('success', 'Commentaire masqué avec succès.');
    }

    // Modifie le contenu d'un commentaire (admin)
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment = CommentModel::findOrFail($id);
        $comment->content = $validated['content'];
        $comment->save();

        return redirect()->route('blog.show', $comment->post_id)->with('success', 'Commentaire mis à jour avec succès.');
    }

    // Supprime un commentaire (admin)
    public function destroy($id)
    {
        CommentModel::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Commentaire supprimé avec succès.');
    }
}
