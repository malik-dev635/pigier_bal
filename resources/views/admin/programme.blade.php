<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programme — Pigier's Élites Awards</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: #ECECEE;
            color: #1a1a1a;
            font-family: Georgia, 'Times New Roman', serif;
            line-height: 1.5;
        }
        a { color: inherit; }

        /* Barre d'outils (non imprimée) */
        .toolbar {
            background: #111113;
            color: #ECECEE;
            font-family: Inter, Arial, sans-serif;
            padding: 14px 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }
        .toolbar a.back { color: #8A8A93; text-decoration: none; font-size: 14px; }
        .toolbar a.back:hover { color: #ECECEE; }
        .btn {
            display: inline-block; cursor: pointer; border: 1px solid #C9A24B;
            background: #C9A24B; color: #111; font-weight: 600; font-size: 13px;
            padding: 8px 16px; border-radius: 0; text-decoration: none;
        }
        .btn-ghost { background: transparent; color: #ECECEE; border-color: #3a3a42; }
        .status { width: 100%; color: #E3C878; font-size: 13px; }

        /* Formulaire d'ajout */
        details { width: 100%; font-family: Inter, Arial, sans-serif; }
        details summary { cursor: pointer; color: #E3C878; font-size: 13px; }
        .addform { margin-top: 12px; display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; max-width: 640px; }
        .addform .full { grid-column: 1 / -1; }
        .addform label { display: block; font-size: 12px; color: #8A8A93; margin-bottom: 4px; }
        .addform input, .addform select {
            width: 100%; padding: 8px 10px; background: #17171A; color: #ECECEE;
            border: 1px solid #3a3a42; font-size: 14px;
        }
        .addform .check { display: flex; align-items: center; gap: 8px; color: #ECECEE; font-size: 14px; }

        /* Document */
        .document { max-width: 800px; margin: 24px auto; background: #fff; padding: 48px 56px; box-shadow: 0 1px 6px rgba(0,0,0,.15); }
        .doc-header { text-align: center; border-bottom: 2px solid #C9A24B; padding-bottom: 20px; margin-bottom: 28px; }
        .doc-logos { display: flex; justify-content: center; gap: 18px; margin-bottom: 14px; }
        .doc-logos img { height: 46px; width: auto; }
        .doc-header h1 { font-size: 28px; margin: 0; letter-spacing: .02em; }
        .doc-header p { margin: 6px 0 0; color: #555; font-family: Inter, Arial, sans-serif; font-size: 13px; text-transform: uppercase; letter-spacing: .15em; }

        section.award { margin-bottom: 26px; break-inside: avoid; page-break-inside: avoid; }
        section.award h2 { font-size: 19px; margin: 0 0 2px; }
        section.award .meta { margin: 0 0 10px; font-family: Inter, Arial, sans-serif; font-size: 12px; color: #777; text-transform: uppercase; letter-spacing: .08em; }
        section.award ol { margin: 0; padding-left: 22px; }
        section.award li { margin: 3px 0; font-size: 16px; }
        section.award li .cls { color: #777; font-size: 14px; }
        section.award li .tag { color: #b08400; font-family: Inter, Arial, sans-serif; font-size: 11px; text-transform: uppercase; letter-spacing: .06em; }
        section.award .empty { color: #999; font-style: italic; font-family: Inter, Arial, sans-serif; font-size: 14px; }

        @media print {
            body { background: #fff; }
            .no-print { display: none !important; }
            .document { box-shadow: none; margin: 0; max-width: none; padding: 0 8px; }
            @page { margin: 16mm; }
        }
    </style>
</head>
<body>
    <div class="toolbar no-print">
        <a href="{{ route('admin.home') }}" class="back">← Administration</a>
        <button class="btn" onclick="window.print()">Imprimer / Exporter en PDF</button>
        @if(session('status'))<span class="status">✓ {{ session('status') }}</span>@endif
        @if($errors->any())<span class="status" style="color:#e07a7a">{{ $errors->first() }}</span>@endif

        <details>
            <summary>+ Ajouter un nominé au programme (hors vote par défaut)</summary>
            <form method="POST" action="{{ route('admin.programme.nominee') }}" class="addform">
                @csrf
                <div class="full">
                    <label>Récompense</label>
                    <select name="category_id" required>
                        @foreach($categoriesForSelect as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Prénom <span style="color:#777">(vide si association)</span></label>
                    <input type="text" name="first_name">
                </div>
                <div>
                    <label>Nom (ou nom de l'association)</label>
                    <input type="text" name="last_name" required>
                </div>
                <div>
                    <label>Classe (facultatif)</label>
                    <input type="text" name="class" placeholder="Ex : RGL3A">
                </div>
                <div class="full">
                    <label class="check"><input type="checkbox" name="is_votable" value="1"> Présenter aussi au vote (sinon : seulement dans le programme)</label>
                </div>
                <div class="full">
                    <button type="submit" class="btn">Ajouter</button>
                </div>
            </form>
        </details>
    </div>

    <div class="document">
        <div class="doc-header">
            <div class="doc-logos">
                <img src="{{ asset('images/logo-bal.png') }}" alt="">
                <img src="{{ asset('images/logo-pigier-award.png') }}" alt="">
            </div>
            <h1>Pigier's Élites Awards</h1>
            <p>Bal de fin d'année 2026 · Programme des nominés</p>
        </div>

        @foreach($categories as $category)
            <section class="award">
                <h2>{{ $category->name }}</h2>
                <p class="meta">{{ $category->voterTypeLabel() }}</p>
                @if($category->nominees->isEmpty())
                    <p class="empty">Aucun nominé.</p>
                @else
                    <ol>
                        @foreach($category->nominees as $nominee)
                            <li>
                                {{ $nominee->full_name }}
                                @if($nominee->class)<span class="cls"> — {{ $nominee->class }}</span>@endif
                                @unless($nominee->is_votable)<span class="tag"> · hors vote</span>@endunless
                            </li>
                        @endforeach
                    </ol>
                @endif
            </section>
        @endforeach
    </div>
</body>
</html>
