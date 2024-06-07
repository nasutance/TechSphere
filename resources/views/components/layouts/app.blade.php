<x-layouts.base>
    @if (in_array(request()->route()->getName(),['static-sign-in', 'static-sign-up', 'register', 'login','password.forgot','reset-password']))
        <!-- Affiche une navbar pour les pages d'inscription, de connexion, et de réinitialisation de mot de passe -->
        <div class="container position-sticky z-index-sticky top-0">
            <div class="row">
                <div class="col-12">
                    <x-navbars.navs.guest></x-navbars.navs.guest> <!-- Composant Navbar pour les invités -->
                </div>
            </div>
        </div>
        @if (in_array(request()->route()->getName(),['static-sign-in', 'login','password.forgot','reset-password']))
            <!-- Mise en page spécifique pour certaines pages -->
            <main class="main-content mt-0">
                <div class="page-header page-header-bg align-items-start min-vh-100">
                    <span class="mask bg-gradient-dark opacity-6"></span> <!-- Effet de masque en dégradé -->
                    {{ $slot }} <!-- Contenu principal de la page -->
                    <x-footers.guest></x-footers.guest> <!-- Composant Footer pour les invités -->
                </div>
            </main>
        @else
            {{ $slot }} <!-- Contenu principal de la page pour les autres routes -->
        @endif

    @elseif (in_array(request()->route()->getName(),['rtl']))
        <!-- Cas spécial pour les pages RTL (Right-to-Left) -->
        {{ $slot }} <!-- Contenu principal de la page -->

    @elseif (in_array(request()->route()->getName(),['virtual-reality']))
        <!-- Mise en page spécifique pour les pages de réalité virtuelle -->
        <div class="virtual-reality">
            <x-navbars.navs.auth></x-navbars.navs.auth> <!-- Composant Navbar pour les utilisateurs authentifiés -->
            <div class="border-radius-xl mx-2 mx-md-3 position-relative"
                style="background-image: url('{{ asset('assets') }}/img/vr-bg.jpg'); background-size: cover;">
                <x-navbars.sidebar></x-navbars.sidebar> <!-- Composant Sidebar -->
                <main class="main-content border-radius-lg h-100">
                    {{  $slot }} <!-- Contenu principal de la page -->
            </div>
            <x-footers.auth></x-footers.auth> <!-- Composant Footer pour les utilisateurs authentifiés -->
            </main>
            <x-plugins></x-plugins> <!-- Composant pour les plugins -->
        </div>

    @else
        <!-- Mise en page par défaut pour les autres pages -->
        <x-navbars.sidebar></x-navbars.sidebar> <!-- Composant Sidebar -->
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
            <x-navbars.navs.auth></x-navbars.navs.auth> <!-- Composant Navbar pour les utilisateurs authentifiés -->
            {{ $slot }} <!-- Contenu principal de la page -->
            <x-footers.auth></x-footers.auth> <!-- Composant Footer pour les utilisateurs authentifiés -->
        </main>
        <x-plugins></x-plugins> <!-- Composant pour les plugins -->
    @endif
</x-layouts.base>
