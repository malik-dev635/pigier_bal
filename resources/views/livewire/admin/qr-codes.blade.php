<div>
    <a href="{{ route('admin.home') }}" class="mb-4 inline-flex items-center gap-1 text-sm text-muted transition-colors hover:text-offwhite">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        Administration
    </a>

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-white sm:text-3xl">QR codes</h1>
        <p class="mt-1 text-sm text-muted">À projeter ou imprimer : les participants scannent pour voter. Cliquez « Projeter » pour l'afficher en grand (idéal pour élire le Roi et la Reine du bal en direct).</p>
    </div>

    {{-- QR général du site --}}
    <div class="card mb-6 flex flex-col items-center gap-4 p-5 sm:flex-row sm:items-center">
        <div class="qr-box aspect-square w-36 shrink-0 bg-white p-2">
            <div data-qr="{{ route('home') }}" class="h-full w-full"></div>
        </div>
        <div class="text-center sm:text-left">
            <p class="eyebrow text-[10px]">Accès général</p>
            <p class="mt-1 font-medium text-white">Page d'accueil du site</p>
            <p class="mt-1 break-all text-xs text-muted">{{ route('home') }}</p>
            <button class="btn-secondary btn-sm mt-3"
                    @click="$dispatch('qr-open', { name: 'Pigier\'s Élites Awards', url: {{ \Illuminate\Support\Js::from(route('home')) }} })">Projeter</button>
        </div>
    </div>

    {{-- Un QR par récompense --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($categories as $category)
            <div class="card flex flex-col items-center p-5 text-center">
                <div class="qr-box aspect-square w-40 bg-white p-2.5">
                    <div data-qr="{{ route('vote.category', $category->slug) }}" class="h-full w-full"></div>
                </div>
                <p class="mt-3 font-medium text-white">{{ $category->name }}</p>
                <p class="mt-0.5 text-xs text-muted">{{ $category->voterTypeLabel() }}</p>
                <button class="btn-secondary btn-sm mt-3"
                        @click="$dispatch('qr-open', { name: {{ \Illuminate\Support\Js::from($category->name) }}, url: {{ \Illuminate\Support\Js::from(route('vote.category', $category->slug)) }} })">
                    Projeter
                </button>
            </div>
        @endforeach
    </div>

    {{-- Mode projection plein écran --}}
    <div x-data="{ open: false, name: '', url: '' }"
         @qr-open.window="open = true; name = $event.detail.name; url = $event.detail.url; $nextTick(() => window.makeQr($refs.big, url))"
         @keydown.escape.window="open = false">
        <div x-show="open" x-cloak @click="open = false"
             class="fixed inset-0 z-[100] flex cursor-pointer flex-col items-center justify-center bg-white p-6">
            <h2 class="mb-5 text-center text-2xl font-semibold text-black sm:text-4xl" x-text="name"></h2>
            <div x-ref="big" class="qr-box aspect-square w-[78vmin] max-w-[620px] bg-white"></div>
            <p class="mt-5 break-all text-center text-sm text-gray-500" x-text="url"></p>
            <p class="mt-2 text-xs text-gray-400">Touchez l'écran (ou Échap) pour fermer</p>
        </div>
    </div>
</div>
