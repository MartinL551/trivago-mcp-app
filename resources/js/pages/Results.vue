<script setup lang="ts">
    import { router } from '@inertiajs/vue3';
    import { onMounted, onUnmounted } from 'vue';
    import ResultsList from '@/components/ResultsList.vue';
    import type { SearchResult, AccommidationResults } from '@/types';

    type Props = {
        searchRequest: SearchResult;
        accommodations: AccommidationResults;
    };

    const props = defineProps<Props>();

    let interval: number;

    const poll = async () => {
        console.log('polling')
        router.reload({
            only: ['searchRequest', 'accommodations'],
        });
    }

    onMounted(() => {
        poll();
        
        interval = window.setInterval(() => {
            poll(); 
        }, 3000);
    })

    onUnmounted(() => {
        clearInterval(interval);
    })
</script>


<template>
    <div> Prompt: {{ searchRequest.prompt }} Status: {{ searchRequest.status }}</div>
    <ResultsList v-if="(searchRequest.status === 'scoring' || searchRequest.status === 'complete') && accommodations" :results="props.accommodations" />
    <div v-else>
        Fetching Results..
    </div>
</template>