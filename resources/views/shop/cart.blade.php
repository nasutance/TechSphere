<div class="cart-container">
    <h2>Votre Panier</h2>

    @if($cartItems->isEmpty())
        <p>Votre panier est vide.</p>
    @else
        <table>
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
                @foreach($cartItems as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 2, ',', ' ') }} €</td>
                        <td>{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} €</td>
                        <td>
                            <form action="{{ route('cart.update', $item->item_id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="redirect" value="{{ url()->full() }}">
                                <button type="submit">+</button>
                            </form>
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
            <strong>Total: {{ number_format($total, 2, ',', ' ') }} €</strong>
        </div>
        <form action="{{ route('shop.checkout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit">Procéder au paiement</button>
        </form>
        <form action="{{ route('cart.clear') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir vider le panier ?')">Vider le panier</button>
        </form>
    @endif
</div>
