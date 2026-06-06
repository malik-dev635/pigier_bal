<div wire:poll.15s>
    {{-- En-tête --}}
    <div class="mb-2">
        <p class="eyebrow">Bal de fin d'année 2026</p>
        <h1 class="mt-2 text-3xl sm:text-4xl">Administration</h1>
    </div>

    {{-- Synthèse en direct --}}
    <div class="mt-6 flex flex-wrap items-center gap-x-8 gap-y-3 border-y border-line py-4">
        <div>
            <p class="text-2xl font-semibold text-white">{{ $totalVotes }}</p>
            <p class="eyebrow mt-1 text-[10px]">Votes</p>
        </div>
        <div>
            <p class="text-2xl font-semibold text-white">{{ $participants }}<span class="text-base text-muted"> / {{ $totalVoters }}</span></p>
            <p class="eyebrow mt-1 text-[10px]">Participants</p>
        </div>
        <div>
            <p class="text-2xl font-semibold text-white">{{ $openCategories }}<span class="text-base text-muted"> / {{ $totalCategories }}</span></p>
            <p class="eyebrow mt-1 text-[10px]">Récompenses ouvertes</p>
        </div>
        <div>
            <p class="text-2xl font-semibold text-white">{{ $totalNominees }}</p>
            <p class="eyebrow mt-1 text-[10px]">Nominés</p>
        </div>
    </div>

    {{-- Contrôles globaux du vote --}}
    <div class="mt-6 card p-4 sm:p-5">
        <p class="eyebrow mb-3 text-[10px]">Contrôles du vote</p>
        <div class="flex flex-wrap items-center gap-3">
            <button wire:click="openAllVotes" wire:confirm="Ouvrir TOUS les votes en même temps ?" class="btn-secondary btn-sm">Ouvrir tous les votes</button>
            <button wire:click="closeAllVotes" wire:confirm="Fermer TOUS les votes en même temps ?" class="btn-secondary btn-sm">Fermer tous les votes</button>
            <span class="mx-1 hidden h-5 w-px bg-line sm:block"></span>
            <button wire:click="toggleHiddenPublic" class="btn-secondary btn-sm">
                <span class="status {{ $hiddenPublic ? 'status-closed' : 'status-open' }}">
                    <span class="status-dot"></span>{{ $hiddenPublic ? 'Votes masqués au public' : 'Votes visibles au public' }}
                </span>
            </button>
        </div>
        @if($hiddenPublic)
            <p class="field-hint mt-2">Les votants voient un message d'attente — toi (admin) tu gardes l'accès.</p>
        @endif
    </div>

    {{-- Tuiles --}}
    <div class="mt-6 grid gap-4 sm:grid-cols-2">
        <a href="{{ route('admin.categories') }}" class="tile">
            <span class="tile-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            </span>
            <h2 class="text-lg text-white">Récompenses</h2>
            <p class="mt-1 text-sm text-muted">Créer les récompenses, ouvrir ou clôturer les votes.</p>
        </a>

        <a href="{{ route('admin.nominees') }}" class="tile">
            <span class="tile-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
            </span>
            <h2 class="text-lg text-white">Nominés</h2>
            <p class="mt-1 text-sm text-muted">Ajouter et gérer les nominés de chaque récompense.</p>
        </a>

        <a href="{{ route('admin.results') }}" class="tile">
            <span class="tile-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
            </span>
            <h2 class="text-lg text-white">Résultats</h2>
            <p class="mt-1 text-sm text-muted">Classement par récompense et export en CSV.</p>
        </a>

        <a href="{{ route('admin.users') }}" class="tile">
            <span class="tile-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
            </span>
            <h2 class="text-lg text-white">Participants</h2>
            <p class="mt-1 text-sm text-muted">Créer et gérer les comptes des participants.</p>
        </a>

        <a href="{{ route('admin.programme') }}" class="tile">
            <span class="tile-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
            </span>
            <h2 class="text-lg text-white">Programme</h2>
            <p class="mt-1 text-sm text-muted">Liste des nominés à imprimer ou exporter en PDF pour le maître de cérémonie.</p>
        </a>

        <a href="{{ route('admin.qr') }}" class="tile">
            <span class="tile-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z"/></svg>
            </span>
            <h2 class="text-lg text-white">QR codes</h2>
            <p class="mt-1 text-sm text-muted">Afficher / projeter un QR code par récompense pour voter sur place.</p>
        </a>
    </div>
</div>
