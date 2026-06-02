<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
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
                'Catégorie', 'Type de votant', 'Statut', 'Rang',
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
