<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MessageModel;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Méthode pour afficher la page du mini-chat
    public function index()
    {
        // Vérifie si l'utilisateur est authentifié
        if (!Auth::check()) {
            // Redirige vers la page de connexion avec un message d'erreur si non authentifié
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder au mini-chat.');
        }

        // Récupère les 10 derniers messages du mini-chat, triés par date de création décroissante
        $messages = MessageModel::orderBy('created_at', 'desc')->take(10)->get();

        // Retourne la vue 'mini-chat' avec les messages
        return view('mini-chat', ['messages' => $messages]);
    }

    // Méthode pour envoyer un nouveau message
    public function store(Request $request)
    {
        // Valide la longueur du message, il doit être non vide et ne pas dépasser 255 caractères
        $request->validate([
            'message' => 'required|max:255',
        ]);

        // Crée et enregistre le nouveau message
        $message = new MessageModel();
        $message->user_id = Auth::id(); // ID de l'utilisateur connecté
        $message->username = Auth::user()->username; // Nom d'utilisateur de l'utilisateur connecté
        $message->message = $request->input('message'); // Contenu du message
        $message->save(); // Sauvegarde le message dans la base de données

        // Redirige vers la page du mini-chat avec un message de succès
        return redirect()->route('chat.index')->with('success', 'Message envoyé avec succès.');
    }

    // Méthode pour récupérer les 10 derniers messages en JSON (pour les appels AJAX par exemple)
    public function fetchMessages()
    {
        // Récupère les 10 derniers messages, triés par date de création décroissante
        $messages = MessageModel::latest()->take(10)->get();
        // Retourne les messages sous forme de JSON
        return response()->json($messages);
    }

}
