<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Utilisateurs')]
class UserManager extends Component
{
    use WithPagination;

    public bool $showModal = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $email = '';
    public string $role = 'eleve';
    public ?string $class = null;
    public ?string $phone = null;
    public ?string $password = null;

    public string $search = '';
    public string $roleFilter = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'required_without:phone', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingId)],
            'phone' => ['nullable', 'required_without:email', 'string', 'max:50', Rule::unique('users', 'phone')->ignore($this->editingId)],
            'role' => 'required|in:admin,professeur,eleve',
            'class' => 'nullable|string|max:255',
            'password' => $this->editingId ? 'nullable|string|min:6' : 'required|string|min:6',
        ];
    }

    public function create(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $user = User::findOrFail($id);

        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->class = $user->class;
        $this->phone = $user->phone;
        $this->password = null;
        $this->showModal = true;
    }

    public function save(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $validated = $this->validate();

        $data = [
            'name' => $this->name,
            'email' => filled($this->email) ? $this->email : null,
            'role' => $this->role,
            'class' => $this->class,
            'phone' => filled($this->phone) ? $this->phone : null,
        ];

        if (filled($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->editingId) {
            $user = User::findOrFail($this->editingId);
            $user->update($data);
        } else {
            $user = User::create($data);
        }

        $user->syncRoles([$this->role]);

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('toast', message: 'Utilisateur enregistré.');
    }

    public function delete(int $id): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        if ($id === auth()->id()) {
            $this->dispatch('toast', message: 'Vous ne pouvez pas supprimer votre propre compte.');
            return;
        }

        User::whereKey($id)->delete();
        $this->dispatch('toast', message: 'Utilisateur supprimé.');
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'email', 'role', 'class', 'phone', 'password']);
        $this->resetValidation();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $users = User::query()
            ->when($this->search, fn ($q) => $q->where(function ($qq) {
                $qq->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('class', 'like', "%{$this->search}%");
            }))
            ->when($this->roleFilter, fn ($q) => $q->where('role', $this->roleFilter))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.user-manager', [
            'users' => $users,
        ]);
    }
}
