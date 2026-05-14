<script setup lang="ts">
    import ResultsList from '@/components/ResultsList.vue';
    import type { SearchResult } from '@/types';

    type Props = {
        searchRequest: SearchResult
    };

    const props = defineProps<Props>();
</script>


<template> 
    <div>  
        <ResultsList :results="props.searchRequest" />
    </div>
</template>