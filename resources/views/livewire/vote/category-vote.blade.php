<div x-data="{ submitting: false }"
     @vote-success.window="submitting = false"
     @vote-error.window="submitting = false">

    <a href="{{ route('vote.index') }}" class="inline-flex items-center gap-1 text-sm text-muted transition-colors hover:text-offwhite">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        Toutes les récompenses
    </a>

    @if($category->image_url)
        <div class="mt-4 aspect-[21/9] w-full overflow-hidden rounded-xl border border-line">
            <img src="{{ $category->image_url }}" alt="" class="h-full w-full object-cover">
        </div>
    @endif

    <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-white">{{ $category->name }}</h1>
            @if($category->description)
                <p class="mt-1 max-w-2xl text-sm text-muted">{{ $category->description }}</p>
            @endif
        </div>
        @if($isOpen)
            <span class="status status-open shrink-0"><span class="status-dot"></span>Vote ouvert</span>
        @else
            <span class="status status-closed shrink-0"><span class="status-dot"></span>Vote clôturé</span>
        @endif
    </div>

    {{-- Bandeau d'état --}}
    @if($hasVoted)
        <div class="mt-5 flex items-center gap-3 rounded-xl border border-gold-main/40 bg-gold-main/10 px-4 py-3">
            <svg class="h-5 w-5 shrink-0 text-gold-light" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            <p class="text-sm text-offwhite">Votre vote est enregistré pour cette récompense. Le choix est définitif.</p>
        </div>
    @elseif(! $isOpen)
        <div class="mt-5 rounded-xl border border-line px-4 py-3">
            <p class="text-sm text-muted">Le vote est clôturé. Vous pouvez consulter les nominés.</p>
        </div>
    @endif

    @if($nominees->isEmpty())
        <div class="mt-6 card p-10 text-center">
            <p class="text-sm text-muted">Aucun nominé disponible pour le moment.</p>
            @if($category->requires_proof)
                <p class="mt-1 text-xs text-muted">Cette récompense nécessite une preuve : seuls les nominés l'ayant fournie sont affichés.</p>
            @endif
        </div>
    @else
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($nominees as $nominee)
                @php
                    $isChosen = $votedNomineeId === $nominee->id;
                    $canVote = $isOpen && ! $hasVoted;
                @endphp
                <div class="card flex flex-col p-5 {{ $isChosen ? 'border-gold-main/60' : '' }}">
                    <div class="mb-4 flex aspect-square items-center justify-center overflow-hidden rounded-lg border border-line bg-bg-surface">
                        @if($nominee->photo_url)
                            <img src="{{ $nominee->photo_url }}" alt="{{ $nominee->full_name }}" class="h-full w-full object-cover">
                        @else
                            <span class="text-2xl font-semibold text-muted">{{ $nominee->initials }}</span>
                        @endif
                    </div>

                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-semibold text-white">{{ $nominee->full_name }}</h3>
                        @if($isChosen)
                            <span class="badge-gold shrink-0">Votre choix</span>
                        @endif
                    </div>
                    @if($nominee->class)
                        <p class="mt-0.5 text-sm text-muted">{{ $nominee->class }}</p>
                    @endif
                    @if($nominee->description)
                        <p class="mt-2 line-clamp-3 text-sm text-muted">{{ $nominee->description }}</p>
                    @endif

                    <div class="mt-auto space-y-2 pt-4">
                        @if($category->requires_proof && ($nominee->proof_url || $nominee->proof_file))
                            <div class="flex flex-wrap gap-2">
                                @if($nominee->proof_url)
                                    <a href="{{ $nominee->proof_url }}" target="_blank" rel="noopener" class="btn-secondary btn-sm flex-1">Voir la preuve</a>
                                @endif
                                @if($nominee->proof_file)
                                    <a href="{{ $nominee->proof_file_url }}" target="_blank" rel="noopener" class="btn-secondary btn-sm flex-1">Télécharger</a>
                                @endif
                            </div>
                        @endif

                        @if($isChosen)
                            <button type="button" class="btn-voted w-full" disabled>
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                Voté
                            </button>
                        @elseif($hasVoted)
                            <button type="button" class="btn-secondary w-full" disabled>Vote déjà utilisé</button>
                        @elseif(! $isOpen)
                            <button type="button" class="btn-secondary w-full" disabled>Vote clôturé</button>
                        @else
                            <button type="button"
                                    wire:click="vote({{ $nominee->id }})"
                                    wire:loading.attr="disabled"
                                    x-bind:disabled="submitting"
                                    @click="submitting = true"
                                    class="btn-primary w-full">
                                <span wire:loading.remove wire:target="vote({{ $nominee->id }})">Voter</span>
                                <span wire:loading wire:target="vote({{ $nominee->id }})">Enregistrement…</span>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
