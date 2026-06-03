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
            <div class="flex shrink-0 items-center gap-2 sm:gap-2.5">
                @include('partials.brand-logos', ['size' => 'h-7 w-auto sm:h-9'])
                <span class="eyebrow hidden text-[10px] sm:inline">Administration</span>
            </div>
            <div class="flex shrink-0 items-center gap-3 sm:gap-4">
                <a href="{{ route('vote.index') }}" class="text-sm font-medium text-muted hover:text-offwhite">Voir le site</a>
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
