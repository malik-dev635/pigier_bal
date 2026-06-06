<div wire:poll.5s class="flex min-h-screen flex-col">
    {{-- Barre de contrôle (discrète) --}}
    <div class="flex items-center justify-between gap-3 border-b border-line px-4 py-3 sm:px-6">
        <a href="{{ route('admin.results') }}" class="inline-flex items-center gap-1 text-sm text-muted transition-colors hover:text-offwhite">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Résultats
        </a>
        <div class="flex items-center gap-2 sm:gap-3">
            <span class="hidden items-center gap-2 text-xs text-muted sm:flex">
                <span class="inline-block h-2 w-2 animate-pulse rounded-full bg-gold-main"></span>En direct
            </span>
            <select wire:model.live="categoryId" class="select !py-1.5 text-sm sm:max-w-[240px]">
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}{{ $cat->is_active ? '' : ' (clôturé)' }}</option>
                @endforeach
            </select>
            <button type="button" onclick="if(document.fullscreenElement){document.exitFullscreen()}else{document.documentElement.requestFullscreen()}" class="btn-secondary btn-sm">Plein écran</button>
        </div>
    </div>

    {{-- Affichage --}}
    <main class="flex flex-1 items-center justify-center px-4 py-8 sm:px-10">
        @if(! $category)
            <p class="text-muted">Choisissez une récompense.</p>
        @else
            <div class="w-full max-w-4xl">
                <div class="mb-10 text-center">
                    <p class="eyebrow">Vote · {{ $category->voterTypeLabel() }}</p>
                    <h1 class="mt-3 text-4xl font-semibold text-white sm:text-6xl">{{ $category->name }}</h1>
                    <p class="mt-3 text-base text-muted">{{ $total }} vote{{ $total > 1 ? 's' : '' }} exprimé{{ $total > 1 ? 's' : '' }}</p>
                </div>

                @if($nominees->isEmpty())
                    <p class="text-center text-muted">Aucun candidat éligible pour cette récompense.</p>
                @else
                    <div class="space-y-7">
                        @foreach($nominees as $nominee)
                            @php
                                $pct = $total ? round($nominee->votes_count / $total * 100) : 0;
                                $isLeader = $loop->first && $nominee->votes_count > 0;
                            @endphp
                            <div>
                                <div class="mb-2 flex items-baseline justify-between gap-4">
                                    <span class="flex items-center gap-2 text-2xl font-semibold sm:text-3xl {{ $isLeader ? 'text-gold-light' : 'text-white' }}">
                                        {{ $nominee->full_name }}
                                        @if($isLeader)
                                            <svg class="h-6 w-6 text-gold-main sm:h-7 sm:w-7" fill="currentColor" viewBox="0 0 24 24"><path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm0 2h14v2H5v-2z"/></svg>
                                        @endif
                                    </span>
                                    <span class="shrink-0 text-2xl font-bold sm:text-4xl {{ $isLeader ? 'text-gold-light' : 'text-muted' }}">{{ $pct }}%</span>
                                </div>
                                <div class="h-6 w-full overflow-hidden bg-bg-surface sm:h-8">
                                    <div class="h-full transition-all duration-700 {{ $isLeader ? 'bg-gold-main' : 'bg-gold-dark' }}" style="width: {{ $pct }}%"></div>
                                </div>
                                <p class="mt-1.5 text-sm text-muted sm:text-base">{{ $nominee->votes_count }} voix</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </main>
</div>
