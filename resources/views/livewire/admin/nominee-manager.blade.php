<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-title text-3xl text-gradient-gold tracking-wide">NOMINÉS</h1>
            <p class="text-muted mt-1">Gérez les nominés par catégorie (photo, classe, preuves).</p>
        </div>
        <button wire:click="create" class="btn-gold" @disabled(! $categoryId)>+ Ajouter un nominé</button>
    </div>

    <div class="gold-card mb-6 !py-4">
        <label class="label-gold">Catégorie</label>
        <select wire:model.live="categoryId" class="select-gold max-w-md">
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->voterTypeLabel() }})</option>
            @endforeach
        </select>
        @if($this->category && $this->category->requires_proof)
            <p class="text-xs text-gold-light mt-2">⚠ Preuve obligatoire pour cette catégorie — type : <strong>{{ ucfirst($this->category->proof_type) }}</strong>.</p>
        @endif
    </div>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($nominees as $nominee)
            <div class="gold-card is-hoverable flex flex-col">
                <div class="aspect-[4/3] rounded-md overflow-hidden mb-4 bg-bg-surface flex items-center justify-center border" style="border-color: var(--gold-dark)">
                    @if($nominee->photo_url)
                        <img src="{{ $nominee->photo_url }}" alt="" class="w-full h-full object-cover">
                    @else
                        <span class="font-title text-3xl text-gold-dark">{{ strtoupper(substr($nominee->first_name,0,1).substr($nominee->last_name,0,1)) }}</span>
                    @endif
                </div>
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <h3 class="font-title text-offwhite truncate">{{ $nominee->full_name }}</h3>
                        @if($nominee->class)<p class="text-xs text-muted">{{ $nominee->class }}</p>@endif
                    </div>
                    <span class="badge-neutral whitespace-nowrap">{{ $nominee->votes_count }} ★</span>
                </div>

                <div class="flex flex-wrap gap-2 mt-3">
                    @unless($nominee->is_active)<span class="badge-closed">Inactif</span>@endunless
                    @if($nominee->proof_url)<span class="badge-neutral">URL</span>@endif
                    @if($nominee->proof_file)<span class="badge-neutral">Fichier</span>@endif
                </div>

                <div class="flex gap-2 mt-4 pt-4 border-t" style="border-color: rgba(122,92,24,0.4)">
                    <button wire:click="edit({{ $nominee->id }})" class="btn-ghost flex-1 !py-2 text-xs">Modifier</button>
                    <button wire:click="delete({{ $nominee->id }})" wire:confirm="Supprimer ce nominé ?" class="btn-danger !px-3 !py-2 text-xs">Suppr.</button>
                </div>
            </div>
        @empty
            <div class="gold-card sm:col-span-2 lg:col-span-3 text-center py-12">
                <p class="text-muted">Aucun nominé dans cette catégorie.</p>
            </div>
        @endforelse
    </div>

    {{-- MODALE --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70" wire:key="nom-modal">
            <div class="gold-card w-full max-w-lg max-h-[90vh] overflow-y-auto animate-fade-slide-up">
                <h2 class="font-title text-xl text-offwhite mb-5">{{ $editingId ? 'Modifier le nominé' : 'Nouveau nominé' }}</h2>

                <form wire:submit="save" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label-gold">Prénom</label>
                            <input type="text" wire:model="first_name" class="input-gold">
                            @error('first_name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label-gold">Nom</label>
                            <input type="text" wire:model="last_name" class="input-gold">
                            @error('last_name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="label-gold">Classe</label>
                        <input type="text" wire:model="class" class="input-gold" placeholder="Ex : CDM 1 A">
                    </div>

                    <div>
                        <label class="label-gold">Description</label>
                        <textarea wire:model="description" rows="2" class="textarea-gold"></textarea>
                    </div>

                    <div>
                        <label class="label-gold">Photo</label>
                        @if($existingPhoto)
                            <p class="text-xs text-muted mb-1">Photo actuelle conservée si aucun nouveau fichier.</p>
                        @endif
                        <input type="file" wire:model="photo" accept="image/*" class="input-gold !py-2 text-sm">
                        <div wire:loading wire:target="photo" class="text-xs text-gold-light mt-1">Téléversement…</div>
                        @error('photo') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        @if($photo)
                            <img src="{{ $photo->temporaryUrl() }}" class="mt-2 h-24 rounded-md object-cover border" style="border-color: var(--gold-dark)">
                        @endif
                    </div>

                    @if($this->category && $this->category->needsUrl())
                        <div>
                            <label class="label-gold">Lien de preuve (URL)</label>
                            <input type="url" wire:model="proof_url" class="input-gold" placeholder="https://…">
                            @error('proof_url') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    @if($this->category && $this->category->needsFile())
                        <div>
                            <label class="label-gold">Fichier de preuve (PDF, ZIP, image…)</label>
                            @if($existingProofFile)
                                <p class="text-xs text-muted mb-1">Fichier déjà présent — laissez vide pour le conserver.</p>
                            @endif
                            <input type="file" wire:model="proofFile" class="input-gold !py-2 text-sm">
                            <div wire:loading wire:target="proofFile" class="text-xs text-gold-light mt-1">Téléversement…</div>
                            @error('proofFile') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <label class="flex items-center gap-3 text-sm text-offwhite cursor-pointer">
                        <input type="checkbox" wire:model="is_active" class="rounded border-gold-dark bg-bg-surface text-gold-main focus:ring-0">
                        Nominé actif
                    </label>

                    <div class="flex justify-end gap-3 pt-3">
                        <button type="button" wire:click="$set('showModal', false)" class="btn-ghost">Annuler</button>
                        <button type="submit" class="btn-gold" wire:loading.attr="disabled" wire:target="save,photo,proofFile">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
