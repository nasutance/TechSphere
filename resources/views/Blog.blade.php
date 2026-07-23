<x-layouts.base :title="'Blog'">
    <link rel="stylesheet" href="{{ asset('assets/css/posts-style.css') }}">

    <div class="container">
        <div class="search-bar">
            <form action="{{ route('blog.search') }}" method="GET">
                <input type="text" name="search" placeholder="Rechercher un article..." required>
                <button type="submit">Rechercher</button>
            </form>
        </div>

        @if(auth()->check() && auth()->user()->isAdmin())
            <div class="admin-section">
                <div class="new-post-button">
                    <button onclick="toggleNewPostForm()">Nouveau Post</button>
                </div>

                <div id="new-post-form" style="display: none;">
                    <form action="{{ route('blog.store') }}" method="POST">
                        @csrf
                        <div>
                            <label for="title">Titre</label>
                            <input type="text" name="title" id="title" required>
                        </div>
                        <div>
                            <label for="lead">Lead</label>
                            <textarea name="lead" id="lead" required></textarea>
                        </div>
                        <div>
                            <label for="content">Contenu</label>
                            <textarea name="content" id="content" required></textarea>
                        </div>
                        <button type="submit">Créer</button>
                    </form>
                </div>

                <h2>Suppression des articles de blog</h2>
                <form action="{{ route('blog.delete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <select name="post_id" required>
                        @foreach($posts as $post)
                            <option value="{{ $post->post_id }}">{{ $post->title }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-danger">Supprimer un article</button>
                </form>
            </div>
        @endif

        <div class="posts-grid">
            @forelse ($posts as $post)
                <div class="article">
                    <h2><a href="{{ route('blog.show', ['id' => $post->post_id]) }}">{{ $post->title }}</a></h2>
                    <p>{{ Str::limit($post->lead, 150) }}</p>
                    <span>Publié le {{ $post->created_at->format('d/m/Y') }}</span>
                </div>
            @empty
                <p>Aucun article trouvé.</p>
            @endforelse
        </div>
    </div>

    <script>
        function toggleNewPostForm() {
            var form = document.getElementById('new-post-form');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</x-layouts.base>
