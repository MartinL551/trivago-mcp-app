<script setup lang="ts">
    import { MapPin, SearchCode } from 'lucide-vue-next';
    import type { SearchResult } from '@/types';
    import StatusBadge from './StatusBadge.vue';

    type Props = {
        result: SearchResult
    };

    const props = defineProps<Props>();
</script>

<template>
    <div class="w-full space-y-4 p-6">
        <div class="flex items-start gap-4">
            <SearchCode class="mt-1 size-7 shrink-0" />
            <div class="min-w-0">
                <h3 class="leading-tight text-[var(--text)]">{{ props.result.prompt }}</h3>
                <p
                    v-if="props.result.city || props.result.country"
                    class="mt-2 flex items-center gap-1.5 text-sm text-[var(--text-muted)]"
                >
                    <MapPin class="size-4 shrink-0" />
                    <span>{{ [props.result.city, props.result.country].filter(Boolean).join(', ') }}</span>
                </p>
            </div>
        </div>
        <div class="flex flex-col gap-3 pl-11 sm:flex-row sm:items-center sm:justify-between">
            <StatusBadge :status="props.result.status" />
            <a class="inline-flex items-center justify-center rounded border border-[var(--button-card-border)] bg-[var(--button-card-bg)] px-5 py-2 text-[var(--button-card-text)] transition-colors hover:bg-[var(--button-card-bg-hover)] hover:text-[var(--button-card-text-hover)]"
                :href="`/results/${props.result.id}`"
            >
                View Results
            </a>
        </div>
    </div>
</template>
