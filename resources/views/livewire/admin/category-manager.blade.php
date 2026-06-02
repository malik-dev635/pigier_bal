<div>
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-white">Catégories</h1>
            <p class="mt-1 text-sm text-muted">Créez les catégories et ouvrez ou fermez les votes.</p>
        </div>
        <button wire:click="create" class="btn-primary">Nouvelle catégorie</button>
    </div>

    <div class="table-wrap overflow-x-auto">
        <table class="data-table min-w-[760px]">
            <thead>
                <tr>
                    <th>Catégorie</th>
                    <th>Votants</th>
                    <th>Preuve</th>
                    <th class="text-center">Nominés</th>
                    <th class="text-center">Votes</th>
                    <th class="text-center">Statut</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 shrink-0 overflow-hidden rounded-lg border border-line bg-bg-surface">
                                    @if($category->image_url)
                                        <img src="{{ $category->image_url }}" alt="" class="h-full w-full object-cover">
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-white">{{ $category->name }}</p>
                                    @if($category->description)
                                        <p class="line-clamp-1 max-w-xs text-xs text-muted">{{ $category->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td><span class="badge-muted">{{ $category->voterTypeLabel() }}</span></td>
                        <td>
                            @if($category->requires_proof)
                                <span class="badge-muted">{{ ucfirst($category->proof_type) }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center text-offwhite">{{ $category->nominees_count }}</td>
                        <td class="text-center font-medium text-gold-light">{{ $category->votes_count }}</td>
                        <td class="text-center">
                            <button wire:click="toggle({{ $category->id }})"
                                    class="status {{ $category->is_active ? 'status-open' : 'status-closed' }} mx-auto"
                                    title="Cliquer pour {{ $category->is_active ? 'clôturer' : 'ouvrir' }}">
                                <span class="status-dot"></span>{{ $category->is_active ? 'Ouvert' : 'Clôturé' }}
                            </button>
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="edit({{ $category->id }})" class="btn-secondary btn-sm">Modifier</button>
                                <button wire:click="delete({{ $category->id }})"
                                        wire:confirm="Supprimer cette catégorie ainsi que ses nominés et votes ?"
                                        class="btn-danger btn-sm">Supprimer</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-12 text-center text-muted">Aucune catégorie pour l'instant.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modale --}}
    @if($showModal)
        <div class="modal-overlay" wire:key="cat-modal">
            <div class="modal" @click.outside="$wire.set('showModal', false)">
                <h2 class="text-lg font-semibold text-white">{{ $editingId ? 'Modifier la catégorie' : 'Nouvelle catégorie' }}</h2>

                <form wire:submit="save" class="mt-5 space-y-4">
                    <div>
                        <label class="field-label">Nom</label>
                        <input type="text" wire:model="name" class="input" placeholder="Ex : Meilleur Leadership">
                        @error('name') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="field-label">Description <span class="font-normal text-muted">(facultatif)</span></label>
                        <textarea wire:model="description" rows="2" class="textarea"></textarea>
                        @error('description') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="field-label">Affiche <span class="font-normal text-muted">(facultatif)</span></label>
                        <input type="file" wire:model="image" accept="image/*" class="input py-2">
                        <div wire:loading wire:target="image" class="field-hint">Chargement…</div>
                        @error('image') <p class="field-error">{{ $message }}</p> @enderror
                        <div class="mt-2 flex items-center gap-3">
                            @if($image)
                                <img src="{{ $image->temporaryUrl() }}" class="h-16 rounded-lg border border-line object-cover">
                                <span class="text-xs text-muted">Nouvelle affiche</span>
                            @elseif($existingImage)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($existingImage) }}" class="h-16 rounded-lg border border-line object-cover">
                                <span class="text-xs text-muted">Affiche actuelle (laissez vide pour conserver)</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="field-label">Qui peut voter</label>
                            <select wire:model="voter_type" class="select">
                                <option value="eleve">Élèves</option>
                                <option value="professeur">Professeurs</option>
                                <option value="both">Élèves et professeurs</option>
                            </select>
                        </div>
                        <div>
                            <label class="field-label">Nominés maximum</label>
                            <input type="number" min="1" wire:model="max_nominees" class="input">
                            @error('max_nominees') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <label class="flex items-center gap-2.5 text-sm text-offwhite">
                        <input type="checkbox" wire:model="is_active" class="checkbox">
                        Ouvrir le vote dès l'enregistrement
                    </label>

                    <label class="flex items-center gap-2.5 text-sm text-offwhite">
                        <input type="checkbox" wire:model.live="requires_proof" class="checkbox">
                        Exiger une preuve pour les nominés
                    </label>

                    @if($requires_proof)
                        <div>
                            <label class="field-label">Type de preuve</label>
                            <select wire:model="proof_type" class="select">
                                <option value="">Sélectionner…</option>
                                <option value="url">Lien (URL)</option>
                                <option value="file">Fichier</option>
                                <option value="both">Lien et fichier</option>
                            </select>
                            @error('proof_type') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="$set('showModal', false)" class="btn-secondary">Annuler</button>
                        <button type="submit" class="btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
