<x-layouts.base :title="'Boutique'">
    <link rel="stylesheet" href="{{ asset('assets/css/shop-style.css') }}">

    <div class="shop-container">
        @if(auth()->check() && auth()->user()->isAdmin())
            <div class="admin-section">
                <h2>Gestion des articles de vente</h2>
                <form action="{{ route('shop.addItem') }}" method="POST">
                    @csrf
                    <input type="text" name="name" placeholder="Nom de l'article" required>
                    <input type="number" step="0.01" min="0" name="price" placeholder="Prix" required>
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
            @include('shop.cart')
        </div>

        <div class="items-list">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @foreach($categories as $category)
                <div class="category">
                    <h2>{{ $category->name }}</h2>
                    <ul>
                        @foreach($category->items as $item)
                            <li>
                                <span>{{ $item->name }} - {{ number_format($item->price, 2, ',', ' ') }} €</span>
                                @auth
                                    <form action="{{ route('cart.update', $item->item_id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="quantity" value="1">
                                        <input type="hidden" name="redirect" value="{{ url()->full() }}">
                                        <button type="submit">Ajouter au panier</button>
                                    </form>
                                @else
                                    <button onclick="showLoginModal()">Ajouter au panier</button>
                                @endauth
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

    <section id="login-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeLoginModal()">&times;</span>
            @include('components.login-section')
        </div>
    </section>

    <script>
        function showLoginModal() {
            document.getElementById('login-modal').style.display = 'flex';
        }

        function closeLoginModal() {
            document.getElementById('login-modal').style.display = 'none';
        }
    </script>
</x-layouts.base>
