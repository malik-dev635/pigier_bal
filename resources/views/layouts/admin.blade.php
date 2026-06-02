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
    <header class="sticky top-0 z-40 border-b border-line bg-bg-primary/95 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-5xl items-center justify-between gap-4 px-4 sm:px-6">
            <a href="{{ route('admin.home') }}" class="flex flex-col leading-tight">
                <span class="display text-lg text-white">Pigier's Élites Awards</span>
                <span class="eyebrow text-[10px]">Administration</span>
            </a>
            <div class="flex items-center gap-2">
                <a href="{{ route('vote.index') }}" class="hidden text-sm font-medium text-muted hover:text-offwhite sm:block">Voir le site</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-secondary btn-sm">Déconnexion</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-8 sm:px-6 sm:py-10">
        {{ $slot }}
    </main>

    @include('layouts.partials.toast')
    @livewireScripts
</body>
</html>
