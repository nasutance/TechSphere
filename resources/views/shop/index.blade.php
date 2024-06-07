<x-layouts.base :title="'Boutique'">
    <!-- Utilise la mise en page de base et définit le titre de la page -->

    <link rel="stylesheet" href="{{ asset('assets/css/shop-style.css') }}">
    <!-- Lien vers le fichier CSS -->

    <div class="shop-container">
        <!-- Conteneur principal pour la boutique -->

        @if(auth()->check() && auth()->user()->role === 'admin')
            <!-- Section visible uniquement pour les administrateurs authentifiés -->
            <div class="admin-section">
                <h2>Gestion des articles de vente</h2>
                <form action="{{ route('shop.addItem') }}" method="POST">
                    @csrf
                    <input type="text" name="name" placeholder="Nom de l'article" required>
                    <input type="number" step="0.01" name="price" placeholder="Prix" required>
                    <select name="category_id" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-success">Ajouter un article</button>
                </form>

                <h2>Suppression des articles de vente</h2>
                <form action="{{ route('shop.deleteItem') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <select name="item_id" required>
                        @foreach($items as $item)
                            <option value="{{ $item->item_id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-danger">Supprimer un article</button>
                </form>
            </div>
        @endif

        <div class="cart">
            <!-- Section du panier -->
            @include('shop.cart')
        </div>

        <div class="items-list">
            <!-- Liste des articles disponibles à la vente -->

            @if(session('error'))
                <!-- Affiche un message d'erreur si présent dans la session -->
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <!-- Affiche un message de succès si présent dans la session -->
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @foreach($categories as $category)
                <!-- Boucle à travers chaque catégorie -->
                <div class="category">
                    <h2>{{ $category->name }}</h2>
                    <!-- Nom de la catégorie -->
                    <ul>
                        <!-- Liste des articles dans cette catégorie -->
                        @foreach($category->items as $item)
                            <li>
                                <span>{{ $item->name }} - {{ $item->price }} €</span>
                                @if(auth()->check())
                                    <!-- Formulaire pour ajouter l'article au panier, visible uniquement si l'utilisateur est authentifié -->
                                    <form action="{{ route('cart.update', $item->item_id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="quantity" value="1">
                                        <input type="hidden" name="redirect" value="{{ url()->full() }}">
                                        <button type="submit">Ajouter au panier</button>
                                    </form>
                                @else
                                    <!-- Bouton pour afficher le modal de connexion si l'utilisateur n'est pas authentifié -->
                                    <button onclick="showLoginModal()">Ajouter au panier</button>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

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
        // Fonction pour afficher le modal de connexion
        function showLoginModal() {
            document.getElementById('login-modal').style.display = 'flex';
        }

        // Fonction pour fermer le modal de connexion
        function closeLoginModal() {
            document.getElementById('login-modal').style.display = 'none';
        }
    </script>
</x-layouts.base>
