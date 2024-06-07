<?php
namespace App\Http\Controllers;

use App\Models\UserModel; // Importation du modèle UserModel
use App\Models\LoginModel; // Importation du modèle LoginModel
use Illuminate\Http\Request; // Importation de la classe Request
use Illuminate\Support\Facades\Auth; // Importation de la façade Auth pour l'authentification
use Illuminate\Support\Facades\Hash; // Importation de la façade Hash pour le hashage des mots de passe
use Illuminate\Support\Facades\Storage; // Importation de la façade Storage pour la gestion des fichiers

class UserController extends Controller
{
    private $userModel; // Déclaration d'une propriété privée $userModel

    public function __construct(UserModel $userModel) { // Constructeur avec injection de dépendance
        $this->userModel = $userModel; // Assignation du modèle UserModel à la propriété $userModel
    }

    // Méthode pour récupérer un utilisateur par ID
    public function getUser($id) {
        $user = UserModel::find($id); // Recherche de l'utilisateur par ID
        if ($user) { // Si l'utilisateur est trouvé
            return response()->json($user); // Retourne les détails de l'utilisateur en format JSON
        } else {
            return response()->json(['message' => 'Aucun utilisateur trouvé avec l\'ID ' . $id], 404); // Retourne un message d'erreur si l'utilisateur n'est pas trouvé
        }
    }

    // Méthode pour ajouter un nouvel utilisateur
    public function addUser(Request $request) {
        $validatedData = $request->validate([ // Validation des données de la requête
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'email' => 'required|string|email|max:255|unique:users',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10',
            'date_de_naissance' => 'required|date',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,gif|max:2048',
        ]);

        $user = new UserModel(); // Création d'une nouvelle instance du modèle UserModel
        $user->username = $validatedData['username'];
        $user->password = Hash::make($validatedData['password']); // Hashage du mot de passe
        $user->email = $validatedData['email'];
        $user->nom = $validatedData['nom'];
        $user->prenom = $validatedData['prenom'];
        $user->adresse = $validatedData['adresse'];
        $user->code_postal = $validatedData['code_postal'];
        $user->date_de_naissance = $validatedData['date_de_naissance'];

        if ($request->hasFile('profile_image')) { // Vérifie si une image de profil a été téléchargée
            $path = $request->file('profile_image')->store('profile_images', 'public'); // Stocke l'image dans le dossier 'profile_images'
            $user->profile_image = $path; // Enregistre le chemin de l'image dans l'utilisateur
        }

        $user->save(); // Sauvegarde l'utilisateur dans la base de données

        return response()->json(['user_id' => $user->user_id], 201); // Retourne l'ID de l'utilisateur créé avec le code de statut 201
    }

