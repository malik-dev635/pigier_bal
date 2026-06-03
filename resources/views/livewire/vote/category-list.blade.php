<div>
    <div class="mb-8">
        <p class="eyebrow">Pigier's Élites Awards 2026</p>
        <h1 class="mt-2 text-3xl sm:text-4xl">Les récompenses</h1>
        <p class="mt-3 max-w-2xl text-sm text-muted">
            Choisissez une récompense pour découvrir les nominés et soutenir votre favori. Un seul vote par récompense.
        </p>
    </div>

    @if($categories->isEmpty())
        <div class="card p-10 text-center">
            <p class="text-sm text-muted">Aucune récompense ne vous est ouverte pour le moment.</p>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($categories as $category)
                @php $hasVoted = in_array($category->id, $votedCategoryIds); @endphp
                <a href="{{ route('vote.category', $category->slug) }}" class="card card-hover flex flex-col overflow-hidden">
                    @if($category->image_url)
                        <div class="flex aspect-[4/5] w-full items-center justify-center overflow-hidden border-b border-line bg-bg-surface">
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="h-full w-full object-contain">
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
                                <span class="badge-gold">Vote enregistré</span>
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
