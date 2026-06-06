<div wire:poll.5s>
    <a href="{{ route('admin.home') }}" class="mb-4 inline-flex items-center gap-1 text-sm text-muted transition-colors hover:text-offwhite">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        Administration
    </a>

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-white sm:text-3xl">Affichage en direct</h1>
            <p class="mt-1 flex items-center gap-2 text-sm text-muted">
                <span class="inline-block h-2 w-2 animate-pulse rounded-full bg-gold-main"></span>
                Mise à jour automatique · à projeter pendant le vote
            </p>
        </div>
        <select wire:model.live="categoryId" class="select sm:max-w-xs">
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}{{ $cat->is_active ? '' : ' (clôturé)' }}</option>
            @endforeach
        </select>
    </div>

    @if(! $category)
        <div class="card p-12 text-center"><p class="text-muted">Choisissez une récompense.</p></div>
    @else
        <div class="card p-6 sm:p-10">
            <div class="mb-8 text-center">
                <p class="eyebrow">{{ $category->voterTypeLabel() }}</p>
                <h2 class="mt-2 text-3xl font-semibold text-white sm:text-5xl">{{ $category->name }}</h2>
                <p class="mt-3 text-sm text-muted">{{ $total }} vote{{ $total > 1 ? 's' : '' }} exprimé{{ $total > 1 ? 's' : '' }}</p>
            </div>

            @if($nominees->isEmpty())
                <p class="text-center text-muted">Aucun candidat éligible pour cette récompense.</p>
            @else
                <div class="mx-auto max-w-3xl space-y-6">
                    @foreach($nominees as $nominee)
                        @php
                            $pct = $total ? round($nominee->votes_count / $total * 100) : 0;
                            $isLeader = $loop->first && $nominee->votes_count > 0;
                        @endphp
                        <div>
                            <div class="mb-2 flex items-baseline justify-between gap-4">
                                <span class="flex items-center gap-2 text-xl font-semibold sm:text-2xl {{ $isLeader ? 'text-gold-light' : 'text-white' }}">
                                    {{ $nominee->full_name }}
                                    @if($isLeader)
                                        <svg class="h-5 w-5 text-gold-main" fill="currentColor" viewBox="0 0 24 24"><path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm0 2h14v2H5v-2z"/></svg>
                                    @endif
                                </span>
                                <span class="shrink-0 text-xl font-semibold sm:text-2xl {{ $isLeader ? 'text-gold-light' : 'text-muted' }}">{{ $pct }}%</span>
                            </div>
                            <div class="h-5 w-full overflow-hidden bg-bg-surface">
                                <div class="h-full transition-all duration-700 {{ $isLeader ? 'bg-gold-main' : 'bg-gold-dark' }}" style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="mt-1 text-sm text-muted">{{ $nominee->votes_count }} voix</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</div>
