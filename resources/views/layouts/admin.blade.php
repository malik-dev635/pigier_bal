<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
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
    <div class="top-gold-bar"></div>

    <div x-data="{ sidebarOpen: false }" class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside
            class="fixed lg:static inset-y-0 left-0 z-40 w-64 bg-bg-card border-r flex flex-col transition-transform lg:translate-x-0"
            style="border-color: var(--gold-dark)"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="h-20 flex items-center px-6 border-b" style="border-color: var(--gold-dark)">
                <div class="leading-tight">
                    <p class="font-title text-gold-main tracking-widest">PIGIER'S</p>
                    <p class="text-[10px] text-muted tracking-[0.3em] uppercase">Élites Awards · Admin</p>
                </div>
            </div>

            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span>📊</span> Tableau de bord
                </a>
                <a href="{{ route('admin.categories') }}" class="sidebar-link {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                    <span>🏆</span> Catégories
                </a>
                <a href="{{ route('admin.nominees') }}" class="sidebar-link {{ request()->routeIs('admin.nominees') ? 'active' : '' }}">
                    <span>👤</span> Nominés
                </a>
                <a href="{{ route('admin.results') }}" class="sidebar-link {{ request()->routeIs('admin.results') ? 'active' : '' }}">
                    <span>📈</span> Résultats
                </a>
                <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <span>👥</span> Utilisateurs
                </a>

                <hr class="sep-gold my-4">

                <a href="{{ route('vote.index') }}" class="sidebar-link">
                    <span>🗳️</span> Espace de vote
                </a>
            </nav>

            <div class="p-4 border-t" style="border-color: var(--gold-dark)">
                <p class="text-sm text-offwhite font-medium truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-muted mb-3">{{ auth()->user()->email }}</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-ghost w-full !py-2 text-xs">Déconnexion</button>
                </form>
            </div>
        </aside>

        {{-- Overlay mobile --}}
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/60 z-30 lg:hidden"></div>

        {{-- Contenu --}}
        <div class="flex-1 min-w-0">
            <div class="lg:hidden h-16 flex items-center px-4 border-b bg-bg-card" style="border-color: var(--gold-dark)">
                <button @click="sidebarOpen = true" class="btn-ghost !px-3 !py-1.5">☰ Menu</button>
            </div>

            <main class="p-4 sm:p-6 lg:p-10 animate-fade-in">
                {{ $slot }}
            </main>
        </div>
    </div>

    @include('layouts.partials.toast')
    @livewireScripts
</body>
</html>
