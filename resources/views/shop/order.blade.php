<x-layouts.base>
    <style>
        .order-confirmation, .order-item {
            background-color: rgba(0, 0, 0, 0.5); /* Fond semi-transparent */
            padding: 20px; /* Espacement intérieur */
            border: 1px solid #ddd; /* Bordure grise */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Ombre légère */
            border-radius: 10px; /* Bords arrondis */
            color: white; /* Texte en blanc */
            width: 100%; /* Prend toute la largeur disponible */
            max-width: 1200px; /* Largeur maximale */
            margin-top: 20px; /* Marge supérieure */
            text-align: left; /* Texte aligné à gauche */
        }

        .order-confirmation h2, .order-confirmation h3, .order-item h3 {
            color: white; /* Texte en blanc pour les titres */
        }

        .order-confirmation p, .order-confirmation ul, .order-item p, .order-item ul {
            color: white; /* Texte en blanc pour les paragraphes et listes */
        }

        .order-confirmation ul, .order-item ul {
            list-style-type: none; /* Suppression des puces de liste */
            padding: 0; /* Suppression du padding */
        }

        .order-confirmation ul li, .order-item ul li {
            background-color: rgba(0, 0, 0, 0.7); /* Fond semi-transparent pour les éléments de liste */
            border: 1px solid #ddd; /* Bordure grise */
            padding: 10px; /* Espacement intérieur */
            margin-bottom: 10px; /* Marge inférieure */
            border-radius: 5px; /* Bords arrondis */
        }

        .order-confirmation .total, .order-item .total {
            font-weight: bold; /* Texte en gras */
            text-align: right; /* Texte aligné à droite */
            margin-top: 10px; /* Marge supérieure */
        }

        .order-confirmation .btn-primary, .order-item .btn-primary {
            display: inline-block; /* Affichage en ligne bloc */
            padding: 10px 20px; /* Espacement intérieur */
            background-color: #007bff; /* Couleur de fond */
            color: white; /* Texte en blanc */
            text-decoration: none; /* Suppression de la décoration de texte */
            border-radius: 5px; /* Bords arrondis */
            text-align: center; /* Texte centré */
            margin-top: 20px; /* Marge supérieure */
        }

        .order-confirmation .btn-primary:hover, .order-item .btn-primary:hover {
            background-color: #0056b3; /* Couleur de fond au survol */
        }

        /* Ajout d'une marge supérieure pour descendre le contenu */
        .order-list {
            margin-top: 100px; /* Ajustez cette valeur si nécessaire */
        }
    </style>

    <div> <br> </div>

    <div class="order-list">
        @if(isset($user) && isset($orders))
            <!-- Section pour afficher les commandes d'un utilisateur spécifique -->
            <div class="order-confirmation">
                <h2>Commandes de {{ $user->username }}</h2>
                @foreach($orders as $order)
                    <div class="order-item">
                        <h3>Commande #{{ $order->order_id }}</h3>
                        <p>Date : {{ $order->created_at->format('d/m/Y') }}</p>
                        <p>Total : {{ $order->total }} €</p>
                        <h4>Détails de la commande</h4>
                        <ul>
                            @foreach($order->items as $orderItem)
                                <li>{{ $orderItem->item->name }} - Quantité : {{ $orderItem->quantity }} - Prix : {{ $orderItem->price }} €</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        @elseif(isset($orders))
            <!-- Section pour afficher les commandes de l'utilisateur authentifié -->
            <div class="order-confirmation">
                <h2>Mes Commandes</h2>
                @foreach($orders as $order)
                    <div class="order-item">
                        <h3>Commande #{{ $order->order_id }}</h3>
                        <p>Date : {{ $order->created_at->format('d/m/Y') }}</p>
                        <p>Total : {{ $order->total }} €</p>
                        <h4>Détails de la commande</h4>
                        <ul>
                            @foreach($order->items as $orderItem)
                                <li>{{ $orderItem->item->name }} - Quantité : {{ $orderItem->quantity }} - Prix : {{ $orderItem->price }} €</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        @elseif(isset($order))
            <!-- Section pour afficher la confirmation de commande -->
            <div class="order-confirmation">
                <h2>Confirmation de Commande</h2>
                <p>Votre commande a été passée avec succès !</p>
                <h3>La facture vous sera adressée par courriel</h3>
                <h4>Détails de la commande</h4>
                <ul>
                    @foreach($order->items as $orderItem)
                        <li>{{ $orderItem->item->name }} - Quantité : {{ $orderItem->quantity }} - Prix : {{ $orderItem->price }} €</li>
                    @endforeach
                </ul>
                <div class="total">
                    Total : {{ $order->total }} €
                </div>
                <a href="{{ route('shop.index') }}" class="btn btn-primary">Retour à la boutique</a>
            </div>
        @endif
    </div>
</x-layouts.base>
