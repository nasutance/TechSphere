<x-layouts.base :title="'Bienvenue sur TechSphere'">
    <!-- Utilise le layout de base avec le titre 'Bienvenue sur TechSphere' -->

    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <!-- Lie le fichier CSS pour le style de la page d'accueil -->

    <section>
        <!-- Section d'accueil -->
        <p class="welcome-text">Votre destination ultime pour les dernières nouvelles technologiques, les discussions en ligne et les achats de matériel informatique.</p>
        <!-- Paragraphe de bienvenue -->

        <!-- Affichage du message d'erreur si présent -->
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <section class="services-container">
            <!-- Conteneur pour les services disponibles à tous les utilisateurs -->
            <h2>Services disponibles pour tous les utilisateurs</h2>
            <!-- Titre de la section des services accessibles à tous -->

            <article class="service-box">
                <!-- Conteneur pour le service Blog/News -->
                <h3>Blog/News</h3>
                <!-- Titre du service Blog/News -->
                <p>Accédez à nos articles de blog et aux dernières nouvelles technologiques. <a href="{{ url('/blog') }}"><br>Visitez notre blog</a>.</p>
                <!-- Description du service avec un lien vers le blog -->
            </article>

            <article class="service-box">
                <!-- Conteneur pour le service Boutique -->
                <h3>Boutique</h3>
                <!-- Titre du service Boutique -->
                <p>Parcourez et achetez du matériel informatique de haute qualité. <a href="{{ url('/shop') }}"><br>Découvrez notre boutique</a>.</p>
                <!-- Description du service avec un lien vers la boutique -->
            </article>
        </section>

        <section class="services-container">
            <!-- Conteneur pour les services réservés aux utilisateurs connectés -->
            <h2>Services réservés aux utilisateurs connectés</h2>
            <!-- Titre de la section des services réservés aux utilisateurs connectés -->

            @auth
                <!-- Affiche ces services seulement si l'utilisateur est connecté -->
                <article class="service-box">
                    <!-- Conteneur pour le service Mini-Chat -->
                    <h3>Mini-Chat</h3>
                    <!-- Titre du service Mini-Chat -->
                    <p>Participez à des discussions en ligne avec d'autres membres de la communauté. <a href="{{ url('/mini-chat') }}"><br>Accédez au Mini-Chat</a>.</p>
                    <!-- Description du service avec un lien vers le Mini-Chat -->
                </article>

                <article class="service-box">
                    <!-- Conteneur pour le service Mon Profil -->
                    <h3>Mon Profil</h3>
                    <!-- Titre du service Mon Profil -->
                    <p>Accédez à vos informations personnelles et gérez votre compte. <a href="{{ url('/profile') }}"><br>Voir Mon Profil</a>.</p>
                    <!-- Description du service avec un lien vers le profil de l'utilisateur -->
                </article>

                <article class="service-box">
                    <!-- Conteneur pour le service Mes Achats -->
                    <h3>Mes Achats</h3>
                    <!-- Titre du service Mes Achats -->
                    <p>Consultez l'historique de vos achats et suivez vos commandes. <a href="{{ url('/orders') }}"><br>Voir Mes Achats</a>.</p>
                    <!-- Description du service avec un lien vers les achats de l'utilisateur -->
                </article>
            @else
                <!-- Affiche ces services si l'utilisateur n'est pas connecté -->
                <article class="service-box">
                    <!-- Conteneur pour le service Mini-Chat -->
                    <h3>Mini-Chat</h3>
                    <!-- Titre du service Mini-Chat -->
                    <p>Participez à des discussions en ligne avec d'autres membres de la communauté. <a href="javascript:void(0);" onclick="showLoginModal()"><br>Connectez-vous</a>.</p>
                    <!-- Description du service avec un lien pour se connecter -->
                </article>

                <article class="service-box">
                    <!-- Conteneur pour le service Mon Profil -->
                    <h3>Mon Profil</h3>
                    <!-- Titre du service Mon Profil -->
                    <p>Accédez à vos informations personnelles et gérez votre compte. <a href="javascript:void(0);" onclick="showLoginModal()"><br>Connectez-vous</a>.</p>
                    <!-- Description du service avec un lien pour se connecter -->
                </article>

                <article class="service-box">
                    <!-- Conteneur pour le service Mes Achats -->
                    <h3>Mes Achats</h3>
                    <!-- Titre du service Mes Achats -->
                    <p>Consultez l'historique de vos achats et suivez vos commandes. <a href="javascript:void(0);" onclick="showLoginModal()"><br>Connectez-vous</a>.</p>
                    <!-- Description du service avec un lien pour se connecter -->
                </article>
            @endauth
        </section>

        <!-- Ajout de la section de connexion -->
        <section id="login-modal" class="modal">
            <!-- Section pour la modal de connexion -->
            <div class="modal-content">
                <!-- Contenu de la modal -->
                <span class="close" onclick="closeLoginModal()">&times;</span>
                <!-- Bouton pour fermer la modal -->
                @include('components.login-section')
                <!-- Inclusion de la section de connexion -->
            </div>
        </section>

        <script>
            function showLoginModal() {
                document.getElementById('login-modal').style.display = 'block';
                <!-- Fonction JavaScript pour afficher la modal de connexion -->
            }

            function closeLoginModal() {
                document.getElementById('login-modal').style.display = 'none';
                <!-- Fonction JavaScript pour cacher la modal de connexion -->
            }
        </script>

    </x-layouts.base>
    <!-- Fin du layout de base -->
