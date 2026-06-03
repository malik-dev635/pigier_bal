<div>
    <a href="{{ route('admin.home') }}" class="mb-4 inline-flex items-center gap-1 text-sm text-muted transition-colors hover:text-offwhite">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        Administration
    </a>

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-white">Résultats</h1>
            <p class="mt-1 text-sm text-muted">Classement détaillé par récompense.</p>
        </div>
        <a href="{{ route('admin.results.export') }}" class="btn-primary">Exporter en CSV</a>
    </div>

    <div class="space-y-4">
        @foreach($categories as $category)
            @php $total = max(1, $category->votes_count); @endphp
            <div wire:key="res-{{ $category->id }}" class="card p-5">
                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-white">{{ $category->name }}</h2>
                        <p class="mt-1 text-xs text-muted">
                            {{ $category->voterTypeLabel() }} · {{ $category->votes_count }} vote{{ $category->votes_count > 1 ? 's' : '' }} ·
                            {{ $category->is_active ? 'Ouvert' : 'Clôturé' }}
                        </p>
                    </div>
                    <button wire:click="resetVotes({{ $category->id }})"
                            wire:confirm="Réinitialiser tous les votes de « {{ $category->name }} » ? Cette action est irréversible."
                            class="btn-danger btn-sm">Réinitialiser les votes</button>
                </div>

                @if($category->nominees->isEmpty() || $category->votes_count === 0)
                    <p class="text-sm text-muted">{{ $category->nominees->isEmpty() ? 'Aucun nominé.' : 'Aucun vote pour le moment.' }}</p>
                @else
                    <div class="space-y-4">
                        @foreach($category->nominees as $index => $nominee)
                            @php $pct = round(($nominee->votes_count / $total) * 100, 1); @endphp
                            <div>
                                <div class="mb-1.5 flex items-center justify-between text-sm">
                                    <span class="flex items-center gap-2 text-offwhite">
                                        <span class="w-6 text-xs font-medium {{ $index === 0 ? 'text-gold-light' : 'text-muted' }}">{{ $index + 1 }}.</span>
                                        {{ $nominee->full_name }}
                                    </span>
                                    <span class="font-medium text-gold-light">{{ $nominee->votes_count }} · {{ $pct }}%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
