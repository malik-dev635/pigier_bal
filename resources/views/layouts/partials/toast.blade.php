{{-- Système de notifications (toast) — écoute les events Livewire --}}
<div
    x-data="{
        toasts: [],
        push(message, type = 'success') {
            const id = Date.now() + Math.random();
            this.toasts.push({ id, message, type });
            setTimeout(() => this.remove(id), 4000);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }"
    @toast.window="push($event.detail.message, 'success')"
    @vote-success.window="push($event.detail.message, 'success')"
    @vote-error.window="push($event.detail.message, 'error')"
    class="fixed top-6 right-6 z-[100] flex flex-col gap-3 w-80"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-x-6"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="gold-card !p-4 flex items-start gap-3"
            :class="toast.type === 'error' ? '!border-red-700/60' : '!border-gold-main'"
        >
            <span class="text-xl leading-none" x-text="toast.type === 'error' ? '⚠' : '✓'"
                  :class="toast.type === 'error' ? 'text-red-400' : 'text-gold-main'"></span>
            <p class="text-sm text-offwhite flex-1" x-text="toast.message"></p>
            <button @click="remove(toast.id)" class="text-muted hover:text-gold-light">✕</button>
        </div>
    </template>
</div>
