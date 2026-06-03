@php($size = $size ?? 'h-8 w-auto sm:h-9')
{{-- Logo pblog → site pblog.ci --}}
<a href="https://pblog.ci" target="_blank" rel="noopener" title="pblog.ci" class="flex shrink-0 items-center">
    <img src="{{ asset('images/logo-pblog-affiche.png') }}" alt="pblog.ci" class="{{ $size }}">
</a>
{{-- Logos bal + awards → accueil --}}
<a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2.5 sm:gap-3">
    <img src="{{ asset('images/logo-bal.png') }}" alt="Bal de fin d'année" class="{{ $size }}">
    <img src="{{ asset('images/logo-pigier-award.png') }}" alt="Pigier's Élites Awards" class="{{ $size }}">
</a>
