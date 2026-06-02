<div wire:poll.5s>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-title text-3xl text-gradient-gold tracking-wide">TABLEAU DE BORD</h1>
            <p class="text-muted mt-1 flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-gold-main animate-pulse"></span>
                Temps réel · actualisation automatique (5s)
            </p>
        </div>
        <a href="{{ route('admin.results.export') }}" class="btn-ghost">⬇ Export CSV</a>
    </div>

    {{-- Stat cards --}}
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        @php
            $stats = [
                ['Votes exprimés', $totalVotes, '🗳️'],
                ['Participants', $participants.' / '.$totalVoters, '👥'],
                ['Catégories ouvertes', $openCategories.' / '.$totalCategories, '🏆'],
                ['Nominés', $totalNominees, '👤'],
            ];
        @endphp
        @foreach($stats as [$label, $value, $icon])
            <div class="gold-card is-hoverable">
                <div class="flex items-center justify-between">
                    <span class="text-2xl">{{ $icon }}</span>
                </div>
                <p class="font-title text-3xl text-gold-light mt-3">{{ $value }}</p>
                <p class="text-xs text-muted uppercase tracking-wider mt-1">{{ $label }}</p>
            </div>
        @endforeach
    </div>

    <div class="sep-gold-double w-full mb-8"></div>

    {{-- Progression par catégorie --}}
    <h2 class="font-title text-xl text-offwhite mb-5">Progression par catégorie</h2>
    <div class="grid gap-5 lg:grid-cols-2">
        @foreach($categories as $category)
            @php $top = $topNominees[$category->id] ?? null; @endphp
            <div class="gold-card">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div>
                        <h3 class="font-title text-offwhite">{{ $category->name }}</h3>
                        <p class="text-xs text-muted">{{ $category->voterTypeLabel() }}</p>
                    </div>
                    <span class="{{ $category->is_active ? 'badge-open' : 'badge-closed' }}">
                        {{ $category->is_active ? 'Ouvert' : 'Clôturé' }}
                    </span>
                </div>

                <div class="progress-track mb-2">
                    <div class="progress-fill" style="width: {{ round(($category->votes_count / $maxVotes) * 100) }}%"></div>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gold-light font-semibold">{{ $category->votes_count }} vote(s)</span>
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
