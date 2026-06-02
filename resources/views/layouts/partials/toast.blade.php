{{-- Notifications — écoute les events Livewire --}}
<div
    x-data="{
        toasts: [],
        push(message, type) {
            const id = Date.now() + Math.random();
            this.toasts.push({ id, message, type });
            setTimeout(() => this.remove(id), 4000);
        },
        remove(id) { this.toasts = this.toasts.filter(t => t.id !== id); }
    }"
    @toast.window="push($event.detail.message, 'success')"
    @vote-success.window="push($event.detail.message, 'success')"
    @vote-error.window="push($event.detail.message, 'error')"
    class="fixed bottom-4 right-4 z-[100] flex w-full max-w-sm flex-col gap-2 px-4 sm:px-0"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="card flex items-start gap-3 p-4"
            :class="toast.type === 'error' ? 'border-red-500/40' : 'border-gold-main/40'"
        >
            <span class="mt-0.5 h-2 w-2 shrink-0 rounded-full"
                  :class="toast.type === 'error' ? 'bg-red-400' : 'bg-gold-main'"></span>
            <p class="flex-1 text-sm text-offwhite" x-text="toast.message"></p>
            <button @click="remove(toast.id)" class="text-muted hover:text-offwhite" aria-label="Fermer">&times;</button>
        </div>
    </template>
</div>
