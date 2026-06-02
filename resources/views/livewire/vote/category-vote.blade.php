<div x-data="{ submitting: false }"
     @vote-success.window="submitting = false"
     @vote-error.window="submitting = false">

    {{-- Fil d'ariane + en-tête --}}
    <div class="mb-8">
        <a href="{{ route('vote.index') }}" class="text-sm text-muted hover:text-gold-light transition-colors">← Toutes les catégories</a>

        @if($category->image_url)
            <div class="mt-4 aspect-[21/9] w-full overflow-hidden rounded-lg border" style="border-color: var(--gold-dark)">
                <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
            </div>
        @endif

        <div class="mt-4 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
            <div>
                <h1 class="font-title text-3xl text-gradient-gold tracking-wide">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="text-muted mt-2 max-w-2xl">{{ $category->description }}</p>
                @endif
            </div>
            <div>
                @if($isOpen)
                    <span class="badge-open">● Vote ouvert</span>
                @else
                    <span class="badge-closed">● Vote clôturé</span>
                @endif
            </div>
        </div>
        <div class="sep-gold mt-5"></div>
    </div>

    {{-- Bandeau d'état --}}
    @if($hasVoted)
        <div class="gold-card !border-gold-main mb-8 flex items-center gap-3 !py-4">
            <span class="text-2xl text-gold-main">✓</span>
            <p class="text-offwhite">Vous avez voté dans cette catégorie. Votre choix est définitif.</p>
        </div>
    @elseif(! $isOpen)
        <div class="gold-card mb-8 !py-4">
            <p class="text-muted">Le vote pour cette catégorie est clôturé. Vous pouvez consulter les nominés.</p>
        </div>
    @endif

    @if($nominees->isEmpty())
        <div class="gold-card text-center py-16">
            <p class="text-muted">Aucun nominé éligible n'est disponible pour le moment.</p>
            @if($category->requires_proof)
                <p class="text-xs text-muted mt-2">Cette catégorie exige une preuve : seuls les nominés ayant fourni leur preuve sont affichés.</p>
            @endif
        </div>
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($nominees as $nominee)
                @php
                    $isChosen = $votedNomineeId === $nominee->id;
                    $canVote = $isOpen && ! $hasVoted;
                @endphp
                <div class="gold-card flex flex-col animate-fade-slide-up {{ $isChosen ? '!border-gold-main shadow-gold' : 'is-hoverable' }}">
                    {{-- Photo --}}
                    <div class="aspect-[4/3] rounded-md overflow-hidden mb-4 bg-bg-surface flex items-center justify-center border" style="border-color: var(--gold-dark)">
                        @if($nominee->photo_url)
                            <img src="{{ $nominee->photo_url }}" alt="{{ $nominee->full_name }}" class="w-full h-full object-cover">
                        @else
                            <span class="font-title text-4xl text-gold-dark">{{ strtoupper(substr($nominee->first_name,0,1).substr($nominee->last_name,0,1)) }}</span>
                        @endif
                    </div>

                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-title text-lg text-offwhite leading-snug">{{ $nominee->full_name }}</h3>
                        @if($isChosen)
                            <span class="badge-success whitespace-nowrap">✓ Voté</span>
                        @endif
                    </div>

                    @if($nominee->class)
                        <p class="text-xs text-muted mt-1">{{ $nominee->class }}</p>
                    @endif
                    @if($nominee->description)
                        <p class="text-sm text-muted mt-3 line-clamp-3">{{ $nominee->description }}</p>
                    @endif

                    <div class="mt-5 space-y-2">
                        {{-- Bouton voir la preuve --}}
                        @if($category->requires_proof)
                            <div class="flex flex-wrap gap-2">
                                @if($nominee->proof_url)
                                    <a href="{{ $nominee->proof_url }}" target="_blank" rel="noopener"
                                       class="btn-ghost flex-1 !py-2 text-sm">🔗 Voir la preuve</a>
                                @endif
                                @if($nominee->proof_file)
                                    <a href="{{ $nominee->proof_file_url }}" target="_blank" rel="noopener" download
                                       class="btn-ghost flex-1 !py-2 text-sm">📎 Télécharger</a>
                                @endif
                            </div>
                        @endif

                        {{-- Bouton voter / état --}}
                        @if($isChosen)
                            <button type="button" class="btn-voted w-full" disabled>✓ Voté</button>
                        @elseif($hasVoted)
                            <button type="button" class="btn-ghost w-full opacity-50 cursor-not-allowed" disabled>Vote utilisé</button>
                        @elseif(! $isOpen)
                            <button type="button" class="btn-ghost w-full opacity-50 cursor-not-allowed" disabled>Vote clôturé</button>
                        @else
                            <button type="button"
                                    wire:click="vote({{ $nominee->id }})"
                                    wire:loading.attr="disabled"
                                    x-bind:disabled="submitting"
                                    @click="submitting = true"
                                    class="btn-gold w-full">
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
