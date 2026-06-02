<div wire:poll.5s>
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-white">Tableau de bord</h1>
            <p class="mt-1 flex items-center gap-2 text-sm text-muted">
                <span class="status-dot bg-gold-main"></span>
                Actualisation automatique toutes les 5 secondes
            </p>
        </div>
        <a href="{{ route('admin.results.export') }}" class="btn-secondary">Exporter en CSV</a>
    </div>

    {{-- Indicateurs --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @php
            $stats = [
                ['Votes exprimés', $totalVotes],
                ['Participants', $participants.' / '.$totalVoters],
                ['Catégories ouvertes', $openCategories.' / '.$totalCategories],
                ['Nominés', $totalNominees],
            ];
        @endphp
        @foreach($stats as [$label, $value])
            <div class="card p-5">
                <p class="text-sm text-muted">{{ $label }}</p>
                <p class="mt-2 text-2xl font-semibold text-white">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    {{-- Progression par catégorie --}}
    <h2 class="mb-4 mt-8 text-lg font-semibold text-white">Progression par catégorie</h2>
    <div class="grid gap-4 lg:grid-cols-2">
        @foreach($categories as $category)
            @php $top = $topNominees[$category->id] ?? null; @endphp
            <div class="card p-5">
                <div class="mb-3 flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-medium text-white">{{ $category->name }}</h3>
                        <p class="text-xs text-muted">{{ $category->voterTypeLabel() }}</p>
                    </div>
                    <span class="status {{ $category->is_active ? 'status-open' : 'status-closed' }} shrink-0">
                        <span class="status-dot"></span>{{ $category->is_active ? 'Ouvert' : 'Clôturé' }}
                    </span>
                </div>

                <div class="progress mb-2">
                    <div class="progress-bar" style="width: {{ round(($category->votes_count / $maxVotes) * 100) }}%"></div>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="font-medium text-gold-light">{{ $category->votes_count }} vote{{ $category->votes_count > 1 ? 's' : '' }}</span>
                    @if($top && $top->votes_count > 0)
                        <span class="text-muted">En tête : <span class="text-offwhite">{{ $top->full_name }}</span> ({{ $top->votes_count }})</span>
                    @else
                        <span class="text-muted">Aucun vote</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
