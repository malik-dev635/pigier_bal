<div>
    <div class="mb-8">
        <p class="eyebrow">Pigier's Élites Awards 2026</p>
        <h1 class="mt-2 text-3xl sm:text-4xl">Votez pour vos favoris</h1>
        <p class="mt-3 max-w-2xl text-sm text-muted">
            Parcourez les récompenses, découvrez les nominés et soutenez celles et ceux qui le méritent. Un seul vote par récompense ,  à vous de jouer&nbsp;!
        </p>

        @if($totalCount > 0)
            <div class="mt-6 max-w-md">
                <div class="mb-1.5 flex items-center justify-between text-xs">
                    <span class="text-muted">Votre progression</span>
                    <span class="font-medium text-gold-light">{{ $votedCount }} / {{ $totalCount }}</span>
                </div>
                <div class="progress">
                    <div class="progress-bar" style="width: {{ round($votedCount / $totalCount * 100) }}%"></div>
                </div>
                @if($votedCount < $totalCount)
                    <p class="mt-2 text-xs text-muted">Il vous reste {{ $totalCount - $votedCount }} récompense{{ ($totalCount - $votedCount) > 1 ? 's' : '' }} à voter.</p>
                @else
                    <p class="mt-2 text-xs text-gold-light">Vous avez voté pour toutes les récompenses. Merci&nbsp;!</p>
                @endif
            </div>
        @endif
    </div>

    @if($categories->isEmpty())
        <div class="card p-10 text-center">
            <p class="text-sm text-muted">Aucune récompense ne vous est ouverte pour le moment.</p>
        </div>
    @else
        <div class="grid items-start gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($categories as $category)
                @php $hasVoted = in_array($category->id, $votedCategoryIds); @endphp
                <a href="{{ route('vote.category', $category->slug) }}" class="card card-hover flex flex-col overflow-hidden {{ $hasVoted ? 'opacity-70 transition-opacity hover:opacity-100' : '' }}">
                    @if($category->image_url)
                        <div class="overflow-hidden border-b border-line">
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="block w-full">
                        </div>
                    @endif

                    <div class="flex flex-1 flex-col p-5">
                        <div class="flex items-start justify-between gap-3">
                            <h2 class="font-semibold text-white">{{ $category->name }}</h2>
                            @if($category->is_active)
                                <span class="status status-open shrink-0"><span class="status-dot"></span>Ouvert</span>
                            @else
                                <span class="status status-closed shrink-0"><span class="status-dot"></span>Clôturé</span>
                            @endif
                        </div>

                        @if($category->description)
                            <p class="mt-2 line-clamp-2 text-sm text-muted">{{ $category->description }}</p>
                        @endif

                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <span class="badge-muted">{{ $category->nominees_count }} nominé{{ $category->nominees_count > 1 ? 's' : '' }}</span>
                            @if($hasVoted)
                                <span class="badge-gold">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    Voté
                                </span>
                            @endif
                        </div>

                        <div class="mt-4 flex items-center text-sm font-medium text-gold-light">
                            {{ $hasVoted ? 'Voir mon vote' : ($category->is_active ? 'Voter' : 'Voir les nominés') }}
                            <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
