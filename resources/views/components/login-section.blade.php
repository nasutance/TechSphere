<!-- components/login-section.blade.php -->

@if(!Auth::check())
<!-- Affiche cette section uniquement si l'utilisateur n'est pas authentifié -->
<section id="login">
    <!-- Section de connexion -->

    <link rel="stylesheet" href="{{ asset('assets/css/login-style.css') }}">
    <!-- Liaison vers la feuille de style pour la mise en forme du formulaire de connexion -->

    <h2>Connexion</h2>
    <!-- Titre de la section de connexion -->

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
            <!-- Affiche un message de succès s'il est présent dans la session -->
        </div>
    @endif

    <form action="{{ url('/login') }}" method="post">
        <!-- Formulaire de connexion avec méthode POST -->
        @csrf
        <!-- Token CSRF pour la sécurité -->

        <label for="username">Nom d'utilisateur:<br></label>
        <!-- Champ pour le nom d'utilisateur -->
        <input type="text" id="username" name="username" required minlength="3" maxlength="255" class="@error('username') is-invalid @enderror" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ $errors->first('username') }}">
        <!-- Champ de saisie du nom d'utilisateur avec validation côté client -->
        @error('username')
            <div class="alert alert-danger">{{ $message }}</div>
            <!-- Affichage des erreurs de validation du nom d'utilisateur -->
        @enderror

        <label for="password"><br>Mot de passe:<br></label>
        <!-- Champ pour le mot de passe -->
        <input type="password" id="password" name="password" required minlength="6" class="@error('password') is-invalid @enderror" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ $errors->first('password') }}">
        <!-- Champ de saisie du mot de passe avec validation côté client -->
        @error('password')
            <div class="alert alert-danger">{{ $message }}</div>
            <!-- Affichage des erreurs de validation du mot de passe -->
        @enderror

        <button type="submit">Se connecter</button>
        <!-- Bouton de soumission du formulaire -->
    </form>

    <p>Vous n'avez pas de compte ? <a href="{{ url('/Register') }}">Inscrivez-vous ici</a>.</p>
    <!-- Lien vers la page d'inscription -->

    <script src="{{ asset('assets/js/label.js') }}"></script>
    <!-- Lien vers le fichier JavaScript pour la gestion des labels -->
</section>
@endif
