<script setup lang="ts">
    import GenericResultList from '@/components/GenericResultList.vue';
    import DailySearchUsage from '@/components/DailySearchUsage.vue';
    import SearchRequest from '@/components/SearchRequest.vue';
    import SearchResultCard from '@/components/SearchResultCard.vue';

    import type { SearchRequests } from '@/types';

    type Props = {
        recentSearchRequests: SearchRequests;
        dailySearchCount: number;
        dailySearchLimit: number;
    };

    const props = defineProps<Props>();
</script>

<template>
    <div class="py-8">
        <div class="mx-auto flex w-full max-w-4xl flex-col items-center py-5 text-center">
            <h1 class="text-[var(--text)]">Find your perfect stay</h1>
            <h5 class="mt-2 max-w-2xl text-[var(--text-muted)]">
                Describe your ideal hotel and we'll find the perfect fit.
            </h5>
        </div>

        <div class="rounded-2xl border border-[var(--border)] bg-[var(--surface-elevated)] p-3 shadow-[var(--card-shadow)]">
            <SearchRequest />
        </div>
    </div>

    <DailySearchUsage
        class="mb-8"
        :count="props.dailySearchCount"
        :limit="props.dailySearchLimit"
    />

    <div v-if="props.recentSearchRequests" class="py-4">
        <div class="mb-4 text-center">
            <h5 class="text-[var(--text)]">Recent Searches</h5>
        </div>

        <GenericResultList :results="props.recentSearchRequests">
            <template #default="{ item: searchRequests }">
                <SearchResultCard class="w-full" :result="searchRequests" />
            </template>
        </GenericResultList>
    </div>
</template>
