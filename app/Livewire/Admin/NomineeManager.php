<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Nominee;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
#[Title('Nominés')]
class NomineeManager extends Component
{
    use WithFileUploads;

    /** Catégorie sélectionnée pour filtrer/gérer les nominés. */
    public ?int $categoryId = null;

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $first_name = '';
    public string $last_name = '';
    public ?string $class = null;
    public ?string $description = null;
    public ?string $proof_url = null;
    public bool $is_active = true;
    public bool $is_votable = true;

    /** Uploads temporaires. */
    public $photo = null;
    public $proofFile = null;

    /** Chemins existants (mode édition). */
    public ?string $existingPhoto = null;
    public ?string $existingProofFile = null;

    public function mount(): void
    {
        $this->categoryId = request()->integer('category')
            ?: Category::query()->orderBy('name')->value('id');
    }

    public function getCategoryProperty(): ?Category
    {
        return $this->categoryId ? Category::find($this->categoryId) : null;
    }

    protected function rules(): array
    {
        $category = $this->category;

        $isEntity = $category && $category->isEntity();

        $rules = [
            'categoryId' => 'required|exists:categories,id',
            // Pour une entité (association/club), seul « Nom » (last_name) est requis.
            'first_name' => ($isEntity ? 'nullable' : 'required').'|string|max:255',
            'last_name' => 'required|string|max:255',
            'class' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
            'photo' => 'nullable|image|max:8192',
            'proofFile' => 'nullable|file|max:20480|mimes:pdf,zip,png,jpg,jpeg,webp,doc,docx',
            'proof_url' => 'nullable|url|max:1000',
        ];

        // Preuve obligatoire selon la configuration de la catégorie.
        if ($category && $category->requires_proof) {
            $hasFile = $this->proofFile || $this->existingProofFile;
            $hasUrl = filled($this->proof_url);

            if ($category->proof_type === 'url') {
                $rules['proof_url'] = 'required|url|max:1000';
            } elseif ($category->proof_type === 'file' && ! $hasFile) {
                $rules['proofFile'] = 'required|file|max:20480|mimes:pdf,zip,png,jpg,jpeg,webp,doc,docx';
            } elseif ($category->proof_type === 'both' && ! $hasFile && ! $hasUrl) {
                // Au moins une des deux preuves.
                $rules['proof_url'] = 'required_without:proofFile|nullable|url|max:1000';
                $rules['proofFile'] = 'required_without:proof_url|nullable|file|max:20480';
            }
        }

        return $rules;
    }

    public function create(): void
    {
        $this->authorize('create', Nominee::class);
        abort_unless($this->categoryId, 422, 'Sélectionnez d\'abord une récompense.');
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $nominee = Nominee::findOrFail($id);
        $this->authorize('update', $nominee);

        $this->editingId = $nominee->id;
        $this->categoryId = $nominee->category_id;
        $this->first_name = $nominee->first_name;
        $this->last_name = $nominee->last_name;
        $this->class = $nominee->class;
        $this->description = $nominee->description;
        $this->proof_url = $nominee->proof_url;
        $this->is_active = $nominee->is_active;
        $this->is_votable = $nominee->is_votable;
        $this->existingPhoto = $nominee->photo;
        $this->existingProofFile = $nominee->proof_file;
        $this->photo = null;
        $this->proofFile = null;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'category_id' => $this->categoryId,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'class' => $this->class,
            'description' => $this->description,
            'proof_url' => $this->proof_url,
            'is_active' => $this->is_active,
            'is_votable' => $this->is_votable,
        ];

        if ($this->photo) {
            $data['photo'] = $this->photo->store('nominees/photos', 'public');
        }

        if ($this->proofFile) {
            $data['proof_file'] = $this->proofFile->store('nominees/proofs', 'public');
        }

        if ($this->editingId) {
            $nominee = Nominee::findOrFail($this->editingId);
            $this->authorize('update', $nominee);
            $nominee->update($data);
            $message = 'Nominé mis à jour.';
        } else {
            $this->authorize('create', Nominee::class);
            Nominee::create($data);
            $message = 'Nominé ajouté.';
        }

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('toast', message: $message);
    }

    public function approve(int $id): void
    {
        $nominee = Nominee::findOrFail($id);
        $this->authorize('update', $nominee);
        $nominee->update(['is_approved' => true, 'is_active' => true]);

        $this->dispatch('toast', message: 'Candidature approuvée — visible au vote.');
    }

    public function delete(int $id): void
    {
        $nominee = Nominee::findOrFail($id);
        $this->authorize('delete', $nominee);
        $nominee->delete();

        $this->dispatch('toast', message: 'Nominé supprimé.');
    }

    public function resetForm(): void
    {
        $this->reset([
            'editingId', 'first_name', 'last_name', 'class', 'description',
            'proof_url', 'is_active', 'is_votable', 'photo', 'proofFile',
            'existingPhoto', 'existingProofFile',
        ]);
        $this->resetValidation();
    }

    public function render(): View
    {
        $this->authorize('viewAny', Nominee::class);

        $categories = Category::query()->orderBy('name')->get();

        $nominees = Nominee::query()
            ->when($this->categoryId, fn ($q) => $q->where('category_id', $this->categoryId))
            ->withCount('votes')
            ->orderBy('is_approved') // candidatures en attente d'abord
            ->orderBy('last_name')
            ->get();

        return view('livewire.admin.nominee-manager', [
            'categories' => $categories,
            'nominees' => $nominees,
        ]);
    }
}
