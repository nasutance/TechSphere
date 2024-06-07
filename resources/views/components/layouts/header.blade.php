<header>
    <!-- Début de la section d'en-tête -->

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="logo">
        TechSphere <!-- Affiche le nom du site -->
    </div>

    <link rel="stylesheet" href="{{ asset('assets/css/header.css') }}">
    <!-- Lien vers la feuille de style pour l'en-tête -->

    <nav>
        <!-- Début de la navigation principale -->
        <ul>
            <li><a href="{{ url('/') }}">Accueil</a></li> <!-- Lien vers la page d'accueil -->
            <li><a href="{{ url('/blog') }}">Blog/News</a></li> <!-- Lien vers la page de blog/news -->
            <li><a href="javascript:void(0);" onclick="handleMiniChatClick()">Mini-Chat</a></li> <!-- Lien vers la page de mini-chat avec vérification -->
            <li><a href="{{ route('shop.index') }}">Boutique</a></li> <!-- Lien vers la boutique -->

            @if(Auth::check() && Auth::user()->role === 'admin')
                <!-- Vérifie si l'utilisateur est authentifié et est un administrateur -->
                <li><a href="{{ route('admin.index') }}">Admin</a></li> <!-- Lien vers la page admin -->
            @endif
        </ul>
    </nav>

    @if(Auth::check())
        <!-- Vérifie si l'utilisateur est authentifié -->
        <div class="user-info">
            @if(Auth::user()->profile_image)
                <!-- Vérifie si l'utilisateur a une image de profil -->
                <a href="{{ route('shop.orders') }}"><img src="{{ asset(Auth::user()->profile_image) }}" alt="Profile Image" class="profile-image"></a>
                <!-- Affiche l'image de profil de l'utilisateur -->
            @endif
            <div class="user-details">
                <a href="{{ route('profile.show') }}">{{ Auth::user()->username }}</a>
                <!-- Lien vers le profil de l'utilisateur -->
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Déconnexion</a>
                <!-- Lien de déconnexion -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf <!-- Formulaire de déconnexion -->
                </form>
            </div>
        </div>
    @endif

    @isset($title)
        <!-- Vérifie si la variable $title est définie -->
        <div class="header-title">
            <h1>{{ $title }}</h1> <!-- Affiche le titre de la page -->
        </div>
    @endisset
</header>

<script src="{{ asset('js/header.js') }}"></script>
<!-- Lien vers le fichier JavaScript pour l'en-tête -->

<script>
    function handleMiniChatClick() {
        @if(Auth::check())
            // Redirige vers la page mini-chat si l'utilisateur est authentifié
            window.location.href = "{{ route('chat.index') }}";
        @else
            // Affiche un message d'erreur et redirige vers la page d'accueil si l'utilisateur n'est pas authentifié
            alert('Veuillez vous connecter pour accéder au mini-chat.');
            window.location.href = "{{ route('login') }}";
        @endif
    }
</script>
