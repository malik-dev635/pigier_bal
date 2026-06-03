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
<body class="min-h-screen bg-bg-primary">
    {{-- Navbar transparente, par-dessus l'affiche --}}
    <header class="absolute inset-x-0 top-0 z-20">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6">
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('images/logo-pblog-affiche.png') }}" alt="pblog" class="h-10 w-auto sm:h-12">
            </a>
            <nav class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('login') }}" class="btn-secondary btn-sm sm:px-5 sm:py-2.5 sm:text-sm">Connexion</a>
                <a href="{{ route('register') }}" class="btn-primary btn-sm sm:px-5 sm:py-2.5 sm:text-sm">Inscription</a>
            </nav>
        </div>
    </header>

    {{-- Affiche en fond, visible en entier, sans overlay --}}
    <main class="flex min-h-screen items-center justify-center">
        <img src="{{ asset('images/img-og.png') }}"
             alt="Pigier's Élites Awards — Bal de fin d'année 2026"
             class="max-h-screen w-auto max-w-full object-contain">
    </main>
</body>
</html>
