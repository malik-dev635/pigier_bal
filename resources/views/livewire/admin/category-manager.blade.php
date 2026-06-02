<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-title text-3xl text-gradient-gold tracking-wide">CATÉGORIES</h1>
            <p class="text-muted mt-1">Créez, configurez et ouvrez/fermez les votes.</p>
        </div>
        <button wire:click="create" class="btn-gold">+ Nouvelle catégorie</button>
    </div>

    <div class="gold-card !p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-muted uppercase text-xs tracking-wider border-b" style="border-color: var(--gold-dark)">
                        <th class="px-4 py-3">Catégorie</th>
                        <th class="px-4 py-3">Votants</th>
                        <th class="px-4 py-3">Preuve</th>
                        <th class="px-4 py-3 text-center">Nominés</th>
                        <th class="px-4 py-3 text-center">Votes</th>
                        <th class="px-4 py-3 text-center">Statut</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr class="border-b last:border-0 hover:bg-bg-surface/40 transition-colors" style="border-color: rgba(122,92,24,0.4)">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-md overflow-hidden bg-bg-surface flex items-center justify-center border shrink-0" style="border-color: var(--gold-dark)">
                                        @if($category->image_url)
                                            <img src="{{ $category->image_url }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-gold-dark">🏆</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-offwhite font-medium">{{ $category->name }}</p>
                                        @if($category->description)
                                            <p class="text-xs text-muted line-clamp-1 max-w-xs">{{ $category->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3"><span class="badge-neutral">{{ $category->voterTypeLabel() }}</span></td>
                            <td class="px-4 py-3">
                                @if($category->requires_proof)
                                    <span class="badge-neutral">{{ ucfirst($category->proof_type) }}</span>
                                @else
                                    <span class="text-muted text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center text-offwhite">{{ $category->nominees_count }}</td>
                            <td class="px-4 py-3 text-center text-gold-light font-semibold">{{ $category->votes_count }}</td>
                            <td class="px-4 py-3 text-center">
                                <button wire:click="toggle({{ $category->id }})"
                                        class="{{ $category->is_active ? 'badge-open' : 'badge-closed' }}">
                                    {{ $category->is_active ? '● Ouvert' : '● Clôturé' }}
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="edit({{ $category->id }})" class="btn-ghost !px-3 !py-1.5 text-xs">Modifier</button>
                                    <button wire:click="delete({{ $category->id }})"
                                            wire:confirm="Supprimer cette catégorie et tous ses nominés/votes ?"
                                            class="btn-danger !px-3 !py-1.5 text-xs">Suppr.</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-12 text-center text-muted">Aucune catégorie. Créez-en une.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODALE --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70" wire:key="cat-modal">
            <div class="gold-card w-full max-w-lg max-h-[90vh] overflow-y-auto animate-fade-slide-up" @click.outside="$wire.set('showModal', false)">
                <h2 class="font-title text-xl text-offwhite mb-5">{{ $editingId ? 'Modifier la catégorie' : 'Nouvelle catégorie' }}</h2>

                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="label-gold">Nom</label>
                        <input type="text" wire:model="name" class="input-gold">
                        @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="label-gold">Description</label>
                        <textarea wire:model="description" rows="2" class="textarea-gold"></textarea>
                        @error('description') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="label-gold">Affiche de la catégorie</label>
                        <input type="file" wire:model="image" accept="image/*" class="input-gold !py-2 text-sm">
                        <div wire:loading wire:target="image" class="text-xs text-gold-light mt-1">Téléversement…</div>
                        @error('image') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        <div class="mt-2 flex items-center gap-3">
                            @if($image)
                                <img src="{{ $image->temporaryUrl() }}" class="h-20 rounded-md object-cover border" style="border-color: var(--gold-dark)">
                                <span class="text-xs text-muted">Nouvelle affiche</span>
                            @elseif($existingImage)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($existingImage) }}" class="h-20 rounded-md object-cover border" style="border-color: var(--gold-dark)">
                                <span class="text-xs text-muted">Affiche actuelle (laissez vide pour conserver)</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label-gold">Type de votant</label>
                            <select wire:model="voter_type" class="select-gold">
                                <option value="eleve">Élèves</option>
                                <option value="professeur">Professeurs</option>
                                <option value="both">Les deux</option>
                            </select>
                        </div>
                        <div>
                            <label class="label-gold">Nominés max.</label>
                            <input type="number" min="1" wire:model="max_nominees" class="input-gold">
                            @error('max_nominees') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <label class="flex items-center gap-3 text-sm text-offwhite cursor-pointer">
                        <input type="checkbox" wire:model="is_active" class="rounded border-gold-dark bg-bg-surface text-gold-main focus:ring-0">
                        Vote ouvert (is_active)
                    </label>

                    <label class="flex items-center gap-3 text-sm text-offwhite cursor-pointer">
                        <input type="checkbox" wire:model.live="requires_proof" class="rounded border-gold-dark bg-bg-surface text-gold-main focus:ring-0">
                        Preuve requise
                    </label>

                    @if($requires_proof)
                        <div>
                            <label class="label-gold">Type de preuve</label>
                            <select wire:model="proof_type" class="select-gold">
                                <option value="">— Sélectionner —</option>
                                <option value="url">Lien (URL)</option>
                                <option value="file">Fichier</option>
                                <option value="both">Les deux</option>
                            </select>
                            @error('proof_type') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <div class="flex justify-end gap-3 pt-3">
                        <button type="button" wire:click="$set('showModal', false)" class="btn-ghost">Annuler</button>
                        <button type="submit" class="btn-gold">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
