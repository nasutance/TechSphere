<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('assets/css/background.css') }}">
    <title>{{ $title ?? 'TechSphere' }}</title>
</head>

<body>
    @include('components.layouts.header', ['title' => $title ?? null])

    <div class="empty-space"></div>

    <main class="message-container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </main>

    <div class="container">
        {{ $slot }}
    </div>

    @include('components.layouts.footer')

    @stack('scripts')
</body>

</html>
