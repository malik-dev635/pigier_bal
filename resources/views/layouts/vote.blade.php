<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
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
    <div class="top-gold-bar"></div>

    <header class="sticky top-0 z-40 bg-bg-card/95 backdrop-blur border-b" style="border-color: var(--gold-dark)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
            <a href="{{ route('vote.index') }}" class="flex items-center gap-3">
                <div class="leading-tight">
                    <p class="font-title text-gold-main text-lg sm:text-xl tracking-widest">PIGIER'S ÉLITES AWARDS</p>
                    <p class="text-[11px] sm:text-xs text-muted tracking-[0.25em] uppercase">Bal de Fin d'Année 2026</p>
                </div>
            </a>

            <nav class="flex items-center gap-2 sm:gap-4">
                <a href="{{ route('vote.index') }}"
                   class="text-sm font-medium transition-colors {{ request()->routeIs('vote.index') || request()->routeIs('vote.category') ? 'text-gold-main' : 'text-offwhite hover:text-gold-light' }}">
                    Catégories
                </a>
                <a href="{{ route('vote.history') }}"
                   class="text-sm font-medium transition-colors {{ request()->routeIs('vote.history') ? 'text-gold-main' : 'text-offwhite hover:text-gold-light' }}">
                    Mes votes
                </a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-offwhite hover:text-gold-light">Admin</a>
                @endif
                <div class="hidden sm:flex items-center gap-2 pl-4 ml-2 border-l" style="border-color: var(--gold-dark)">
                    <span class="text-xs text-muted">{{ auth()->user()->name }}</span>
                    <span class="badge-neutral">{{ ucfirst(auth()->user()->role) }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-ghost !px-3 !py-1.5 text-xs">Déconnexion</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 animate-fade-in">
        {{ $slot }}
    </main>

    @include('layouts.partials.toast')
    @livewireScripts
</body>
</html>
