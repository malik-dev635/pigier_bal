<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Nominee;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.public')]
#[Title('Candidature')]
class CandidacyForm extends Component
{
    use WithFileUploads;

    public Category $category;

    public string $first_name = '';
    public string $last_name = '';
    public ?string $class = null;
    public ?string $description = null;
    public ?string $proof_url = null;
    public $photo = null;
    public $proofFile = null;
    public $proofFile2 = null;

    public bool $submitted = false;

    public function mount(string $token): void
    {
        $this->category = Category::where('candidacy_token', $token)->firstOrFail();
    }

    protected function rules(): array
    {
        // Validation de format uniquement ; la présence de la preuve est
        // vérifiée dans submit() (pour gérer 2 fichiers proprement).
        return [
            'first_name' => ($this->category->isEntity() ? 'nullable' : 'required').'|string|max:255',
            'last_name' => 'required|string|max:255',
            'class' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'photo' => 'required|image|max:8192',
            'proof_url' => 'nullable|url|max:1000',
            'proofFile' => 'nullable|file|max:20480|mimes:pdf,zip,png,jpg,jpeg,webp,doc,docx',
            'proofFile2' => 'nullable|file|max:20480|mimes:pdf,zip,png,jpg,jpeg,webp,doc,docx',
        ];
    }

    protected function messages(): array
    {
        return [
            'photo.required' => 'Veuillez ajouter votre photo (portrait).',
            'photo.image' => 'Le fichier doit être une image (JPG, PNG…).',
        ];
    }

    public function submit(): void
    {
        // Candidatures fermées : on bloque côté serveur.
        abort_unless($this->category->candidacy_open, 403, 'Les candidatures sont fermées pour cette récompense.');

        $this->validate();

        // Vérifie la présence de la preuve requise (lien et/ou fichier(s)).
        if ($this->category->requires_proof) {
            $hasUrl = filled($this->proof_url);
            $hasFile = $this->proofFile || $this->proofFile2;

            $ok = match ($this->category->proof_type) {
                'url' => $hasUrl,
                'file' => $hasFile,
                'both' => $hasUrl || $hasFile,
                default => true,
            };

            if (! $ok) {
                $this->addError('proof', match ($this->category->proof_type) {
                    'url' => 'Veuillez fournir le lien de votre preuve.',
                    'file' => 'Veuillez joindre au moins un fichier de preuve.',
                    default => 'Veuillez fournir votre preuve (un lien et/ou un fichier).',
                });
                return;
            }
        }

        $data = [
            'category_id' => $this->category->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'class' => $this->class,
            'description' => $this->description,
            'proof_url' => $this->proof_url,
            'is_active' => true,
            'is_approved' => false, // en attente de validation par l'admin
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

        Nominee::create($data);

        $this->reset(['first_name', 'last_name', 'class', 'description', 'proof_url', 'photo', 'proofFile', 'proofFile2']);
        $this->submitted = true;
    }

    public function render(): View
    {
        return view('livewire.public.candidacy-form');
    }
}
