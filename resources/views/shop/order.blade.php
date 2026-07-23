<x-layouts.base>
    <style>
        .order-confirmation, .order-item {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            color: white;
            width: 100%;
            max-width: 1200px;
            margin-top: 20px;
            text-align: left;
        }

        .order-confirmation h2, .order-confirmation h3, .order-item h3 {
            color: white;
        }

        .order-confirmation p, .order-confirmation ul, .order-item p, .order-item ul {
            color: white;
        }

        .order-confirmation ul, .order-item ul {
            list-style-type: none;
            padding: 0;
        }

        .order-confirmation ul li, .order-item ul li {
            background-color: rgba(0, 0, 0, 0.7);
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .order-confirmation .total, .order-item .total {
            font-weight: bold;
            text-align: right;
            margin-top: 10px;
        }

        .order-confirmation .btn-primary, .order-item .btn-primary {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
        }

        .order-confirmation .btn-primary:hover, .order-item .btn-primary:hover {
            background-color: #0056b3;
        }

        .order-list {
            margin-top: 100px;
        }
    </style>

    <div class="order-list">
        @if(isset($user) && isset($orders))
            {{-- Commandes d'un utilisateur donné (vue admin) --}}
            <div class="order-confirmation">
                <h2>Commandes de {{ $user->username }}</h2>
                @forelse($orders as $order)
                    <div class="order-item">
                        <h3>Commande #{{ $order->order_id }}</h3>
                        <p>Date : {{ $order->created_at->format('d/m/Y') }}</p>
                        <p>Total : {{ number_format($order->total, 2, ',', ' ') }} €</p>
                        <h4>Détails de la commande</h4>
                        <ul>
                            @foreach($order->items as $orderItem)
                                <li>{{ $orderItem->item->name }} - Quantité : {{ $orderItem->quantity }} - Prix : {{ number_format($orderItem->price, 2, ',', ' ') }} €</li>
                            @endforeach
                        </ul>
                    </div>
                @empty
                    <p>Aucune commande pour cet utilisateur.</p>
                @endforelse
            </div>
        @elseif(isset($orders))
            {{-- Historique des commandes de l'utilisateur connecté --}}
            <div class="order-confirmation">
                <h2>Mes Commandes</h2>
                @forelse($orders as $order)
                    <div class="order-item">
                        <h3>Commande #{{ $order->order_id }}</h3>
                        <p>Date : {{ $order->created_at->format('d/m/Y') }}</p>
                        <p>Total : {{ number_format($order->total, 2, ',', ' ') }} €</p>
                        <h4>Détails de la commande</h4>
                        <ul>
                            @foreach($order->items as $orderItem)
                                <li>{{ $orderItem->item->name }} - Quantité : {{ $orderItem->quantity }} - Prix : {{ number_format($orderItem->price, 2, ',', ' ') }} €</li>
                            @endforeach
                        </ul>
                    </div>
                @empty
                    <p>Vous n'avez pas encore passé de commande.</p>
                @endforelse
            </div>
        @elseif(isset($order))
            {{-- Confirmation après le passage d'une commande --}}
            <div class="order-confirmation">
                <h2>Confirmation de Commande</h2>
                <p>Votre commande a été passée avec succès !</p>
                <h3>La facture vous sera adressée par courriel</h3>
                <h4>Détails de la commande</h4>
                <ul>
                    @foreach($order->items as $orderItem)
                        <li>{{ $orderItem->item->name }} - Quantité : {{ $orderItem->quantity }} - Prix : {{ number_format($orderItem->price, 2, ',', ' ') }} €</li>
                    @endforeach
                </ul>
                <div class="total">
                    Total : {{ number_format($order->total, 2, ',', ' ') }} €
                </div>
                <a href="{{ route('shop.index') }}" class="btn btn-primary">Retour à la boutique</a>
            </div>
        @endif
    </div>
</x-layouts.base>
