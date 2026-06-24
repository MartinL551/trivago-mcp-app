<script setup lang="ts">
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
        <GenericResultList :results="props.searchRequests">
            <template #default="{ item: searchRequests }">
                <SearchResultCard class="w-full" :result="searchRequests" />
            </template>
        </GenericResultList>
    </div>
</template>