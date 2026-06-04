<div>
    @if($submitted)
        {{-- Confirmation --}}
        <div class="card p-6 text-center">
            <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center border border-gold-main text-gold-light">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            </div>
            <h2 class="text-lg font-semibold text-white">Candidature envoyée</h2>
            <p class="mt-2 text-sm text-muted">
                Merci&nbsp;! Votre candidature pour « {{ $category->name }} » a bien été reçue.
                Elle sera examinée par l'organisation avant d'apparaître au vote.
            </p>
            <button wire:click="$set('submitted', false)" class="btn-secondary mt-5">Soumettre une autre candidature</button>
        </div>

    @elseif(! $category->candidacy_open)
        {{-- Candidatures fermées --}}
        <div class="card p-8 text-center">
            <h2 class="text-lg font-semibold text-white">Candidatures fermées</h2>
            <p class="mt-2 text-sm text-muted">
                Les candidatures pour « {{ $category->name }} » ne sont pas ouvertes (ou sont déjà clôturées).
            </p>
        </div>

    @else
        {{-- Formulaire --}}
        <div class="card p-6">
            <p class="eyebrow">{{ $category->voterTypeLabel() }}</p>
            <h2 class="mt-1 text-lg font-semibold text-white">{{ $category->name }}</h2>
            @if($category->description)
                <p class="mt-1 text-sm text-muted">{{ $category->description }}</p>
            @endif
            <p class="mt-3 text-sm text-muted">Remplissez ce formulaire pour proposer votre candidature.</p>

            <form wire:submit="submit" class="mt-6 space-y-4">
                @if($category->isEntity())
                    <div>
                        <label class="field-label">{{ $category->nameLabel() }}</label>
                        <input type="text" wire:model="last_name" class="input" placeholder="Ex : Club Robotique Pigier">
                        @error('last_name') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                @else
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
                        <input type="text" wire:model="class" class="input" placeholder="Ex : RGL3A">
                    </div>
                @endif

                <div>
                    <label class="field-label">Présentation <span class="font-normal text-muted">(facultatif)</span></label>
                    <textarea wire:model="description" rows="3" class="textarea" placeholder="Quelques mots sur vous / votre projet…"></textarea>
                </div>

                <div>
                    @if($category->isEntity())
                        <label class="field-label">Logo / photo</label>
                        <input type="file" wire:model="photo" accept="image/*" class="input py-2">
                        <p class="field-hint">Le logo ou une photo représentative — apparaîtra sur le bulletin de vote. Obligatoire.</p>
                    @else
                        <label class="field-label">Votre photo <span class="font-normal text-muted">(portrait)</span></label>
                        <input type="file" wire:model="photo" accept="image/*" class="input py-2">
                        <p class="field-hint">Une photo <strong>de vous</strong> — elle apparaîtra sur le bulletin de vote. Obligatoire.</p>
                    @endif
                    <div wire:loading wire:target="photo" class="field-hint">Chargement…</div>
                    @error('photo') <p class="field-error">{{ $message }}</p> @enderror
                    @if($photo && $photo->isPreviewable())
                        <img src="{{ $photo->temporaryUrl() }}" class="mt-2 h-24 border border-line object-cover">
                    @endif
                </div>

                @if($category->requires_proof)
                    <div class="border-t border-line pt-4">
                        <p class="text-sm font-medium text-offwhite">Preuve de votre travail</p>
                        <p class="field-hint">
                            @if($category->proof_type === 'both')
                                Montrez votre travail : ajoutez un <strong>lien</strong> <em>ou</em> un <strong>fichier</strong> (au moins l'un des deux).
                            @elseif($category->proof_type === 'url')
                                Ajoutez le <strong>lien</strong> vers votre travail.
                            @else
                                Joignez le <strong>fichier</strong> de votre travail.
                            @endif
                            Ce n'est pas votre photo.
                        </p>
                    </div>
                @endif

                @if($category->needsUrl())
                    <div>
                        <label class="field-label">Lien (vidéo, portfolio, Drive…)</label>
                        <input type="url" wire:model="proof_url" class="input" placeholder="https://…">
                        @error('proof_url') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                @endif

                @if($category->needsFile())
                    <div>
                        <label class="field-label">Fichier <span class="font-normal text-muted">(PDF, image, ZIP…)</span></label>
                        <input type="file" wire:model="proofFile" class="input py-2">
                        <div wire:loading wire:target="proofFile" class="field-hint">Chargement…</div>
                        @error('proofFile') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                @endif

                <button type="submit" class="btn-primary w-full" wire:loading.attr="disabled" wire:target="submit,photo,proofFile">
                    Envoyer ma candidature
                </button>
                <p class="text-center text-xs text-muted">Votre candidature sera validée par l'organisation avant d'apparaître au vote.</p>
            </form>
        </div>
    @endif
</div>
