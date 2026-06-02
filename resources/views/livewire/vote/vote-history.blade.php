<div>
    <div class="mb-8">
        <h1 class="font-title text-3xl text-gradient-gold tracking-wide">MES VOTES</h1>
        <p class="text-muted mt-2">Récapitulatif de tous les votes que vous avez exprimés.</p>
        <div class="sep-gold mt-5"></div>
    </div>

    @if($votes->isEmpty())
        <div class="gold-card text-center py-16">
            <p class="text-muted mb-4">Vous n'avez encore voté dans aucune catégorie.</p>
            <a href="{{ route('vote.index') }}" class="btn-gold">Commencer à voter</a>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach($votes as $vote)
                <div class="gold-card is-hoverable flex items-center gap-4 animate-fade-slide-up">
                    <div class="w-14 h-14 rounded-md overflow-hidden bg-bg-surface flex items-center justify-center border shrink-0" style="border-color: var(--gold-dark)">
                        @if($vote->nominee?->photo_url)
                            <img src="{{ $vote->nominee->photo_url }}" alt="" class="w-full h-full object-cover">
                        @else
                            <span class="text-gold-dark font-title">★</span>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs text-muted uppercase tracking-wider truncate">{{ $vote->category?->name ?? 'Catégorie supprimée' }}</p>
                        <p class="font-title text-offwhite truncate">{{ $vote->nominee?->full_name ?? 'Nominé supprimé' }}</p>
                        <p class="text-xs text-muted mt-1">{{ $vote->created_at->translatedFormat('d M Y à H:i') }}</p>
                    </div>
                    <span class="badge-success">✓</span>
                </div>
            @endforeach
        </div>
    @endif
</div>
