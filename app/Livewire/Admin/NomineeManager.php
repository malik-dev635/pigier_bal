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
    public $proofFile2 = null;

    /** Chemins existants (mode édition). */
    public ?string $existingPhoto = null;
    public ?string $existingProofFile = null;
    public ?string $existingProofFile2 = null;

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

        // Validation de format ; la présence de la preuve est vérifiée dans save().
        return [
            'categoryId' => 'required|exists:categories,id',
            // Pour une entité (association/club), seul « Nom » (last_name) est requis.
            'first_name' => ($isEntity ? 'nullable' : 'required').'|string|max:255',
            'last_name' => 'required|string|max:255',
            'class' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
            'photo' => 'nullable|image|max:8192',
            'proofFile' => 'nullable|file|max:20480|mimes:pdf,zip,png,jpg,jpeg,webp,doc,docx',
            'proofFile2' => 'nullable|file|max:20480|mimes:pdf,zip,png,jpg,jpeg,webp,doc,docx',
            'proof_url' => 'nullable|url|max:1000',
        ];
    }

    protected function proofIsSatisfied(Category $category): bool
    {
        if (! $category->requires_proof) {
            return true;
        }

        $hasUrl = filled($this->proof_url);
        $hasFile = $this->proofFile || $this->proofFile2 || $this->existingProofFile || $this->existingProofFile2;

        return match ($category->proof_type) {
            'url' => $hasUrl,
            'file' => $hasFile,
            'both' => $hasUrl || $hasFile,
            default => true,
        };
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
        $this->existingProofFile2 = $nominee->proof_file_2;
        $this->photo = null;
        $this->proofFile = null;
        $this->proofFile2 = null;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        // Présence de la preuve requise (lien et/ou fichier(s)).
        if ($this->category && ! $this->proofIsSatisfied($this->category)) {
            $this->addError('proof', match ($this->category->proof_type) {
                'url' => 'Veuillez fournir le lien de preuve.',
                'file' => 'Veuillez joindre au moins un fichier de preuve.',
                default => 'Veuillez fournir une preuve (lien et/ou fichier).',
            });
            return;
        }

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

        if ($this->proofFile2) {
            $data['proof_file_2'] = $this->proofFile2->store('nominees/proofs', 'public');
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
            'proof_url', 'is_active', 'is_votable', 'photo', 'proofFile', 'proofFile2',
            'existingPhoto', 'existingProofFile', 'existingProofFile2',
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
