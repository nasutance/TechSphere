<x-layouts.base :title="$post->title">
    <!-- Utilise le layout de base avec le titre du post -->

    <head>
        <meta charset="UTF-8">
        <!-- Définit le jeu de caractères en UTF-8 -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Définit la vue pour les appareils mobiles -->
        <title>{{ $post->title }}</title>
        <!-- Titre de la page dynamiquement défini avec le titre du post -->
        <link rel="stylesheet" href="{{ asset('assets/css/content-style.css') }}">
        <!-- Lie le fichier CSS pour le style du contenu -->
    </head>

    <body>
        <section class="content-container">
            <!-- Conteneur principal du contenu -->
            <article class="post-content">
                <!-- Conteneur de l'article -->

                @if(auth()->check() && auth()->user()->role === 'admin')
                    <!-- Affiche les boutons d'édition si l'utilisateur est un admin -->
                    <button onclick="toggleEditMode()">Editer</button>
                    <!-- Bouton pour activer le mode édition -->

                    <form id="edit-post-form" action="{{ route('post.update', $post->post_id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('PUT')
                        <!-- Formulaire de mise à jour du post -->
                        <input type="text" name="title" id="edit-title" value="{{ $post->title }}">
                        <!-- Champ de texte pour éditer le titre -->
                        <textarea name="lead" id="edit-lead">{{ $post->lead }}</textarea>
                        <!-- Champ de texte pour éditer le lead -->
                        <textarea name="content" id="edit-content">{{ $post->content }}</textarea>
                        <!-- Champ de texte pour éditer le contenu -->
                        <button type="submit">Valider</button>
                        <!-- Bouton pour valider les modifications -->
                    </form>
                @endif

                <p class="lead" id="post-lead">{{ $post->lead }}</p>
                <!-- Affiche le lead du post -->
                <p id="post-content">{{ $post->content }}</p>
                <!-- Affiche le contenu du post -->
            </article>

            <aside class="comments-section">
                <!-- Section pour les commentaires -->
                <h4>Commentaires</h4>
                @foreach($post->comments as $comment)
                    @if($comment->visible || (auth()->check() && auth()->user()->role === 'admin'))
                        <!-- Affiche le commentaire s'il est visible ou si l'utilisateur est admin -->
                        <section class="comment" id="comment-{{ $comment->comment_id }}">
                            <p>{{ $comment->content }}</p>
                            <!-- Contenu du commentaire -->
                            <span>Par {{ $comment->user->username }}</span>
                            <!-- Nom de l'utilisateur qui a posté le commentaire -->

                            <!-- Affiche la visibilité du commentaire pour l'admin -->
                            @if(auth()->check() && auth()->user()->role === 'admin')
                                @if($comment->visible)
                                    <span style="color: green;">(Visible)</span>
                                @else
                                    <span style="color: red;">(Non visible)</span>
                                @endif
                            @endif

                            @if(auth()->check())
                                <!-- Affiche le bouton de réponse pour tous les utilisateurs connectés -->
                                <button onclick="toggleReplyMode({{ $comment->comment_id }}, '{{ $comment->user->username }}')">Répondre</button>
                                <!-- Bouton pour activer le mode réponse -->
                                <form id="reply-form-{{ $comment->comment_id }}" action="{{ route('comment.store') }}" method="POST" style="display: none;">
                                    @csrf
                                    <textarea name="content">@{{ $comment->user->username }} </textarea>
                                    <!-- Champ de texte pour la réponse -->
                                    <input type="hidden" name="post_id" value="{{ $post->post_id }}">
                                    <!-- Champ caché avec l'ID du post -->
                                    <button type="submit">Valider</button>
                                    <!-- Bouton pour valider la réponse -->
                                </form>
                            @endif

                            @if(auth()->check() && auth()->user()->role === 'admin')
                                <!-- Affiche les boutons de modération pour l'admin -->
                                @if(!$comment->visible)
                                    <form action="{{ route('comment.approve', $comment->comment_id) }}" method="POST">
                                        @csrf
                                        <button type="submit">Approuver</button>
                                    </form>
                                @else
                                    <form action="{{ route('comment.hide', $comment->comment_id) }}" method="POST">
                                        @csrf
                                        <button type="submit">Cacher</button>
                                    </form>
                                @endif
                                <button onclick="toggleCommentEditMode({{ $comment->comment_id }})">Modérer</button>
                                <!-- Bouton pour activer le mode modération -->
                                <form id="edit-comment-form-{{ $comment->comment_id }}" action="{{ route('comment.update', $comment->comment_id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('PUT')
                                    <textarea name="content">{{ $comment->content }}</textarea>
                                    <!-- Champ de texte pour éditer le commentaire -->
                                    <button type="submit">Enregistrer les modifications</button>
                                    <!-- Bouton pour valider les modifications -->
                                </form>
                                <form action="{{ route('comment.destroy', $comment->comment_id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Supprimer</button>
                                    <!-- Bouton pour supprimer le commentaire -->
                                </form>
                            @endif
                        </section>
                    @endif
                @endforeach

                @if(auth()->check())
                    <!-- Affiche le bouton de commentaire pour les utilisateurs connectés -->
                    <button onclick="comment()">Commenter</button>
                    <form id="comment-form" action="{{ route('comment.store') }}" method="POST" style="display: none;">
                        @csrf
                        <textarea name="content" id="comment-content"></textarea>
                        <!-- Champ de texte pour écrire un commentaire -->
                        <input type="hidden" name="post_id" value="{{ $post->post_id }}">
                        <!-- Champ caché avec l'ID du post -->
                        <button type="submit">Valider</button>
                        <!-- Bouton pour valider le commentaire -->
                    </form>
                @else
                    <p>Veuillez <a href="{{ route('login') }}" id="login-link">vous connecter</a> pour commenter.</p>
                    <!-- Lien vers la page de connexion si l'utilisateur n'est pas connecté -->
                @endif
            </aside>
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
            document.getElementById('login-link').addEventListener('click', function(event) {
                event.preventDefault();
                showLoginModal();
                <!-- Affiche la modal de connexion quand le lien de connexion est cliqué -->
            });

            function showLoginModal() {
                document.getElementById('login-modal').style.display = 'block';
                <!-- Fonction pour afficher la modal de connexion -->
            }

            function closeLoginModal() {
                document.getElementById('login-modal').style.display = 'none';
                <!-- Fonction pour cacher la modal de connexion -->
            }

            function toggleEditMode() {
                document.getElementById('post-lead').style.display = 'none';
                document.getElementById('post-content').style.display = 'none';
                document.getElementById('edit-post-form').style.display = 'block';
                <!-- Fonction pour activer le mode édition du post -->
            }

            function toggleCommentEditMode(commentId) {
                const commentContent = document.querySelector(`#comment-${commentId} p`);
                const editForm = document.querySelector(`#edit-comment-form-${commentId}`);
                commentContent.style.display = 'none';
                editForm.style.display = 'block';
                <!-- Fonction pour activer le mode édition d'un commentaire -->
            }

            function toggleReplyMode(commentId, username) {
                const replyForm = document.querySelector(`#reply-form-${commentId}`);
                const textarea = replyForm.querySelector('textarea');
                textarea.value = `@${username} `;
                replyForm.style.display = 'block';
                <!-- Fonction pour activer le mode réponse à un commentaire -->
            }

            function comment() {
                document.getElementById('comment-form').style.display = 'block';
                <!-- Fonction pour afficher le formulaire de commentaire -->
            }
        </script>
    </body>
</x-layouts.base>
<!-- Fin du layout de base -->
