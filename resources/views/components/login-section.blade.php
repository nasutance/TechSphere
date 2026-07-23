@if(!Auth::check())
<section id="login">
    <link rel="stylesheet" href="{{ asset('assets/css/login-style.css') }}">

    <h2>Connexion</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ url('/login') }}" method="post">
        @csrf

        <label for="username">Nom d'utilisateur:<br></label>
        <input type="text" id="username" name="username" required minlength="3" maxlength="255" class="@error('username') is-invalid @enderror" title="{{ $errors->first('username') }}">
        @error('username')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <label for="password"><br>Mot de passe:<br></label>
        <input type="password" id="password" name="password" required class="@error('password') is-invalid @enderror" title="{{ $errors->first('password') }}">
        @error('password')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <button type="submit">Se connecter</button>
    </form>

    <p>Vous n'avez pas de compte ? <a href="{{ route('register') }}">Inscrivez-vous ici</a>.</p>

    <script src="{{ asset('assets/js/label.js') }}" defer></script>
</section>
@endif
