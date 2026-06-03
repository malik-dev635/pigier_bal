<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pigier's Élites Awards — Bal de fin d'année 2026</title>
    @include('partials.og-meta')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-bg-primary">
    {{-- Navbar : pblog + bal + awards à gauche, actions à droite --}}
    <header class="border-b border-line">
        <div class="mx-auto flex max-w-6xl flex-col items-center gap-3 px-4 py-3 sm:flex-row sm:justify-between sm:gap-4 sm:px-6 sm:py-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 sm:gap-4">
                @include('partials.brand-logos', ['size' => 'h-7 w-auto sm:h-11'])
            </a>
            <nav class="flex shrink-0 items-center gap-2 sm:gap-3">
                <a href="{{ route('login') }}" class="btn-secondary btn-sm sm:px-5 sm:py-2.5 sm:text-sm">Connexion</a>
                <a href="{{ route('register') }}" class="btn-primary btn-sm sm:px-5 sm:py-2.5 sm:text-sm">Inscription</a>
            </nav>
        </div>
    </header>

    {{-- Affiche en entier, centrée --}}
    <main class="flex min-h-0 flex-1 items-center justify-center p-4 sm:p-6">
        <img src="{{ asset('images/img-og.png') }}"
             alt="Pigier's Élites Awards — Bal de fin d'année 2026"
             class="max-h-full max-w-full object-contain">
    </main>

    @include('partials.footer')
</body>
</html>
