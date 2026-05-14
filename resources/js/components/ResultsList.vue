<script setup lang="ts">
    import type { SearchResult } from '@/types';
import ResultCard from './ResultCard.vue';

    type Props = {
        results: SearchResult
    };

    const props = defineProps<Props>();
</script>



<template>
    <div v-for="accommodation in props.results.accommodations"   :key="accommodation.id">
       <ResultCard :result="accommodation" />
    </div>
</template>