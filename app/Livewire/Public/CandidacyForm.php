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

    public bool $submitted = false;

    public function mount(string $token): void
    {
        $this->category = Category::where('candidacy_token', $token)->firstOrFail();
    }

    protected function rules(): array
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'class' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'photo' => 'nullable|image|max:4096',
            'proof_url' => 'nullable|url|max:1000',
            'proofFile' => 'nullable|file|max:20480|mimes:pdf,zip,png,jpg,jpeg,webp,doc,docx',
        ];

        if ($this->category->requires_proof) {
            if ($this->category->proof_type === 'url') {
                $rules['proof_url'] = 'required|url|max:1000';
            } elseif ($this->category->proof_type === 'file') {
                $rules['proofFile'] = 'required|file|max:20480|mimes:pdf,zip,png,jpg,jpeg,webp,doc,docx';
            } else { // both : au moins une preuve
                $rules['proof_url'] = 'required_without:proofFile|nullable|url|max:1000';
                $rules['proofFile'] = 'required_without:proof_url|nullable|file|max:20480';
            }
        }

        return $rules;
    }

    public function submit(): void
    {
        // Candidatures fermées : on bloque côté serveur.
        abort_unless($this->category->candidacy_open, 403, 'Les candidatures sont fermées pour cette récompense.');

        $this->validate();

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

        Nominee::create($data);

        $this->reset(['first_name', 'last_name', 'class', 'description', 'proof_url', 'photo', 'proofFile']);
        $this->submitted = true;
    }

    public function render(): View
    {
        return view('livewire.public.candidacy-form');
    }
}
