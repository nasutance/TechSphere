<!DOCTYPE html>
<html lang="en" dir="{{ Route::currentRouteName() == 'rtl' ? 'rtl' : 'ltr' }}">
<!-- Détermine la direction du texte (RTL ou LTR) en fonction de la route actuelle -->

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Configure les métadonnées essentielles pour une mise en page responsive -->

    <link rel="stylesheet" href="{{ asset('assets/css/background.css') }}">
    <!-- Lien vers la feuille de style pour le background -->

    <title>{{ $title ?? 'TechSphere' }}</title>
    <!-- Définit le titre de la page, utilise 'TechSphere' par défaut si $title n'est pas défini -->
</head>

<body class="g-sidenav-show {{ Route::currentRouteName() == 'rtl' ? 'rtl' : '' }} {{ Route::currentRouteName() == 'register' || Route::currentRouteName() == 'static-sign-up' ? '' : 'bg-gray-200' }}">
    <!-- Applique des classes au body en fonction de la route actuelle -->

    @include('components.layouts.header', ['title' => $title ?? null])
    <!-- Inclut le composant header avec le titre passé en paramètre -->

    <!-- Espace vide sous le header -->
    <div class="empty-space"></div>

    <main class="message-container">
        <!-- Conteneur pour afficher les messages de succès ou d'erreur -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                <!-- Affiche le message de succès si présent dans la session -->
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        <!-- Affiche chaque message d'erreur dans une liste -->
                    @endforeach
                </ul>
            </div>
        @endif
    </main>

    <div class="container">
        <!-- Conteneur principal pour le contenu de la page -->
        {{ $slot }}
        <!-- Affiche le contenu de la page -->
    </div>

    @include('components.layouts.footer')
    <!-- Inclut le composant footer -->

    @stack('scripts')
    <!-- Pile pour les scripts supplémentaires -->
</body>

</html>
