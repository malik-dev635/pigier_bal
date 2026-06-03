<div>
    <a href="{{ route('admin.home') }}" class="mb-4 inline-flex items-center gap-1 text-sm text-muted transition-colors hover:text-offwhite">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        Administration
    </a>

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl">Participants</h1>
            <p class="mt-1 text-sm text-muted">Gérez les comptes des élèves, professeurs et administrateurs.</p>
        </div>
        <button wire:click="create" class="btn-primary">Nouveau compte</button>
    </div>

    <div class="mb-5 flex flex-col gap-3 sm:flex-row">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher un nom, email ou classe…" class="input sm:max-w-xs">
        <select wire:model.live="roleFilter" class="select sm:max-w-[200px]">
            <option value="">Tous les rôles</option>
            <option value="admin">Administrateurs</option>
            <option value="professeur">Professeurs</option>
            <option value="eleve">Élèves</option>
        </select>
    </div>

    <div class="table-wrap overflow-x-auto">
        <table class="data-table min-w-[640px]">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Identifiant</th>
                    <th>Rôle</th>
                    <th>Classe</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr wire:key="user-{{ $user->id }}">
                        <td class="font-medium text-white">{{ $user->name }}</td>
                        <td class="text-muted">{{ $user->email ?? $user->phone ?? '—' }}</td>
                        <td>
                            <span class="{{ $user->role === 'admin' ? 'badge-gold' : 'badge-muted' }}">
                                {{ ['admin' => 'Administrateur', 'professeur' => 'Professeur', 'eleve' => 'Élève'][$user->role] ?? $user->role }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $user->class ?? '—' }}</td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="edit({{ $user->id }})" class="btn-secondary btn-sm">Modifier</button>
                                <button wire:click="delete({{ $user->id }})" wire:confirm="Supprimer ce compte ?" class="btn-danger btn-sm">Supprimer</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-12 text-center text-muted">Aucun participant trouvé.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>

    {{-- Modale --}}
    @if($showModal)
        <div class="modal-overlay" wire:key="user-modal">
            <div class="modal">
                <h2 class="text-lg font-semibold text-white">{{ $editingId ? 'Modifier le compte' : 'Nouveau compte' }}</h2>

                <form wire:submit="save" class="mt-5 space-y-4">
                    <div>
                        <label class="field-label">Nom complet</label>
                        <input type="text" wire:model="name" class="input">
                        @error('name') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="field-label">Email</label>
                            <input type="email" wire:model="email" class="input" placeholder="nom@exemple.com">
                            @error('email') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="field-label">Téléphone</label>
                            <input type="text" wire:model="phone" class="input" placeholder="07 00 00 00 00">
                            @error('phone') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <p class="field-hint -mt-2">Renseignez au moins un email ou un téléphone.</p>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="field-label">Rôle</label>
                            <select wire:model="role" class="select">
                                <option value="eleve">Élève</option>
                                <option value="professeur">Professeur</option>
                                <option value="admin">Administrateur</option>
                            </select>
                            @error('role') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="field-label">Classe <span class="font-normal text-muted">(facultatif)</span></label>
                            <input type="text" wire:model="class" class="input">
                        </div>
                    </div>
                    <div>
                        <label class="field-label">Mot de passe {{ $editingId ? '— laisser vide pour conserver' : '' }}</label>
                        <input type="text" wire:model="password" class="input" placeholder="6 caractères minimum">
                        @error('password') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="$set('showModal', false)" class="btn-secondary">Annuler</button>
                        <button type="submit" class="btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
