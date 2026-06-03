@php($ogImage = asset('images/img-og.png'))
@php($ogTitle = $ogTitle ?? "Pigier's Élites Awards — Bal de fin d'année 2026")
@php($ogDescription = $ogDescription ?? "Votez pour vos favoris du Bal de fin d'année 2026.")

<meta name="description" content="{{ $ogDescription }}">

{{-- Open Graph (WhatsApp, Facebook, LinkedIn…) --}}
<meta property="og:type" content="website">
<meta property="og:site_name" content="Pigier's Élites Awards">
<meta property="og:title" content="{{ $ogTitle }}">
<meta property="og:description" content="{{ $ogDescription }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:width" content="1080">
<meta property="og:image:height" content="1080">
<meta property="og:url" content="{{ url()->current() }}">

{{-- Twitter / X --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $ogTitle }}">
<meta name="twitter:description" content="{{ $ogDescription }}">
<meta name="twitter:image" content="{{ $ogImage }}">
