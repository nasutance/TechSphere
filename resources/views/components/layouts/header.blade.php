<header>
    <link rel="stylesheet" href="{{ asset('assets/css/header.css') }}">

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="logo">
        TechSphere
    </div>

    <nav>
        <ul>
            <li><a href="{{ url('/') }}">Accueil</a></li>
            <li><a href="{{ route('blog.index') }}">Blog/News</a></li>
            <li><a href="javascript:void(0);" onclick="handleMiniChatClick()">Mini-Chat</a></li>
            <li><a href="{{ route('shop.index') }}">Boutique</a></li>

            @if(Auth::check() && Auth::user()->isAdmin())
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
            @endif
        </ul>
    </nav>

    @auth
        <div class="user-info">
            @if(Auth::user()->profile_image)
                <a href="{{ route('shop.orders') }}"><img src="{{ Auth::user()->profile_image_url }}" alt="Photo de profil" class="profile-image"></a>
            @endif
            <div class="user-details">
                <a href="{{ route('profile.show') }}">{{ Auth::user()->username }}</a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Déconnexion</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    @endauth

    @isset($title)
        <div class="header-title">
            <h1>{{ $title }}</h1>
        </div>
    @endisset
</header>

<script src="{{ asset('assets/js/header.js') }}" defer></script>

<script>
    function handleMiniChatClick() {
        @auth
            window.location.href = "{{ route('chat.index') }}";
        @else
            alert('Veuillez vous connecter pour accéder au mini-chat.');
            window.location.href = "{{ route('login') }}";
        @endauth
    }
</script>
