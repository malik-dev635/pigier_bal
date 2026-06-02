<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Pigier's Élites Awards</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-x-hidden">
    <div class="top-gold-bar"></div>

    {{-- Halo doré décoratif --}}
    <div class="pointer-events-none absolute -top-40 left-1/2 -translate-x-1/2 w-[600px] h-[600px] rounded-full blur-3xl"
         style="background: radial-gradient(circle, rgba(212,168,67,0.12), transparent 70%)"></div>

    <div class="w-full max-w-md relative animate-fade-slide-up">
        <div class="text-center mb-8">
            @if (file_exists(public_path('images/affiche.png')))
                <img src="{{ asset('images/affiche.png') }}" alt="Pigier's Élites Awards"
                     class="mx-auto h-28 object-contain mb-5 drop-shadow-[0_0_25px_rgba(212,168,67,0.35)]">
            @endif
            <h1 class="font-title text-2xl sm:text-3xl text-gradient-gold tracking-widest">PIGIER'S ÉLITES AWARDS</h1>
            <p class="text-xs text-muted tracking-[0.3em] uppercase mt-2">Bal de Fin d'Année 2026</p>
            <div class="sep-gold-double w-40 mx-auto mt-5"></div>
        </div>

        <div class="gold-card is-hoverable">
            <h2 class="font-title text-xl text-offwhite mb-1">Connexion</h2>
            <p class="text-sm text-muted mb-6">Accédez à votre espace de vote.</p>

            @if ($errors->any())
                <div class="mb-5 rounded-md px-4 py-3 text-sm" style="background: rgba(220,38,38,0.12); border:1px solid rgba(220,38,38,0.4); color:#fca5a5">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="login" class="label-gold">Email ou téléphone</label>
                    <input id="login" type="text" name="login" value="{{ old('login') }}"
                           required autofocus class="input-gold" placeholder="vous@pigier.test ou 07 00 00 00 00">
                </div>
                <div>
                    <label for="password" class="label-gold">Mot de passe</label>
                    <input id="password" type="password" name="password"
                           required class="input-gold" placeholder="••••••••">
                </div>
                <label class="flex items-center gap-2 text-sm text-muted">
                    <input type="checkbox" name="remember" class="rounded border-gold-dark bg-bg-surface text-gold-main focus:ring-0">
                    Se souvenir de moi
                </label>
                <button type="submit" class="btn-gold w-full">Se connecter</button>
            </form>

            <div class="sep-gold my-6"></div>
            <p class="text-center text-sm text-muted">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="text-gold-main hover:text-gold-light font-medium">Créer mon compte</a>
            </p>
        </div>
    </div>
</body>
</html>
