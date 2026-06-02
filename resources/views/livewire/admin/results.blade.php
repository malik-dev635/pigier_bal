<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-title text-3xl text-gradient-gold tracking-wide">RÉSULTATS</h1>
            <p class="text-muted mt-1">Classement détaillé par catégorie.</p>
        </div>
        <a href="{{ route('admin.results.export') }}" class="btn-gold">⬇ Exporter en CSV</a>
    </div>

    <div class="space-y-6">
        @foreach($categories as $category)
            @php $total = max(1, $category->votes_count); @endphp
            <div class="gold-card">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                    <div>
                        <h2 class="font-title text-xl text-offwhite">{{ $category->name }}</h2>
                        <p class="text-xs text-muted mt-1">
                            {{ $category->voterTypeLabel() }} ·
                            {{ $category->votes_count }} vote(s) ·
                            <span class="{{ $category->is_active ? 'text-gold-light' : 'text-muted' }}">{{ $category->is_active ? 'Ouvert' : 'Clôturé' }}</span>
                        </p>
                    </div>
                    <button wire:click="resetVotes({{ $category->id }})"
                            wire:confirm="Réinitialiser TOUS les votes de « {{ $category->name }} » ? Action irréversible."
                            class="btn-danger !px-4 !py-2 text-xs">↺ Réinitialiser les votes</button>
                </div>

                @if($category->nominees->isEmpty() || $category->votes_count === 0)
                    <p class="text-muted text-sm">{{ $category->nominees->isEmpty() ? 'Aucun nominé.' : 'Aucun vote pour le moment.' }}</p>
                @else
                    <div class="space-y-4">
                        @foreach($category->nominees as $index => $nominee)
                            @php $pct = round(($nominee->votes_count / $total) * 100, 1); @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1.5 text-sm">
                                    <span class="flex items-center gap-2 text-offwhite">
                                        <span class="font-title {{ $index === 0 ? 'text-gold-main' : 'text-muted' }}">#{{ $index + 1 }}</span>
                                        {{ $nominee->full_name }}
                                        @if($index === 0 && $nominee->votes_count > 0)<span class="text-gold-main">👑</span>@endif
                                    </span>
                                    <span class="text-gold-light font-semibold">{{ $nominee->votes_count }} · {{ $pct }}%</span>
                                </div>
                                <div class="progress-track">
                                    <div class="progress-fill" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
