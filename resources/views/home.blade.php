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
    {{-- Navbar : logos bal + awards à gauche, actions à droite --}}
    <header class="border-b border-line">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-3 px-4 py-3 sm:px-6 sm:py-4">
            <a href="{{ route('home') }}" class="flex items-center gap-3 sm:gap-4">
                <img src="{{ asset('images/logo-bal.png') }}" alt="Bal de fin d'année" class="h-9 w-auto sm:h-12">
                <img src="{{ asset('images/logo-pigier-award.png') }}" alt="Pigier's Élites Awards" class="h-9 w-auto sm:h-12">
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

    {{-- Footer : tous les logos --}}
    <footer class="border-t border-line">
        <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6">
            <p class="eyebrow mb-4 text-center text-[10px]">Organisé par</p>
            <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-10">
                <img src="{{ asset('images/logo-bal.png') }}" alt="Bal de fin d'année" class="h-8 w-auto opacity-90 transition-opacity hover:opacity-100 sm:h-10">
                <img src="{{ asset('images/logo-pigier-award.png') }}" alt="Pigier's Élites Awards" class="h-8 w-auto opacity-90 transition-opacity hover:opacity-100 sm:h-10">
                <img src="{{ asset('images/logo-pblog-affiche.png') }}" alt="pblog" class="h-8 w-auto opacity-90 transition-opacity hover:opacity-100 sm:h-10">
            </div>
            <p class="mt-5 text-center text-xs text-muted">© {{ date('Y') }} Pigier's Élites Awards — Bal de fin d'année</p>
        </div>
    </footer>
</body>
</html>
