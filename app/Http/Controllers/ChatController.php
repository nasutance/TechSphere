<?php

namespace App\Http\Controllers;

use App\Models\MessageModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Affiche le mini-chat avec les 10 derniers messages
    public function index()
    {
        $messages = MessageModel::latest()->take(10)->get();

        return view('mini-chat', ['messages' => $messages]);
    }

    // Enregistre un nouveau message
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        MessageModel::create([
            'user_id' => Auth::id(),
            'username' => Auth::user()->username,
            'message' => $validated['message'],
        ]);

        return redirect()->route('chat.index')->with('success', 'Message envoyé avec succès.');
    }

    // Renvoie les 10 derniers messages en JSON (rafraîchissement AJAX)
    public function fetchMessages()
    {
        $messages = MessageModel::latest()->take(10)->get();

        return response()->json($messages);
    }
}
