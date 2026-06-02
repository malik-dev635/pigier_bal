<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-title text-3xl text-gradient-gold tracking-wide">UTILISATEURS</h1>
            <p class="text-muted mt-1">Créez et gérez les comptes élèves, professeurs et admins.</p>
        </div>
        <button wire:click="create" class="btn-gold">+ Nouveau compte</button>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher (nom, e-mail, classe)…" class="input-gold sm:max-w-xs">
        <select wire:model.live="roleFilter" class="select-gold sm:max-w-[180px]">
            <option value="">Tous les rôles</option>
            <option value="admin">Admins</option>
            <option value="professeur">Professeurs</option>
            <option value="eleve">Élèves</option>
        </select>
    </div>

    <div class="gold-card !p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-muted uppercase text-xs tracking-wider border-b" style="border-color: var(--gold-dark)">
                        <th class="px-4 py-3">Nom</th>
                        <th class="px-4 py-3">E-mail</th>
                        <th class="px-4 py-3">Rôle</th>
                        <th class="px-4 py-3">Classe</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b last:border-0 hover:bg-bg-surface/40" style="border-color: rgba(122,92,24,0.4)">
                            <td class="px-4 py-3 text-offwhite font-medium">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-muted">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <span class="{{ $user->role === 'admin' ? 'badge-success' : 'badge-neutral' }}">{{ ucfirst($user->role) }}</span>
                            </td>
                            <td class="px-4 py-3 text-muted">{{ $user->class ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="edit({{ $user->id }})" class="btn-ghost !px-3 !py-1.5 text-xs">Modifier</button>
                                    <button wire:click="delete({{ $user->id }})" wire:confirm="Supprimer ce compte ?" class="btn-danger !px-3 !py-1.5 text-xs">Suppr.</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-12 text-center text-muted">Aucun utilisateur trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>

    {{-- MODALE --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70" wire:key="user-modal">
            <div class="gold-card w-full max-w-lg max-h-[90vh] overflow-y-auto animate-fade-slide-up">
                <h2 class="font-title text-xl text-offwhite mb-5">{{ $editingId ? 'Modifier le compte' : 'Nouveau compte' }}</h2>

                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="label-gold">Nom complet</label>
                        <input type="text" wire:model="name" class="input-gold">
                        @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label-gold">E-mail <span class="text-muted text-xs">(ou téléphone)</span></label>
                        <input type="email" wire:model="email" class="input-gold">
                        @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label-gold">Rôle</label>
                            <select wire:model="role" class="select-gold">
                                <option value="eleve">Élève</option>
                                <option value="professeur">Professeur</option>
                                <option value="admin">Admin</option>
                            </select>
                            @error('role') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label-gold">Classe</label>
                            <input type="text" wire:model="class" class="input-gold">
                        </div>
                    </div>
                    <div>
                        <label class="label-gold">Téléphone</label>
                        <input type="text" wire:model="phone" class="input-gold">
                        @error('phone') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label-gold">Mot de passe {{ $editingId ? '(laisser vide pour conserver)' : '' }}</label>
                        <input type="text" wire:model="password" class="input-gold" placeholder="••••••••">
                        @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-3">
                        <button type="button" wire:click="$set('showModal', false)" class="btn-ghost">Annuler</button>
                        <button type="submit" class="btn-gold">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
