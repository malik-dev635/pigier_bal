<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Candidature' }} — Pigier's Élites Awards</title>
    @include('partials.og-meta')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="flex min-h-screen items-start justify-center px-4 py-10">
    <div class="w-full max-w-lg">
        <div class="mb-6 text-center">
            <div class="mb-4 flex items-center justify-center gap-3">
                <img src="{{ asset('images/logo-bal.png') }}" alt="Bal de fin d'année" class="h-10 w-auto sm:h-11">
                <img src="{{ asset('images/logo-pigier-award.png') }}" alt="Pigier's Élites Awards" class="h-10 w-auto sm:h-11">
            </div>
            <h1 class="text-2xl">Candidature</h1>
        </div>
        {{ $slot }}
    </div>
    @livewireScripts
</body>
</html>
