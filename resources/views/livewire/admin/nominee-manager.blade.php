<div>
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-white">Nominés</h1>
            <p class="mt-1 text-sm text-muted">Ajoutez et gérez les nominés de chaque catégorie.</p>
        </div>
        <button wire:click="create" class="btn-primary" @disabled(! $categoryId)>Ajouter un nominé</button>
    </div>

    <div class="card mb-6 p-4">
        <label class="field-label">Catégorie</label>
        <select wire:model.live="categoryId" class="select max-w-md">
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }} — {{ $cat->voterTypeLabel() }}</option>
            @endforeach
        </select>
        @if($this->category && $this->category->requires_proof)
            <p class="field-hint">Preuve obligatoire pour cette catégorie — type : <span class="text-offwhite">{{ ucfirst($this->category->proof_type) }}</span>.</p>
        @endif
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($nominees as $nominee)
            <div class="card flex flex-col p-5">
                <div class="mb-4 flex aspect-square items-center justify-center overflow-hidden rounded-lg border border-line bg-bg-surface">
                    @if($nominee->photo_url)
                        <img src="{{ $nominee->photo_url }}" alt="" class="h-full w-full object-cover">
                    @else
                        <span class="text-2xl font-semibold text-muted">{{ strtoupper(substr($nominee->first_name,0,1).substr($nominee->last_name,0,1)) }}</span>
                    @endif
                </div>
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <h3 class="truncate font-semibold text-white">{{ $nominee->full_name }}</h3>
                        @if($nominee->class)<p class="text-sm text-muted">{{ $nominee->class }}</p>@endif
                    </div>
                    <span class="badge-muted shrink-0">{{ $nominee->votes_count }} vote{{ $nominee->votes_count > 1 ? 's' : '' }}</span>
                </div>

                <div class="mt-3 flex flex-wrap gap-2">
                    @unless($nominee->is_active)<span class="badge-muted">Inactif</span>@endunless
                    @if($nominee->proof_url)<span class="badge-muted">Lien</span>@endif
                    @if($nominee->proof_file)<span class="badge-muted">Fichier</span>@endif
                </div>

                <div class="mt-4 flex gap-2 border-t border-line pt-4">
                    <button wire:click="edit({{ $nominee->id }})" class="btn-secondary btn-sm flex-1">Modifier</button>
                    <button wire:click="delete({{ $nominee->id }})" wire:confirm="Supprimer ce nominé ?" class="btn-danger btn-sm">Supprimer</button>
                </div>
            </div>
        @empty
            <div class="card p-10 text-center sm:col-span-2 lg:col-span-3">
                <p class="text-sm text-muted">Aucun nominé dans cette catégorie.</p>
            </div>
        @endforelse
    </div>

    {{-- Modale --}}
    @if($showModal)
        <div class="modal-overlay" wire:key="nom-modal">
            <div class="modal">
                <h2 class="text-lg font-semibold text-white">{{ $editingId ? 'Modifier le nominé' : 'Nouveau nominé' }}</h2>

                <form wire:submit="save" class="mt-5 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="field-label">Prénom</label>
                            <input type="text" wire:model="first_name" class="input">
                            @error('first_name') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="field-label">Nom</label>
                            <input type="text" wire:model="last_name" class="input">
                            @error('last_name') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="field-label">Classe <span class="font-normal text-muted">(facultatif)</span></label>
                        <input type="text" wire:model="class" class="input" placeholder="Ex : BTS 2 Marketing">
                    </div>

                    <div>
                        <label class="field-label">Description <span class="font-normal text-muted">(facultatif)</span></label>
                        <textarea wire:model="description" rows="2" class="textarea"></textarea>
                    </div>

                    <div>
                        <label class="field-label">Photo <span class="font-normal text-muted">(facultatif)</span></label>
                        @if($existingPhoto)
                            <p class="field-hint mb-1">Photo actuelle conservée si aucun nouveau fichier.</p>
                        @endif
                        <input type="file" wire:model="photo" accept="image/*" class="input py-2">
                        <div wire:loading wire:target="photo" class="field-hint">Chargement…</div>
                        @error('photo') <p class="field-error">{{ $message }}</p> @enderror
                        @if($photo)
                            <img src="{{ $photo->temporaryUrl() }}" class="mt-2 h-20 rounded-lg border border-line object-cover">
                        @endif
                    </div>

                    @if($this->category && $this->category->needsUrl())
                        <div>
                            <label class="field-label">Lien de preuve</label>
                            <input type="url" wire:model="proof_url" class="input" placeholder="https://…">
                            @error('proof_url') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    @if($this->category && $this->category->needsFile())
                        <div>
                            <label class="field-label">Fichier de preuve <span class="font-normal text-muted">(PDF, image, ZIP…)</span></label>
                            @if($existingProofFile)
                                <p class="field-hint mb-1">Fichier déjà présent — laissez vide pour le conserver.</p>
                            @endif
                            <input type="file" wire:model="proofFile" class="input py-2">
                            <div wire:loading wire:target="proofFile" class="field-hint">Chargement…</div>
                            @error('proofFile') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <label class="flex items-center gap-2.5 text-sm text-offwhite">
                        <input type="checkbox" wire:model="is_active" class="checkbox">
                        Nominé actif (visible au vote)
                    </label>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="$set('showModal', false)" class="btn-secondary">Annuler</button>
                        <button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="save,photo,proofFile">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
