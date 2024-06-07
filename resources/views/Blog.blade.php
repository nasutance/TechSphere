<x-layouts.base :title="'Blog'">
<!-- Utilise le layout de base avec le titre 'Blog' -->

<head>
    <meta charset="UTF-8">
    <!-- Définit le jeu de caractères en UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Définit la vue pour les appareils mobiles -->
    <link rel="stylesheet" href="{{ asset('assets/css/posts-style.css') }}">
    <!-- Lie le fichier CSS pour le style des posts -->
</head>

<body>
<div class="container">
    <!-- Conteneur principal -->

    <div class="search-bar">
        <!-- Barre de recherche -->
        <form action="{{ route('blog.search') }}" method="GET">
            <!-- Formulaire de recherche envoyé en méthode GET -->
            <input type="text" name="search" placeholder="Rechercher un article..." required>
            <!-- Champ de saisie pour la recherche avec un placeholder -->
            <button type="submit">Rechercher</button>
            <!-- Bouton de soumission pour la recherche -->
        </form>
    </div>

    @if(auth()->check() && auth()->user()->role === 'admin')
        <!-- Affiche cette section seulement si l'utilisateur est un admin -->
        <div class="admin-section">
            <!-- Section admin -->
            <div class="new-post-button">
                <!-- Bouton pour afficher le formulaire de création de nouveau post -->
                <button onclick="toggleNewPostForm()">Nouveau Post</button>
                <!-- Bouton avec fonction JavaScript pour afficher/cacher le formulaire de nouveau post -->
            </div>

            <div id="new-post-form" style="display: none;">
                <!-- Formulaire de création de nouveau post, caché par défaut -->
                <form action="{{ route('blog.store') }}" method="POST">
                    <!-- Formulaire pour créer un nouveau post, envoyé en méthode POST -->
                    @csrf
                    <!-- Protection CSRF -->
                    <div>
                        <label for="title">Titre</label>
                        <!-- Label pour le titre -->
                        <input type="text" name="title" id="title" required>
                        <!-- Champ de saisie pour le titre -->
                    </div>
                    <div>
                        <label for="lead">Lead</label>
                        <!-- Label pour le lead -->
                        <textarea name="lead" id="lead" required></textarea>
                        <!-- Champ de texte pour le lead -->
                    </div>
                    <div>
                        <label for="content">Contenu</label>
                        <!-- Label pour le contenu -->
                        <textarea name="content" id="content" required></textarea>
                        <!-- Champ de texte pour le contenu -->
                    </div>
                    <button type="submit">Créer</button>
                    <!-- Bouton de soumission pour créer le post -->
                </form>
            </div>

            <h2>Suppression des articles de blog</h2>
            <!-- Titre pour la section de suppression des articles -->
            <form action="{{ route('blog.delete') }}" method="POST">
                <!-- Formulaire pour supprimer un article, envoyé en méthode POST -->
                @csrf
                <!-- Protection CSRF -->
                @method('DELETE')
                <!-- Méthode DELETE -->
                <select name="post_id" required>
                    <!-- Sélecteur pour choisir quel article supprimer -->
                    @foreach($posts as $post)
                        <option value="{{ $post->post_id }}">{{ $post->title }}</option>
                        <!-- Options du sélecteur avec les titres des articles -->
                    @endforeach
                </select>
                <button type="submit" class="btn btn-danger">Supprimer un article</button>
                <!-- Bouton de soumission pour supprimer l'article -->
            </form>
        </div>
    @endif

    <div class="posts-grid">
        <!-- Grille pour afficher les articles -->
        @if(isset($posts) && $posts->count() > 0)
            <!-- Vérifie s'il y a des articles à afficher -->
            @foreach ($posts as $post)
                <!-- Boucle pour afficher chaque article -->
                <div class="article">
                    <!-- Conteneur pour un article -->
                    <h2><a href="{{ route('blog.show', ['id' => $post->post_id]) }}">{{ $post->title }}</a></h2>
                    <!-- Titre de l'article avec un lien vers sa page -->
                    <p>{{ Str::limit($post->lead, 150) }}</p>
                    <!-- Affiche un extrait du lead de l'article (limité à 150 caractères) -->
                    <span>Publié le {{ $post->created_at->format('d/m/Y') }}</span>
                    <!-- Date de publication de l'article formatée en jour/mois/année -->
                </div>
            @endforeach
        @else
            <p>Aucun article trouvé.</p>
            <!-- Message affiché s'il n'y a aucun article -->
        @endif
    </div>
</div>

<script>
    function toggleNewPostForm() {
        var form = document.getElementById('new-post-form');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
        <!-- Fonction JavaScript pour afficher ou cacher le formulaire de nouveau post -->
    }
</script>
</body>
</x-layouts.base>
<!-- Fin du layout de base -->
