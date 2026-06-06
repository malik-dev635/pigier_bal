<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Affichage en direct')]
class LiveResults extends Component
{
    public ?int $categoryId = null;

    public function mount(): void
    {
        $this->categoryId = request()->integer('category')
            ?: (Category::active()->orderBy('name')->value('id') ?? Category::orderBy('name')->value('id'));
    }

    public function render(): View
    {
        $this->authorize('viewAny', Category::class);

        $categories = Category::query()->orderBy('voter_type')->orderBy('name')->get(['id', 'name', 'is_active']);

        $category = $this->categoryId ? Category::find($this->categoryId) : null;

        $nominees = collect();
        $total = 0;

        if ($category) {
            $nominees = $category->votableNominees()
                ->withCount('votes')
                ->orderByDesc('votes_count')
                ->orderBy('last_name')
                ->get();
            $total = (int) $nominees->sum('votes_count');
        }

        return view('livewire.admin.live-results', [
            'categories' => $categories,
            'category' => $category,
            'nominees' => $nominees,
            'total' => $total,
        ]);
    }
}
