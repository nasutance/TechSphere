<x-layouts.base :title="'Votre Panier'">
    <link rel="stylesheet" href="{{ asset('assets/css/cart-style.css') }}">

    @include('shop.cart')
</x-layouts.base>
