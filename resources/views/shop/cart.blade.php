<div class="cart-container">
    <!-- Conteneur principal pour le panier -->
    <h2>Votre Panier</h2>
    <!-- Titre de la section du panier -->

    @if($cartItems->isEmpty())
        <!-- Vérifie si le panier est vide -->
        <p>Votre panier est vide.</p>
        <!-- Affiche un message si le panier est vide -->
    @else
        <!-- Si le panier n'est pas vide -->
        <table>
            <!-- Table pour afficher les articles du panier -->
            <thead>
                <tr>
                    <th>Article</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Boucle à travers les articles du panier -->
                @foreach($cartItems as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <!-- Affiche le nom de l'article -->
                        <td>{{ $item->quantity }}</td>
                        <!-- Affiche la quantité de l'article -->
                        <td>{{ $item->price }} €</td>
                        <!-- Affiche le prix unitaire de l'article -->
                        <td>{{ $item->price * $item->quantity }} €</td>
                        <!-- Affiche le total pour cet article -->
                        <td>
                            <!-- Formulaire pour augmenter la quantité de l'article -->
                            <form action="{{ route('cart.update', $item->item_id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="redirect" value="{{ url()->full() }}">
                                <button type="submit">+</button>
                            </form>
                            <!-- Formulaire pour diminuer la quantité de l'article -->
                            <form action="{{ route('cart.update', $item->item_id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="quantity" value="-1">
                                <input type="hidden" name="redirect" value="{{ url()->full() }}">
                                <button type="submit">-</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="total">
            <!-- Affiche le total du panier -->
            <strong>Total: {{ $total }} €</strong>
        </div>
        <!-- Formulaire pour procéder au paiement -->
        <form action="{{ route('cart.checkout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit">Procéder au paiement</button>
        </form>
        <!-- Formulaire pour vider le panier -->
        <form action="{{ route('cart.clear') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir vider le panier ?')">Vider le panier</button>
        </form>
    @endif
</div>
