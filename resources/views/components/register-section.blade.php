<section id="register">
    <link rel="stylesheet" href="{{ asset('assets/css/register-style.css') }}">

    {{-- Le même formulaire sert à l'inscription et à la mise à jour du profil --}}
    <form action="{{ Auth::check() ? route('profile.update') : route('register.post') }}" method="post" enctype="multipart/form-data">
        @csrf
        @if(Auth::check())
            @method('PUT')
        @endif

        <label for="reg_username">Nom d'utilisateur:</label>
        <input type="text" id="reg_username" name="username" value="{{ old('username', Auth::check() ? Auth::user()->username : '') }}" required minlength="3" maxlength="255" class="@error('username') is-invalid @enderror" title="{{ $errors->first('username') }}">
        @error('username')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <label for="reg_email">Email:</label>
        <input type="email" id="reg_email" name="email" value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" required class="@error('email') is-invalid @enderror" title="{{ $errors->first('email') }}">
        @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="inline-fields">
            <div>
                <label for="reg_password">Mot de passe:</label>
                <input type="password" id="reg_password" name="password" minlength="8" @guest required @endguest class="@error('password') is-invalid @enderror" title="{{ $errors->first('password') }}">
                @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="reg_password_confirmation">Confirmez le mot de passe:</label>
                <input type="password" id="reg_password_confirmation" name="password_confirmation" minlength="8" @guest required @endguest class="@error('password_confirmation') is-invalid @enderror" title="{{ $errors->first('password_confirmation') }}">
                @error('password_confirmation')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="inline-fields">
            <div>
                <label for="reg_nom">Nom:</label>
                <input type="text" id="reg_nom" name="nom" value="{{ old('nom', Auth::check() ? Auth::user()->nom : '') }}" required minlength="2" maxlength="255" class="@error('nom') is-invalid @enderror" title="{{ $errors->first('nom') }}">
                @error('nom')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="reg_prenom">Prénom:</label>
                <input type="text" id="reg_prenom" name="prenom" value="{{ old('prenom', Auth::check() ? Auth::user()->prenom : '') }}" required minlength="2" maxlength="255" class="@error('prenom') is-invalid @enderror" title="{{ $errors->first('prenom') }}">
                @error('prenom')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <label for="reg_adresse">Adresse:</label>
        <input type="text" id="reg_adresse" name="adresse" value="{{ old('adresse', Auth::check() ? Auth::user()->adresse : '') }}" required minlength="5" maxlength="255" class="@error('adresse') is-invalid @enderror" title="{{ $errors->first('adresse') }}">
        @error('adresse')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <label for="reg_code_postal">Code postal:</label>
        <input type="text" id="reg_code_postal" name="code_postal" value="{{ old('code_postal', Auth::check() ? Auth::user()->code_postal : '') }}" required minlength="4" maxlength="10" class="@error('code_postal') is-invalid @enderror" title="{{ $errors->first('code_postal') }}">
        @error('code_postal')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <label for="reg_date_de_naissance">Date de naissance:</label>
        <input type="date" id="reg_date_de_naissance" name="date_de_naissance" value="{{ old('date_de_naissance', Auth::check() ? Auth::user()->date_de_naissance : '') }}" required class="@error('date_de_naissance') is-invalid @enderror" title="{{ $errors->first('date_de_naissance') }}">
        @error('date_de_naissance')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <label for="profile_image">Image de profil:</label>
        <input type="file" id="profile_image" name="profile_image" accept=".jpeg,.jpg,.png,.gif" class="@error('profile_image') is-invalid @enderror" title="{{ $errors->first('profile_image') }}">
        @error('profile_image')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <button type="submit">{{ Auth::check() ? 'Modifier' : 'S\'inscrire' }}</button>
    </form>
</section>
