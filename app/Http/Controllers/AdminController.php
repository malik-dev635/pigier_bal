<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Nominee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    /**
     * Page « Programme » : liste imprimable des nominés pour le maître de cérémonie.
     */
    public function programme(): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $categories = Category::query()
            ->with(['nominees' => fn ($q) => $q->withCount('votes')
                ->orderByDesc('votes_count')
                ->orderBy('last_name')
                ->orderBy('first_name')])
            ->withCount('votes')
            ->orderBy('voter_type')
            ->orderBy('name')
            ->get();

        return view('admin.programme', [
            'categories' => $categories,
            'categoriesForSelect' => Category::orderBy('name')->get(),
        ]);
    }

    /**
     * Ajoute un nominé au programme (par défaut « hors vote »).
     */
    public function programmeStoreNominee(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'first_name' => 'nullable|string|max:255', // vide pour une association/entité
            'last_name' => 'required|string|max:255',
            'class' => 'nullable|string|max:255',
        ]);

        Nominee::create([
            'category_id' => $data['category_id'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'class' => $data['class'] ?? null,
            'is_active' => true,
            'is_approved' => true,
            'is_votable' => $request->boolean('is_votable'), // décoché = hors vote
        ]);

        return redirect()->route('admin.programme')->with('status', 'Nominé ajouté au programme.');
    }

    /**
     * Export CSV de tous les résultats (classement par catégorie).
     */
    public function exportResults(): StreamedResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $categories = Category::query()
            ->with(['nominees' => fn ($q) => $q->withCount('votes')->orderByDesc('votes_count')])
            ->withCount('votes')
            ->orderBy('voter_type')
            ->orderBy('name')
            ->get();

        $filename = 'resultats-pigier-elites-'.now()->format('Y-m-d_His').'.csv';

        return response()->streamDownload(function () use ($categories) {
            $out = fopen('php://output', 'w');

            // BOM UTF-8 pour Excel.
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'Récompense', 'Votants', 'Statut', 'Rang',
                'Nominé', 'Classe', 'Votes', 'Pourcentage',
            ], ';');

            foreach ($categories as $category) {
                $total = max(1, $category->votes_count);
                $rank = 0;

                if ($category->nominees->isEmpty()) {
                    fputcsv($out, [
                        $category->name,
                        $category->voterTypeLabel(),
                        $category->is_active ? 'Ouvert' : 'Clôturé',
                        '-', '(aucun nominé)', '-', 0, '0%',
                    ], ';');
                    continue;
                }

                foreach ($category->nominees as $nominee) {
                    $rank++;
                    $pct = round(($nominee->votes_count / $total) * 100, 1);
                    fputcsv($out, [
                        $category->name,
                        $category->voterTypeLabel(),
                        $category->is_active ? 'Ouvert' : 'Clôturé',
                        $rank,
                        $nominee->full_name,
                        $nominee->class ?? '-',
                        $nominee->votes_count,
                        $pct.'%',
                    ], ';');
                }
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
