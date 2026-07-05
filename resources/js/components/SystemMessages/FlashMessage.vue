<script setup lang="ts">
    import { usePage } from '@inertiajs/vue3';
    import { AlertCircle, CheckCircle2, Info, TriangleAlert, X } from 'lucide-vue-next';
    import { computed, ref, watch } from 'vue';


    type FlashType = 'success' | 'warning' | 'error' | 'info';

    type Flash = {
        type?: FlashType | null;
        message?: string | null;
    };

    const page = usePage();
    const dismissed = ref(false);

    const flash = computed(() => page.props.flash as Flash | undefined);
    const message = computed(() => flash.value?.message);
    const type = computed<FlashType>(() => flash.value?.type ?? 'info');

    watch(message, () => {
        dismissed.value = false;
    });

    const icon = computed(() => {
        return {
            success: CheckCircle2,
            warning: TriangleAlert,
            error: AlertCircle,
            info: Info,
        }[type.value];
    });

    const styles = computed(() => {
        return {
            success: 'border-[var(--success-border)] bg-[var(--success-bg)] text-[var(--success-text)]',
            warning: 'border-[var(--warning)] bg-[var(--warning-bg)] text-[var(--warning-text)]',
            error: 'border-[var(--danger-border)] bg-[var(--danger-bg)] text-[var(--danger-text)]',
            info: 'border-[var(--border)] bg-[var(--surface-elevated)] text-[var(--text)]',
        }[type.value];
    });
</script>

<template>
    <div
        v-if="message && !dismissed"
        class="mx-auto mt-4 max-w-7xl px-4"
    >
        <div
            class="flex items-start gap-3 rounded border p-4 shadow-[var(--card-shadow)]"
            :class="styles"
        >
            <component :is="icon" class="mt-0.5 size-5 shrink-0" />

            <p class="min-w-0 flex-1 text-sm font-medium">
                {{ message }}
            </p>

            <button
                type="button"
                class="shrink-0 rounded p-1 transition-colors hover:bg-[var(--overlay)]"
                aria-label="Dismiss message"
                @click="dismissed = true"
            >
                <X class="size-4" />
            </button>
        </div>
    </div>
</template>
