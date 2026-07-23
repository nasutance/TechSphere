<x-layouts.base :title="'Bienvenue sur TechSphere'">
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">

    <section>
        <p class="welcome-text">Votre destination ultime pour les dernières nouvelles technologiques, les discussions en ligne et les achats de matériel informatique.</p>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <section class="services-container">
            <h2>Services disponibles pour tous les utilisateurs</h2>

            <article class="service-box">
                <h3>Blog/News</h3>
                <p>Accédez à nos articles de blog et aux dernières nouvelles technologiques. <a href="{{ route('blog.index') }}"><br>Visitez notre blog</a>.</p>
            </article>

            <article class="service-box">
                <h3>Boutique</h3>
                <p>Parcourez et achetez du matériel informatique de haute qualité. <a href="{{ route('shop.index') }}"><br>Découvrez notre boutique</a>.</p>
            </article>
        </section>

        <section class="services-container">
            <h2>Services réservés aux utilisateurs connectés</h2>

            @auth
                <article class="service-box">
                    <h3>Mini-Chat</h3>
                    <p>Participez à des discussions en ligne avec d'autres membres de la communauté. <a href="{{ route('chat.index') }}"><br>Accédez au Mini-Chat</a>.</p>
                </article>

                <article class="service-box">
                    <h3>Mon Profil</h3>
                    <p>Accédez à vos informations personnelles et gérez votre compte. <a href="{{ route('profile.show') }}"><br>Voir Mon Profil</a>.</p>
                </article>

                <article class="service-box">
                    <h3>Mes Achats</h3>
                    <p>Consultez l'historique de vos achats et suivez vos commandes. <a href="{{ route('shop.orders') }}"><br>Voir Mes Achats</a>.</p>
                </article>
            @else
                <article class="service-box">
                    <h3>Mini-Chat</h3>
                    <p>Participez à des discussions en ligne avec d'autres membres de la communauté. <a href="javascript:void(0);" onclick="showLoginModal()"><br>Connectez-vous</a>.</p>
                </article>

                <article class="service-box">
                    <h3>Mon Profil</h3>
                    <p>Accédez à vos informations personnelles et gérez votre compte. <a href="javascript:void(0);" onclick="showLoginModal()"><br>Connectez-vous</a>.</p>
                </article>

                <article class="service-box">
                    <h3>Mes Achats</h3>
                    <p>Consultez l'historique de vos achats et suivez vos commandes. <a href="javascript:void(0);" onclick="showLoginModal()"><br>Connectez-vous</a>.</p>
                </article>
            @endauth
        </section>

        <section id="login-modal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeLoginModal()">&times;</span>
                @include('components.login-section')
            </div>
        </section>

        <script>
            function showLoginModal() {
                document.getElementById('login-modal').style.display = 'block';
            }

            function closeLoginModal() {
                document.getElementById('login-modal').style.display = 'none';
            }
        </script>
    </section>
</x-layouts.base>
