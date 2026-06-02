<div>
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-white sm:text-3xl">Mes votes</h1>
        <p class="mt-2 text-sm text-muted">Récapitulatif des votes que vous avez exprimés.</p>
    </div>

    @if($votes->isEmpty())
        <div class="card p-10 text-center">
            <p class="text-sm text-muted">Vous n'avez encore voté pour aucune récompense.</p>
            <a href="{{ route('vote.index') }}" class="btn-primary mt-4">Commencer à voter</a>
        </div>
    @else
        <div class="grid gap-3 sm:grid-cols-2">
            @foreach($votes as $vote)
                <div class="card flex items-center gap-4 p-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-line bg-bg-surface">
                        @if($vote->nominee?->photo_url)
                            <img src="{{ $vote->nominee->photo_url }}" alt="" class="h-full w-full object-cover">
                        @else
                            <span class="text-sm font-semibold text-muted">{{ strtoupper(substr($vote->nominee?->first_name ?? '?',0,1)) }}</span>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-xs text-muted">{{ $vote->category?->name ?? 'Récompense supprimée' }}</p>
                        <p class="truncate font-medium text-white">{{ $vote->nominee?->full_name ?? 'Nominé supprimé' }}</p>
                        <p class="mt-0.5 text-xs text-muted">{{ $vote->created_at->translatedFormat('d M Y à H:i') }}</p>
                    </div>
                    <svg class="h-5 w-5 shrink-0 text-gold-light" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                </div>
            @endforeach
        </div>
    @endif
</div>
