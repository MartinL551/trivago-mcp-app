<script setup lang="ts">
    import { SearchCode } from 'lucide-vue-next';
    import GenericResultList from '@/components/GenericResultList.vue';
    import SearchResultCard from '@/components/SearchResultCard.vue';
    import type { SearchRequests } from '@/types';

    type Props = {
        searchRequests: SearchRequests;
    };

    const props = defineProps<Props>();
</script>

<template>
    <div>
        <div class="my-5 rounded border-3 border-[var(--card-border)] bg-[var(--card-bg)] p-4 shadow-[var(--card-shadow)]">
            <div class="flex items-center gap-4">
                <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[var(--primary-soft)] text-[var(--primary)]">
                    <SearchCode class="size-6" />
                </div>

                <div class="min-w-0 flex-1">
                    <h2 class="text-lg font-semibold leading-snug text-[var(--text)]">
                        View Past Searches
                    </h2>
                    <p class="mt-1 text-[var(--text-muted)]">
                        Revisit your previous hotel searches and open their results.
                    </p>
                </div>
            </div>
        </div>

        <GenericResultList :results="props.searchRequests">
            <template #default="{ item: searchRequests }">
                <SearchResultCard class="w-full" :result="searchRequests" />
            </template>
        </GenericResultList>
    </div>
</template>
