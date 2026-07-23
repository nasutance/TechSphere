<x-layouts.base :title="$post->title">
    <link rel="stylesheet" href="{{ asset('assets/css/content-style.css') }}">

    <section class="content-container">
        <article class="post-content">
            @if(auth()->check() && auth()->user()->isAdmin())
                <button onclick="toggleEditMode()">Editer</button>

                <form id="edit-post-form" action="{{ route('post.update', $post->post_id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('PUT')
                    <input type="text" name="title" id="edit-title" value="{{ $post->title }}" required>
                    <textarea name="lead" id="edit-lead" required>{{ $post->lead }}</textarea>
                    <textarea name="content" id="edit-content" required>{{ $post->content }}</textarea>
                    <button type="submit">Valider</button>
                </form>
            @endif

            <p class="lead" id="post-lead">{{ $post->lead }}</p>
            <p id="post-content">{{ $post->content }}</p>
        </article>

        <aside class="comments-section">
            <h4>Commentaires</h4>
            @foreach($post->comments as $comment)
                @if($comment->visible || (auth()->check() && auth()->user()->isAdmin()))
                    <section class="comment" id="comment-{{ $comment->comment_id }}">
                        <p>{{ $comment->content }}</p>
                        <span>Par {{ $comment->user->username }}</span>

                        @if(auth()->check() && auth()->user()->isAdmin())
                            @if($comment->visible)
                                <span style="color: green;">(Visible)</span>
                            @else
                                <span style="color: red;">(Non visible)</span>
                            @endif
                        @endif

                        @auth
                            <button onclick="toggleReplyMode({{ $comment->comment_id }}, '{{ $comment->user->username }}')">Répondre</button>
                            <form id="reply-form-{{ $comment->comment_id }}" action="{{ route('comment.store') }}" method="POST" style="display: none;">
                                @csrf
                                <textarea name="content">{{ '@' . $comment->user->username }} </textarea>
                                <input type="hidden" name="post_id" value="{{ $post->post_id }}">
                                <button type="submit">Valider</button>
                            </form>
                        @endauth

                        @if(auth()->check() && auth()->user()->isAdmin())
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
                            <form id="edit-comment-form-{{ $comment->comment_id }}" action="{{ route('comment.update', $comment->comment_id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('PUT')
                                <textarea name="content">{{ $comment->content }}</textarea>
                                <button type="submit">Enregistrer les modifications</button>
                            </form>
                            <form action="{{ route('comment.destroy', $comment->comment_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Supprimer</button>
                            </form>
                        @endif
                    </section>
                @endif
            @endforeach

            @auth
                <button onclick="comment()">Commenter</button>
                <form id="comment-form" action="{{ route('comment.store') }}" method="POST" style="display: none;">
                    @csrf
                    <textarea name="content" id="comment-content" required></textarea>
                    <input type="hidden" name="post_id" value="{{ $post->post_id }}">
                    <button type="submit">Valider</button>
                </form>
            @else
                <p>Veuillez <a href="{{ route('login') }}" id="login-link">vous connecter</a> pour commenter.</p>
            @endauth
        </aside>
    </section>

    <section id="login-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeLoginModal()">&times;</span>
            @include('components.login-section')
        </div>
    </section>

    <script>
        const loginLink = document.getElementById('login-link');
        if (loginLink) {
            loginLink.addEventListener('click', function(event) {
                event.preventDefault();
                showLoginModal();
            });
        }

        function showLoginModal() {
            document.getElementById('login-modal').style.display = 'block';
        }

        function closeLoginModal() {
            document.getElementById('login-modal').style.display = 'none';
        }

        function toggleEditMode() {
            document.getElementById('post-lead').style.display = 'none';
            document.getElementById('post-content').style.display = 'none';
            document.getElementById('edit-post-form').style.display = 'block';
        }

        function toggleCommentEditMode(commentId) {
            document.querySelector(`#comment-${commentId} p`).style.display = 'none';
            document.querySelector(`#edit-comment-form-${commentId}`).style.display = 'block';
        }

        function toggleReplyMode(commentId, username) {
            const replyForm = document.querySelector(`#reply-form-${commentId}`);
            replyForm.querySelector('textarea').value = `@${username} `;
            replyForm.style.display = 'block';
        }

        function comment() {
            document.getElementById('comment-form').style.display = 'block';
        }
    </script>
</x-layouts.base>
