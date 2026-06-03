<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte — Pigier's Élites Awards</title>
    @include('partials.og-meta', ['ogDescription' => "Créez votre compte et votez pour vos favoris du Bal de fin d'année 2026."])
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
            <h2 class="text-lg font-semibold text-white">Créer un compte</h2>
            <p class="mt-1 text-sm text-muted">Inscrivez-vous pour participer aux votes.</p>

            @if ($errors->any())
                <div class="mt-5 rounded-lg border border-red-500/40 bg-red-500/10 px-3.5 py-2.5 text-sm text-red-300">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.attempt') }}" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label for="name" class="field-label">Nom complet</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                           required autofocus class="input" placeholder="Prénom et nom">
                </div>
                <div>
                    <label for="class" class="field-label">Classe <span class="font-normal text-muted">(facultatif)</span></label>
                    <input id="class" type="text" name="class" value="{{ old('class') }}" class="input" placeholder="Ex : RGL3A">
                </div>
                <div>
                    <label for="login" class="field-label">Email ou téléphone</label>
                    <input id="login" type="text" name="login" value="{{ old('login') }}"
                           required class="input" placeholder="nom@exemple.com ou 07 00 00 00 00">
                    <p class="field-hint">Vous l'utiliserez pour vous connecter.</p>
                </div>
                <div>
                    <label for="password" class="field-label">Mot de passe</label>
                    <input id="password" type="password" name="password" required class="input" placeholder="6 caractères minimum">
                </div>
                <div>
                    <label for="password_confirmation" class="field-label">Confirmer le mot de passe</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required class="input" placeholder="Répétez le mot de passe">
                </div>
                <button type="submit" class="btn-primary w-full">Créer mon compte</button>
            </form>

            <div class="my-5 border-t border-line"></div>
            <p class="text-center text-sm text-muted">
                Déjà un compte ?
                <a href="{{ route('login') }}" class="font-medium text-gold-light hover:underline">Se connecter</a>
            </p>
        </div>
    </div>
</body>
</html>
