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
    <div x-data="{ sidebar: false }" class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside x-cloak
               class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col border-r border-line bg-bg-card transition-transform lg:static lg:translate-x-0"
               :class="sidebar ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex h-16 items-center border-b border-line px-5">
                <div class="leading-tight">
                    <p class="text-sm font-semibold text-white">Pigier's Élites Awards</p>
                    <p class="text-xs text-muted">Administration</p>
                </div>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto p-3">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25A2.25 2.25 0 018.25 10.5H6A2.25 2.25 0 013.75 8.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 018.25 20.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25A2.25 2.25 0 0113.5 8.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                    Tableau de bord
                </a>
                <a href="{{ route('admin.categories') }}" class="nav-link {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                    Catégories
                </a>
                <a href="{{ route('admin.nominees') }}" class="nav-link {{ request()->routeIs('admin.nominees') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    Nominés
                </a>
                <a href="{{ route('admin.results') }}" class="nav-link {{ request()->routeIs('admin.results') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                    Résultats
                </a>
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    Utilisateurs
                </a>

                <div class="my-3 border-t border-line"></div>

                <a href="{{ route('vote.index') }}" class="nav-link">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Espace de vote
                </a>
            </nav>

            <div class="border-t border-line p-4">
                <p class="truncate text-sm font-medium text-offwhite">{{ auth()->user()->name }}</p>
                <p class="mb-3 truncate text-xs text-muted">{{ auth()->user()->email ?? auth()->user()->phone }}</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-secondary btn-sm w-full">Déconnexion</button>
                </form>
            </div>
        </aside>

        {{-- Overlay mobile --}}
        <div x-show="sidebar" x-cloak @click="sidebar = false" class="fixed inset-0 z-30 bg-black/60 lg:hidden"></div>

        {{-- Contenu --}}
        <div class="min-w-0 flex-1">
            <div class="flex h-16 items-center gap-3 border-b border-line px-4 lg:hidden">
                <button @click="sidebar = true" class="btn-secondary btn-sm">Menu</button>
                <span class="text-sm font-medium text-white">Administration</span>
            </div>

            <main class="p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    @include('layouts.partials.toast')
    @livewireScripts
</body>
</html>
