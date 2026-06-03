<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Pigier's Élites Awards</title>
    @include('partials.og-meta', ['ogDescription' => "Connectez-vous pour voter aux Pigier's Élites Awards — Bal de fin d'année 2026."])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center px-4 py-10">
    <div class="w-full max-w-sm">
        <div class="mb-8 text-center">
            <h1 class="text-xl font-semibold text-white">Pigier's Élites Awards</h1>
            <p class="mt-1 text-sm text-muted">Bal de fin d'année 2026</p>
        </div>

        <div class="card p-6">
            <h2 class="text-lg font-semibold text-white">Connexion</h2>
            <p class="mt-1 text-sm text-muted">Connectez-vous pour accéder aux votes.</p>

            @if ($errors->any())
                <div class="mt-5 rounded-lg border border-red-500/40 bg-red-500/10 px-3.5 py-2.5 text-sm text-red-300">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label for="login" class="field-label">Email ou téléphone</label>
                    <input id="login" type="text" name="login" value="{{ old('login') }}"
                           required autofocus class="input" placeholder="nom@exemple.com">
                </div>
                <div>
                    <label for="password" class="field-label">Mot de passe</label>
                    <input id="password" type="password" name="password" required class="input" placeholder="Votre mot de passe">
                </div>
                <label class="flex items-center gap-2 text-sm text-muted">
                    <input type="checkbox" name="remember" class="checkbox" @checked(old('remember', true))>
                    Rester connecté
                </label>
                <button type="submit" class="btn-primary w-full">Se connecter</button>
            </form>

            <div class="my-5 border-t border-line"></div>
            <p class="text-center text-sm text-muted">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="font-medium text-gold-light hover:underline">Créer un compte</a>
            </p>
        </div>
    </div>
</body>
</html>
