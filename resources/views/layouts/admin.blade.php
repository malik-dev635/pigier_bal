<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Administration' }} — Pigier's Élites Awards</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen">
    <header x-data="{ open: false }" class="sticky top-0 z-40 border-b border-line bg-bg-primary/95 backdrop-blur">
        <div class="mx-auto max-w-5xl px-4 sm:px-6">
            <div class="flex h-16 items-center justify-between gap-4">
                <a href="{{ route('admin.categories') }}" class="flex flex-col leading-tight">
                    <span class="text-base font-semibold text-white">Pigier's Élites Awards</span>
                    <span class="text-xs text-muted">Administration</span>
                </a>

                <nav class="hidden items-center gap-1 sm:flex">
                    <a href="{{ route('admin.categories') }}"
                       class="rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.categories','admin.nominees') ? 'text-gold-light' : 'text-muted hover:text-offwhite' }}">Catégories</a>
                    <a href="{{ route('admin.results') }}"
                       class="rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.results') ? 'text-gold-light' : 'text-muted hover:text-offwhite' }}">Résultats</a>
                    <a href="{{ route('admin.users') }}"
                       class="rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.users') ? 'text-gold-light' : 'text-muted hover:text-offwhite' }}">Comptes</a>
                    <span class="mx-2 h-5 w-px bg-line"></span>
                    <a href="{{ route('vote.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-muted hover:text-offwhite">Voir le site</a>
                    <form method="POST" action="{{ route('logout') }}" class="ml-1">
                        @csrf
                        <button type="submit" class="btn-secondary btn-sm">Déconnexion</button>
                    </form>
                </nav>

                <button @click="open = !open" class="btn-secondary btn-sm sm:hidden">Menu</button>
            </div>

            <nav x-show="open" x-cloak class="flex flex-col gap-1 border-t border-line py-3 sm:hidden">
                <a href="{{ route('admin.categories') }}" class="nav-link {{ request()->routeIs('admin.categories','admin.nominees') ? 'active' : '' }}">Catégories</a>
                <a href="{{ route('admin.results') }}" class="nav-link {{ request()->routeIs('admin.results') ? 'active' : '' }}">Résultats</a>
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">Comptes</a>
                <a href="{{ route('vote.index') }}" class="nav-link">Voir le site</a>
                <form method="POST" action="{{ route('logout') }}" class="px-3 pt-2">
                    @csrf
                    <button type="submit" class="btn-secondary btn-sm w-full">Déconnexion</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-8 sm:px-6">
        {{ $slot }}
    </main>

    @include('layouts.partials.toast')
    @livewireScripts
</body>
</html>
