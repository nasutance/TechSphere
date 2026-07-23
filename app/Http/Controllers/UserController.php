<?php

namespace App\Http\Controllers;

use App\Models\LoginModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Connexion d'un utilisateur
    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credentials)) {
            return back()->withErrors(['username' => 'Les identifiants fournis sont incorrects.']);
        }

        $request->session()->regenerate();

        // Les comptes bloqués sont refusés à la connexion
        if (Auth::user()->role === 'blocked') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors(['username' => 'Votre compte est restreint. Contactez le support.']);
        }

        // Historise la connexion pour les statistiques du tableau de bord
        LoginModel::create([
            'user_id' => Auth::id(),
            'date' => now(),
        ]);

        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.index')->with('success', 'Connexion réussie en tant qu\'administrateur !');
        }

        return redirect()->intended('/')->with('success', 'Connexion réussie !');
    }

    // Inscription d'un nouvel utilisateur
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10',
            'date_de_naissance' => 'required|date|before:today',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $user = new User();
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->nom = $validated['nom'];
        $user->prenom = $validated['prenom'];
        $user->adresse = $validated['adresse'];
        $user->code_postal = $validated['code_postal'];
        $user->date_de_naissance = $validated['date_de_naissance'];

        if ($request->hasFile('profile_image')) {
            $user->profile_image = $request->file('profile_image')->store('profile_images', 'public');
        }

        $user->save();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/')->with('success', 'Inscription réussie. Bienvenue !');
    }

    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Déconnexion réussie !');
    }

    // Page de profil de l'utilisateur connecté
    public function showProfile()
    {
        return view('profile');
    }

    // Mise à jour du profil
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->user_id . ',user_id',
            'email' => 'required|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'password' => 'nullable|string|min:8|confirmed',
            'nom' => 'nullable|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:10',
            'date_de_naissance' => 'nullable|date|before:today',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $user->username = $validated['username'];
        $user->email = $validated['email'];
        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->nom = $validated['nom'] ?? $user->nom;
        $user->prenom = $validated['prenom'] ?? $user->prenom;
        $user->adresse = $validated['adresse'] ?? $user->adresse;
        $user->code_postal = $validated['code_postal'] ?? $user->code_postal;
        $user->date_de_naissance = $validated['date_de_naissance'] ?? $user->date_de_naissance;

        if ($request->hasFile('profile_image')) {
            $user->profile_image = $request->file('profile_image')->store('profile_images', 'public');
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profil mis à jour avec succès.');
    }

    // Formulaire d'inscription
    public function showRegisterForm()
    {
        return view('register');
    }
}
