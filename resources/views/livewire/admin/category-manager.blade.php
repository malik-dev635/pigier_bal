<div wire:poll.15s>
    <a href="{{ route('admin.home') }}" class="mb-4 inline-flex items-center gap-1 text-sm text-muted transition-colors hover:text-offwhite">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        Administration
    </a>

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl">Récompenses</h1>
            <p class="mt-1 text-sm text-muted">
                {{ $totalVotes }} vote{{ $totalVotes > 1 ? 's' : '' }} · {{ $participants }} participant{{ $participants > 1 ? 's' : '' }} · {{ $openCount }} récompense{{ $openCount > 1 ? 's' : '' }} ouverte{{ $openCount > 1 ? 's' : '' }}
            </p>
        </div>
        <button wire:click="create" class="btn-primary">Nouvelle récompense</button>
    </div>

    @if($categories->isEmpty())
        <div class="card p-10 text-center">
            <p class="text-sm text-muted">Aucune récompense. Créez-en une pour commencer.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($categories as $category)
                <div class="card p-4 sm:p-5">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        {{-- Infos --}}
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="h-12 w-12 shrink-0 overflow-hidden rounded-lg border border-line bg-bg-surface">
                                @if($category->image_url)
                                    <img src="{{ $category->image_url }}" alt="" class="h-full w-full object-cover">
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="truncate font-medium text-white">{{ $category->name }}</p>
                                <p class="mt-0.5 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-muted">
                                    <span>{{ $category->voterTypeLabel() }}</span>
                                    <span aria-hidden="true">·</span>
                                    <span>{{ $category->nominees_count }} nominé{{ $category->nominees_count > 1 ? 's' : '' }}</span>
                                    <span aria-hidden="true">·</span>
                                    <span class="font-medium text-gold-light">{{ $category->votes_count }} vote{{ $category->votes_count > 1 ? 's' : '' }}</span>
                                    @if($category->requires_proof)
                                        <span aria-hidden="true">·</span>
                                        <span>preuve {{ $category->proof_type }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                            <button wire:click="toggle({{ $category->id }})"
                                    class="btn-secondary btn-sm">
                                <span class="status {{ $category->is_active ? 'status-open' : 'status-closed' }}">
                                    <span class="status-dot"></span>{{ $category->is_active ? 'Ouvert' : 'Clôturé' }}
                                </span>
                            </button>
                            <a href="{{ route('admin.nominees', ['category' => $category->id]) }}" class="btn-secondary btn-sm">Nominés</a>
                            <button wire:click="edit({{ $category->id }})" class="btn-secondary btn-sm">Modifier</button>
                            <button wire:click="delete({{ $category->id }})"
                                    wire:confirm="Supprimer cette récompense ainsi que ses nominés et votes ?"
                                    class="btn-danger btn-sm">Supprimer</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Modale --}}
    @if($showModal)
        <div class="modal-overlay" wire:key="cat-modal">
            <div class="modal" @click.outside="$wire.set('showModal', false)">
                <h2 class="text-lg font-semibold text-white">{{ $editingId ? 'Modifier la récompense' : 'Nouvelle récompense' }}</h2>

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
