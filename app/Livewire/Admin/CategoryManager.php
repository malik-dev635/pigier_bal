<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
#[Title('Récompenses')]
class CategoryManager extends Component
{
    use WithFileUploads;

    public bool $showModal = false;

    public ?int $editingId = null;

    /** Affiche de la catégorie (upload temporaire + chemin existant). */
    public $image = null;
    public ?string $existingImage = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:2000')]
    public ?string $description = null;

    #[Validate('required|in:eleve,professeur,both')]
    public string $voter_type = 'eleve';

    #[Validate('required|integer|min:1|max:50')]
    public int $max_nominees = 5;

    public bool $is_active = true;

    public bool $requires_proof = false;

    public ?string $proof_type = null;

    protected function rules(): array
    {
        return [
            'image' => 'nullable|image|max:8192',
            'proof_type' => [
                Rule::requiredIf($this->requires_proof),
                'nullable',
                'in:url,file,both',
            ],
        ];
    }

    public function create(): void
    {
        $this->authorize('create', Category::class);
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $category = Category::findOrFail($id);
        $this->authorize('update', $category);

        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->voter_type = $category->voter_type;
        $this->max_nominees = $category->max_nominees;
        $this->is_active = $category->is_active;
        $this->requires_proof = $category->requires_proof;
        $this->proof_type = $category->proof_type;
        $this->existingImage = $category->image;
        $this->image = null;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'voter_type' => $this->voter_type,
            'max_nominees' => $this->max_nominees,
            'is_active' => $this->is_active,
            'requires_proof' => $this->requires_proof,
            'proof_type' => $this->requires_proof ? $this->proof_type : null,
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('categories/images', 'public');
        }

        if ($this->editingId) {
            $category = Category::findOrFail($this->editingId);
            $this->authorize('update', $category);
            $category->update($data);
            $message = 'Récompense mise à jour.';
        } else {
            $this->authorize('create', Category::class);
            Category::create($data);
            $message = 'Récompense créée.';
        }

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('toast', message: $message);
    }

    public function toggle(int $id): void
    {
        $category = Category::findOrFail($id);
        $this->authorize('toggle', $category);
        $category->update(['is_active' => ! $category->is_active]);

        $this->dispatch('toast', message: $category->is_active
            ? 'Vote ouvert pour « '.$category->name.' ».'
            : 'Vote clôturé pour « '.$category->name.' ».');
    }

    public function delete(int $id): void
    {
        $category = Category::findOrFail($id);
        $this->authorize('delete', $category);
        $category->delete();

        $this->dispatch('toast', message: 'Récompense supprimée.');
    }

    public function resetForm(): void
    {
        $this->reset([
            'editingId', 'name', 'description', 'voter_type',
            'max_nominees', 'is_active', 'requires_proof', 'proof_type',
            'image', 'existingImage',
        ]);
        $this->resetValidation();
    }

    public function render(): View
    {
        $this->authorize('viewAny', Category::class);

        $categories = Category::query()
            ->withCount(['nominees', 'votes'])
            ->orderBy('voter_type')
            ->orderBy('name')
            ->get();

        return view('livewire.admin.category-manager', [
            'categories' => $categories,
            'totalVotes' => \App\Models\Vote::count(),
            'participants' => \App\Models\Vote::distinct('user_id')->count('user_id'),
            'openCount' => $categories->where('is_active', true)->count(),
        ]);
    }
}
