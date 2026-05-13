<script setup lang="ts">
    import ResultsList from '@/components/ResultsList.vue';
    import type { SearchResult } from '@/types';

    type Props = {
        searchResult: SearchResult
    };

    const props = withDefaults(defineProps<Props>(), {
    });

</script>


<template> 
    <div>  
        <ResultsList :search-result="props.searchResult" />
    </div>
</template>