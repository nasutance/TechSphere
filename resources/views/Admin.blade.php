<x-layouts.base :title="'Admin Dashboard'">
    <link rel="stylesheet" href="{{ asset('assets/css/Admin.css') }}">
    <style>
        footer {
            clear: both;
            width: 100%;
            text-align: center;
            padding: 1rem 0;
            background: rgba(255, 255, 255, 0.0);
            position: relative;
        }
    </style>
    <div class="admin-container">
        <h2>Liste des utilisateurs</h2>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table>
            <thead>
                <tr>
                    <th>Nom d'utilisateur</th>
                    <th>Email</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Adresse</th>
                    <th>Code Postal</th>
                    <th>Date de Naissance</th>
                    <th>Image de Profil</th>
                    <th>Rôle</th>
                    <th>Connexions Aujourd'hui</th>
                    <th>Connexions 7 derniers jours</th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->nom }}</td>
                        <td>{{ $user->prenom }}</td>
                        <td>{{ $user->adresse }}</td>
                        <td>{{ $user->code_postal }}</td>
                        <td>{{ $user->date_de_naissance }}</td>
                        <td>
                            @if($user->profile_image)
                                <img src="{{ asset($user->profile_image) }}" alt="Profile Image" class="profile-image">
                            @endif
                        </td>
                        <td>{{ $user->role }}</td>
                        <td>{{ $user->logins_today }}</td>
                        <td>{{ $user->logins_last_7_days }}</td>
                        <td class="actions">
                            <a href="{{ route('shop.userOrders', $user->user_id) }}" class="btn btn-warning">Voir Achats</a>
                            <a href="javascript:void(0);" class="btn btn-secondary" onclick="showComments({{ $user->user_id }})">Commentaires</a>
                            @if($user->role !== 'blocked' && Auth::id() !== $user->user_id)
                                <form action="{{ route('admin.blockUser', $user->user_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-danger">Bloquer</button>
                                </form>
                            @elseif(Auth::id() !== $user->user_id)
                                <form action="{{ route('admin.unblockUser', $user->user_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success">Débloquer</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div id="comments-section" style="display:none;">
            <table id="comments-table">
                <thead>
                    <tr>
                        <th>Derniers Commentaires</th>
                        <th>Date</th>
                        <th>Post</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Les commentaires seront ajoutés ici via JavaScript -->
                </tbody>
            </table>
        </div>

        <div class="new-comments-notifications">
            <table>
                <thead>
                    <tr>
                        <th>Commentaire à vérifier</th>
                        <th>Auteur</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($newComments as $comment)
                        <tr>
                            <td>
                                <a href="{{ route('blog.show', $comment->post_id) }}#comment-{{ $comment->comment_id }}">
                                    "{{ $comment->post_title }}"
                                </a>
                            </td>
                            <td>{{ $comment->author_name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">Aucun nouveau commentaire à vérifier</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function showComments(userId) {
            fetch(`/admin/user/${userId}/comments`)
                .then(response => response.json())
                .then(comments => {
                    const commentsTableBody = document.querySelector('#comments-table tbody');
                    commentsTableBody.innerHTML = '';
                    comments.forEach(comment => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${comment.content}</td>
                            <td>${new Date(comment.created_at).toLocaleDateString()}</td>
                            <td>${comment.post_title}</td>
                        `;
                        commentsTableBody.appendChild(row);
                    });
                    document.getElementById('comments-section').style.display = 'block';
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</x-layouts.base>
