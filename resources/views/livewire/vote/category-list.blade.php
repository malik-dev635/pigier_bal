<div>
    <div class="mb-10 text-center">
        <h1 class="font-title text-3xl sm:text-4xl text-gradient-gold tracking-widest">CATÉGORIES DE VOTE</h1>
        <p class="text-muted mt-3 max-w-2xl mx-auto">
            Choisissez une catégorie pour découvrir les nominés et soutenir votre favori.
            Vous disposez d'<span class="text-gold-light">un seul vote par catégorie</span>.
        </p>
        <div class="sep-gold-double w-48 mx-auto mt-5"></div>
    </div>

    @if($categories->isEmpty())
        <div class="gold-card text-center py-16">
            <p class="text-muted">Aucune catégorie ne vous est accessible pour le moment.</p>
        </div>
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($categories as $category)
                @php $hasVoted = in_array($category->id, $votedCategoryIds); @endphp
                <div class="gold-card is-hoverable flex flex-col animate-fade-slide-up">
                    @if($category->image_url)
                        <div class="-mx-6 -mt-6 mb-4 aspect-[16/9] overflow-hidden rounded-t-lg border-b" style="border-color: var(--gold-dark)">
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <h2 class="font-title text-lg text-offwhite leading-snug">{{ $category->name }}</h2>
                        @if($category->is_active)
                            <span class="badge-open whitespace-nowrap">● Ouvert</span>
                        @else
                            <span class="badge-closed whitespace-nowrap">● Clôturé</span>
                        @endif
                    </div>

                    @if($category->description)
                        <p class="text-sm text-muted mb-4 line-clamp-2">{{ $category->description }}</p>
                    @endif

                    <div class="flex flex-wrap gap-2 mb-5 text-xs">
                        <span class="badge-neutral">{{ $category->nominees_count }} nominé(s)</span>
                        @if($category->requires_proof)
                            <span class="badge-neutral">Preuve requise</span>
                        @endif
                        @if($hasVoted)
                            <span class="badge-success">✓ Voté</span>
                        @endif
                    </div>

                    <div class="mt-auto">
                        <a href="{{ route('vote.category', $category->slug) }}"
                           class="{{ $hasVoted ? 'btn-ghost' : 'btn-gold' }} w-full">
                            {{ $hasVoted ? 'Voir mon vote' : ($category->is_active ? 'Voter' : 'Voir les nominés') }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
