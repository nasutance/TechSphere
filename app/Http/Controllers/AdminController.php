<?php

namespace App\Http\Controllers;

use App\Models\CommentModel;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    // Tableau de bord : liste des utilisateurs et commentaires en attente de modération
    public function index()
    {
        $users = User::withCount([
            'logins as logins_today' => fn ($query) => $query->whereDate('date', Carbon::today()),
            'logins as logins_last_7_days' => fn ($query) => $query->whereDate('date', '>=', Carbon::now()->subDays(7)),
        ])->get();

        $newComments = CommentModel::with(['post:post_id,title', 'user:user_id,username'])
            ->where('visible', false)
            ->orderByDesc('created_at')
            ->get();

        return view('admin', compact('users', 'newComments'));
    }

    // Les 5 derniers commentaires d'un utilisateur (chargés en AJAX depuis le tableau de bord)
    public function viewUserComments($userId)
    {
        User::findOrFail($userId);

        $comments = CommentModel::with('post:post_id,title')
            ->where('author_id', $userId)
            ->orderByDesc('created_at')
            ->take(5)
            ->get()
            ->map(fn ($comment) => [
                'content' => $comment->content,
                'created_at' => $comment->created_at,
                'post_title' => $comment->post?->title,
            ]);

        return response()->json($comments);
    }

    // Bloque un utilisateur (il ne pourra plus se connecter)
    public function blockUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->role = 'blocked';
        $user->save();

        return redirect()->route('admin.index')->with('success', "L'utilisateur a été bloqué avec succès.");
    }

    // Débloque un utilisateur en lui rendant le rôle client
    public function unblockUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->role = 'client';
        $user->save();

        return redirect()->route('admin.index')->with('success', "L'utilisateur a été débloqué avec succès.");
    }
}
