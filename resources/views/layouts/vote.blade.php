<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Voter' }} — Pigier's Élites Awards</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen">
    <header x-data="{ open: false }" class="sticky top-0 z-40 border-b border-line bg-bg-primary/95 backdrop-blur">
        <div class="mx-auto max-w-6xl px-4 sm:px-6">
            <div class="flex h-16 items-center justify-between gap-4">
                <a href="{{ route('vote.index') }}" class="flex flex-col leading-tight">
                    <span class="text-base font-semibold text-white">Pigier's Élites Awards</span>
                    <span class="text-xs text-muted">Bal de fin d'année 2026</span>
                </a>

                {{-- Navigation bureau --}}
                <nav class="hidden items-center gap-1 sm:flex">
                    <a href="{{ route('vote.index') }}"
                       class="rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('vote.index','vote.category') ? 'text-gold-light' : 'text-muted hover:text-offwhite' }}">
                        Catégories
                    </a>
                    <a href="{{ route('vote.history') }}"
                       class="rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('vote.history') ? 'text-gold-light' : 'text-muted hover:text-offwhite' }}">
                        Mes votes
                    </a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.home') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-muted hover:text-offwhite">Administration</a>
                    @endif
                    <span class="mx-2 h-5 w-px bg-line"></span>
                    <span class="text-sm text-muted">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="ml-2">
                        @csrf
                        <button type="submit" class="btn-secondary btn-sm">Déconnexion</button>
                    </form>
                </nav>

                {{-- Bouton menu mobile --}}
                <button @click="open = !open" class="btn-secondary btn-sm sm:hidden" aria-label="Menu">
                    Menu
                </button>
            </div>

            {{-- Navigation mobile --}}
            <nav x-show="open" x-cloak class="flex flex-col gap-1 border-t border-line py-3 sm:hidden">
                <a href="{{ route('vote.index') }}" class="nav-link {{ request()->routeIs('vote.index','vote.category') ? 'active' : '' }}">Catégories</a>
                <a href="{{ route('vote.history') }}" class="nav-link {{ request()->routeIs('vote.history') ? 'active' : '' }}">Mes votes</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.home') }}" class="nav-link">Administration</a>
                @endif
                <div class="mt-2 flex items-center justify-between border-t border-line px-3 pt-3">
                    <span class="text-sm text-muted">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-secondary btn-sm">Déconnexion</button>
                    </form>
                </div>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 sm:py-10">
        {{ $slot }}
    </main>

    @include('layouts.partials.toast')
    @livewireScripts
</body>
</html>