    // Méthode pour mettre à jour un utilisateur
    public function updateUser(Request $request, $id) {
        $validatedData = $request->validate([ // Validation des données de la requête
            'username' => 'sometimes|required|string|max:255',
            'password' => 'sometimes|required|string|min:8',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id . ',user_id',
            'nom' => 'nullable|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:10',
            'date_de_naissance' => 'nullable|date',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = UserModel::find($id); // Recherche de l'utilisateur par ID
        if ($user) { // Si l'utilisateur est trouvé
            $user->username = $validatedData['username'] ?? $user->username;
            $user->email = $validatedData['email'] ?? $user->email;
            if (!empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']); // Hashage du mot de passe si fourni
            }
            $user->nom = $validatedData['nom'] ?? $user->nom;
            $user->prenom = $validatedData['prenom'] ?? $user->prenom;
            $user->adresse = $validatedData['adresse'] ?? $user->adresse;
            $user->code_postal = $validatedData['code_postal'] ?? $user->code_postal;
            $user->date_de_naissance = $validatedData['date_de_naissance'] ?? $user->date_de_naissance;

            if ($request->hasFile('profile_image')) { // Vérifie si une image de profil a été téléchargée
                $path = $request->file('profile_image')->store('profile_images', 'public'); // Stocke l'image dans le dossier 'profile_images'
                $user->profile_image = $path; // Enregistre le chemin de l'image dans l'utilisateur
            }

            $user->save(); // Sauvegarde les modifications de l'utilisateur dans la base de données

            return response()->json(['message' => 'Utilisateur mis à jour avec succès.']); // Retourne un message de succès
        } else {
            return response()->json(['message' => 'Utilisateur non trouvé.'], 404); // Retourne un message d'erreur si l'utilisateur n'est pas trouvé
        }
    }

    // Méthode pour supprimer un utilisateur
    public function deleteUser($id) {
        $user = UserModel::find($id); // Recherche de l'utilisateur par ID
        if ($user) { // Si l'utilisateur est trouvé
            $user->delete(); // Supprime l'utilisateur de la base de données
            return response()->json(['message' => 'Utilisateur supprimé avec succès.']); // Retourne un message de succès
        } else {
            return response()->json(['message' => 'Utilisateur non trouvé.'], 404); // Retourne un message d'erreur si l'utilisateur n'est pas trouvé
        }
    }

    // Méthode pour récupérer le rôle d'un utilisateur par ID
    public function getUserRole($id) {
        $user = UserModel::find($id); // Recherche de l'utilisateur par ID
        if ($user) { // Si l'utilisateur est trouvé
            return response()->json(['role' => $user->role]); // Retourne le rôle de l'utilisateur
        } else {
            return response()->json(['message' => 'Utilisateur non trouvé.'], 404); // Retourne un message d'erreur si l'utilisateur n'est pas trouvé
        }
    }

    // Méthode pour connecter un utilisateur
    public function loginUser(Request $request) {
        $credentials = $request->only('username', 'password'); // Récupération des identifiants depuis la requête

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) { // Tentative de connexion
            $request->session()->regenerate(); // Régénère la session

            if (Auth::user()->role === 'blocked') { // Vérifie si le compte est bloqué
                Auth::logout(); // Déconnecte l'utilisateur
                return back()->withErrors(['username' => 'Votre compte est restreint. Contactez le support.']); // Retourne un message d'erreur
            }

            // Ajouter une entrée dans la table logins
            $login = new LoginModel();
            $login->user_id = Auth::id();
            $login->date = now();
            $login->save();

            if (Auth::user()->role === 'admin') { // Redirige selon le rôle de l'utilisateur
                return redirect()->route('admin.index')->with('success', 'Connexion réussie en tant qu\'administrateur!');
            }

            if ($request->has('redirect_to')) {
                return redirect()->to($request->input('redirect_to'))->with('success', 'Connexion réussie!');
            }

            return redirect()->intended('/')->with('success', 'Connexion réussie!');
        }

        return back()->withErrors(['username' => 'Les identifiants fournis sont incorrects.']); // Retourne un message d'erreur si les identifiants sont incorrects
    }

    // Méthode pour enregistrer un nouvel utilisateur
    public function register(Request $request) {
        $validatedData = $request->validate([ // Validation des données du formulaire
            'username' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'nom' => 'required|max:255',
            'prenom' => 'required|max:255',
            'adresse' => 'required|max:255',
            'code_postal' => 'required|max:10',
            'date_de_naissance' => 'required|date',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,gif|max:2048',
        ]);

        try {
            // Création d'une nouvelle instance du modèle UserModel
            $user = new UserModel();
            $user->username = $validatedData['username'];
            $user->password = Hash::make($validatedData['password']); // Hashage du mot de passe
            $user->email = $validatedData['email'];
            $user->nom = $validatedData['nom'];
            $user->prenom = $validatedData['prenom'];
            $user->adresse = $validatedData['adresse'];
            $user->code_postal = $validatedData['code_postal'];
            $user->date_de_naissance = $validatedData['date_de_naissance'];

            if ($request->hasFile('profile_image')) { // Vérifie si une image de profil a été téléchargée
                $path = $request->file('profile_image')->store('profile_images', 'public'); // Stocke l'image dans le dossier 'profile_images'
                $user->profile_image = $path; // Enregistre le chemin de l'image dans l'utilisateur
            }

            $user->save(); // Sauvegarde l'utilisateur dans la base de données

            Auth::loginUsingId($user->user_id); // Connecte l'utilisateur nouvellement inscrit
            return redirect('/')->with('success', 'Inscription réussie. Bienvenue!'); // Redirige l'utilisateur avec un message de succès
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Erreur d\'inscription: ' . $e->getMessage()]); // Retourne un message d'erreur en cas d'exception
        }
    }

    // Méthode pour déconnecter un utilisateur
    public function logout(Request $request) {
        Auth::logout(); // Déconnecte l'utilisateur

        $request->session()->invalidate(); // Invalide la session actuelle
        $request->session()->regenerateToken(); // Régénère le token CSRF

        return redirect('/')->with('success', 'Déconnexion réussie!'); // Redirige l'utilisateur avec un message de succès
    }

    // Méthode pour afficher le profil de l'utilisateur connecté
    public function showProfile() {
        return view('profile'); // Retourne la vue 'profile'
    }

    // Méthode pour mettre à jour le profil de l'utilisateur
    public function updateProfile(Request $request) {
        $user = Auth::user(); // Récupère l'utilisateur authentifié

        $validatedData = $request->validate([ // Validation des données du formulaire
            'username' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(). ',user_id',
            'password' => 'nullable|min:6|confirmed',
            'nom' => 'nullable|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:10',
            'date_de_naissance' => 'nullable|date',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']); // Hashage du mot de passe si fourni
        }
        $user->nom = $validatedData['nom'] ?? $user->nom;
        $user->prenom = $validatedData['prenom'] ?? $user->prenom;
        $user->adresse = $validatedData['adresse'] ?? $user->adresse;
        $user->code_postal = $validatedData['code_postal'] ?? $user->code_postal;
        $user->date_de_naissance = $validatedData['date_de_naissance'] ?? $user->date_de_naissance;

        if ($request->hasFile('profile_image')) { // Vérifie si une image de profil a été téléchargée
            $path = $request->file('profile_image')->storeAs('assets/img', $user->user_id . '.' . $request->file('profile_image')->getClientOriginalExtension(), 'public'); // Stocke l'image dans le dossier 'assets/img'
            $user->profile_image = $path; // Enregistre le chemin de l'image dans l'utilisateur
        }

        $user->save(); // Sauvegarde les modifications de l'utilisateur dans la base de données

        return redirect()->route('profile.show')->with('success', 'Profil mis à jour avec succès.'); // Redirige l'utilisateur avec un message de succès
    }

    // Méthode pour afficher le formulaire d'inscription
    public function showRegisterForm() {
        return view('Register'); // Retourne la vue 'Register'
    }
}
?>
