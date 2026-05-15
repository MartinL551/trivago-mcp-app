<script setup lang="ts">
    import ResultsList from '@/components/ResultsList.vue';
    import { useSearchPolling } from '@/composables/useSearchPolling';
    import type { SearchResult, AccommidationResults } from '@/types';

    type Props = {
        initialSearchRequest: SearchResult;
        initalAccommodations: AccommidationResults;
    };

    const props = defineProps<Props>();

    const {
        searchRequest,
        accommodations,
    } = useSearchPolling(
        props.initialSearchRequest,
        props.initalAccommodations,
    )
</script>


<template>
    <div class="py-4 text-xl"> Prompt: {{ searchRequest.prompt }}</div>
    <div class="py-4 text-xl"> Status: {{ searchRequest.status }} </div>
    <ResultsList v-if="(searchRequest.status === 'scoring' || searchRequest.status === 'complete') && accommodations" :results="accommodations" />
    <div v-else>
        Fetching Results..
    </div>
</template>