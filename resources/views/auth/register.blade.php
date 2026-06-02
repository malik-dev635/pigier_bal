<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription — Pigier's Élites Awards</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-x-hidden py-10">
    <div class="top-gold-bar"></div>

    <div class="pointer-events-none absolute -top-40 left-1/2 -translate-x-1/2 w-[600px] h-[600px] rounded-full blur-3xl"
         style="background: radial-gradient(circle, rgba(212,168,67,0.12), transparent 70%)"></div>

    <div class="w-full max-w-md relative animate-fade-slide-up py-8">
        <div class="text-center mb-8">
            @if (file_exists(public_path('images/affiche.png')))
                <img src="{{ asset('images/affiche.png') }}" alt="Pigier's Élites Awards"
                     class="mx-auto h-24 object-contain mb-5 drop-shadow-[0_0_25px_rgba(212,168,67,0.35)]">
            @endif
            <h1 class="font-title text-2xl sm:text-3xl text-gradient-gold tracking-widest">PIGIER'S ÉLITES AWARDS</h1>
            <p class="text-xs text-muted tracking-[0.3em] uppercase mt-2">Bal de Fin d'Année 2026</p>
            <div class="sep-gold-double w-40 mx-auto mt-5"></div>
        </div>

        <div class="gold-card is-hoverable">
            <h2 class="font-title text-xl text-offwhite mb-1">Créer mon compte</h2>
            <p class="text-sm text-muted mb-6">Inscrivez-vous pour participer aux votes.</p>

            @if ($errors->any())
                <div class="mb-5 rounded-md px-4 py-3 text-sm" style="background: rgba(220,38,38,0.12); border:1px solid rgba(220,38,38,0.4); color:#fca5a5">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.attempt') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="label-gold">Nom complet</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                           required autofocus class="input-gold" placeholder="Prénom Nom">
                </div>
                <div>
                    <label for="class" class="label-gold">Classe <span class="text-muted">(facultatif)</span></label>
                    <input id="class" type="text" name="class" value="{{ old('class') }}"
                           class="input-gold" placeholder="Ex : CF 1 A">
                </div>
                <div>
                    <label for="login" class="label-gold">Email ou téléphone</label>
                    <input id="login" type="text" name="login" value="{{ old('login') }}"
                           required class="input-gold" placeholder="vous@email.com ou 07 00 00 00 00">
                    <p class="text-xs text-muted mt-1">Servira d'identifiant pour vous connecter.</p>
                </div>
                <div>
                    <label for="password" class="label-gold">Mot de passe</label>
                    <input id="password" type="password" name="password"
                           required class="input-gold" placeholder="6 caractères minimum">
                </div>
                <div>
                    <label for="password_confirmation" class="label-gold">Confirmer le mot de passe</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                           required class="input-gold" placeholder="••••••••">
                </div>
                <button type="submit" class="btn-gold w-full">Créer mon compte</button>
            </form>

            <div class="sep-gold my-6"></div>
            <p class="text-center text-sm text-muted">
                Déjà inscrit ?
                <a href="{{ route('login') }}" class="text-gold-main hover:text-gold-light font-medium">Se connecter</a>
            </p>
        </div>
    </div>
</body>
</html>
