<!-- resources/views/components/register-section.blade.php -->

<section id="register">
    <!-- Section pour le formulaire d'inscription -->

    <link rel="stylesheet" href="{{ asset('assets/css/register-style.css') }}">
    <!-- Liaison vers la feuille de style pour la mise en forme du formulaire d'inscription -->

    <form action="{{ Auth::check() ? route('profile.update') : route('register') }}" method="post" enctype="multipart/form-data">
        <!-- Formulaire d'inscription ou de mise à jour du profil avec méthode POST et support des fichiers -->
        @csrf
        <!-- Token CSRF pour la sécurité -->
        @if(Auth::check())
            @method('PUT')
            <!-- Méthode PUT pour la mise à jour du profil si l'utilisateur est authentifié -->
        @endif

        <label for="reg_username">Nom d'utilisateur:</label>
        <input type="text" id="reg_username" name="username" value="{{ old('username', Auth::check() ? Auth::user()->username : '') }}" required minlength="3" maxlength="255" class="@error('username') is-invalid @enderror" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ $errors->first('username') }}">
        <!-- Champ pour le nom d'utilisateur avec validation côté client et affichage des erreurs -->
        @error('username')
            <div class="alert alert-danger">{{ $message }}</div>
            <!-- Affichage des erreurs de validation du nom d'utilisateur -->
        @enderror

        <label for="reg_email">Email:</label>
        <input type="email" id="reg_email" name="email" value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" required class="@error('email') is-invalid @enderror" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ $errors->first('email') }}">
        <!-- Champ pour l'email avec validation côté client et affichage des erreurs -->
        @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
            <!-- Affichage des erreurs de validation de l'email -->
        @enderror

        <div class="inline-fields">
            <!-- Champs pour le mot de passe et la confirmation du mot de passe affichés côte à côte -->
            <div>
                <label for="reg_password">Mot de passe:</label>
                <input type="password" id="reg_password" name="password" class="@error('password') is-invalid @enderror" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ $errors->first('password') }}">
                <!-- Champ pour le mot de passe avec validation côté client et affichage des erreurs -->
                @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                    <!-- Affichage des erreurs de validation du mot de passe -->
                @enderror
            </div>

            <div>
                <label for="reg_password_confirmation">Confirmez le mot de passe:</label>
                <input type="password" id="reg_password_confirmation" name="password_confirmation" class="@error('password_confirmation') is-invalid @enderror" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ $errors->first('password_confirmation') }}">
                <!-- Champ pour la confirmation du mot de passe avec validation côté client et affichage des erreurs -->
                @error('password_confirmation')
                    <div class="alert alert-danger">{{ $message }}</div>
                    <!-- Affichage des erreurs de validation de la confirmation du mot de passe -->
                @enderror
            </div>
        </div>

        <div class="inline-fields">
            <!-- Champs pour le nom et le prénom affichés côte à côte -->
            <div>
                <label for="reg_nom">Nom:</label>
                <input type="text" id="reg_nom" name="nom" value="{{ old('nom', Auth::check() ? Auth::user()->nom : '') }}" required minlength="2" maxlength="255" class="@error('nom') is-invalid @enderror" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ $errors->first('nom') }}">
                <!-- Champ pour le nom avec validation côté client et affichage des erreurs -->
                @error('nom')
                    <div class="alert alert-danger">{{ $message }}</div>
                    <!-- Affichage des erreurs de validation du nom -->
                @enderror
            </div>

            <div>
                <label for="reg_prenom">Prénom:</label>
                <input type="text" id="reg_prenom" name="prenom" value="{{ old('prenom', Auth::check() ? Auth::user()->prenom : '') }}" required minlength="2" maxlength="255" class="@error('prenom') is-invalid @enderror" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ $errors->first('prenom') }}">
                <!-- Champ pour le prénom avec validation côté client et affichage des erreurs -->
                @error('prenom')
                    <div class="alert alert-danger">{{ $message }}</div>
                    <!-- Affichage des erreurs de validation du prénom -->
                @enderror
            </div>
        </div>

        <label for="reg_adresse">Adresse:</label>
        <input type="text" id="reg_adresse" name="adresse" value="{{ old('adresse', Auth::check() ? Auth::user()->adresse : '') }}" required minlength="5" maxlength="255" class="@error('adresse') is-invalid @enderror" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ $errors->first('adresse') }}">
        <!-- Champ pour l'adresse avec validation côté client et affichage des erreurs -->
        @error('adresse')
            <div class="alert alert-danger">{{ $message }}</div>
            <!-- Affichage des erreurs de validation de l'adresse -->
        @enderror

        <label for="reg_code_postal">Code postal:</label>
        <input type="text" id="reg_code_postal" name="code_postal" value="{{ old('code_postal', Auth::check() ? Auth::user()->code_postal : '') }}" required minlength="4" maxlength="10" class="@error('code_postal') is-invalid @enderror" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ $errors->first('code_postal') }}">
        <!-- Champ pour le code postal avec validation côté client et affichage des erreurs -->
        @error('code_postal')
            <div class="alert alert-danger">{{ $message }}</div>
            <!-- Affichage des erreurs de validation du code postal -->
        @enderror

        <label for="reg_date_de_naissance">Date de naissance:</label>
        <input type="date" id="reg_date_de_naissance" name="date_de_naissance" value="{{ old('date_de_naissance', Auth::check() ? Auth::user()->date_de_naissance : '') }}" required class="@error('date_de_naissance') is-invalid @enderror" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ $errors->first('date_de_naissance') }}">
        <!-- Champ pour la date de naissance avec validation côté client et affichage des erreurs -->
        @error('date_de_naissance')
            <div class="alert alert-danger">{{ $message }}</div>
            <!-- Affichage des erreurs de validation de la date de naissance -->
        @enderror

        <label for="profile_image">Image de profil:</label>
        <input type="file" id="profile_image" name="profile_image" accept=".jpeg,.jpg,.gif" class="@error('profile_image') is-invalid @enderror" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ $errors->first('profile_image') }}">
        <!-- Champ pour l'image de profil avec validation côté client et affichage des erreurs -->
        @error('profile_image')
            <div class="alert alert-danger">{{ $message }}</div>
            <!-- Affichage des erreurs de validation de l'image de profil -->
        @enderror

        <button type="submit">{{ Auth::check() ? 'Modifier' : 'S\'inscrire' }}</button>
        <!-- Bouton de soumission du formulaire, le texte change selon si l'utilisateur est authentifié ou non -->
    </form>
</section>
