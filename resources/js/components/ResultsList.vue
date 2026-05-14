<script setup lang="ts">
    import type { AccommidationResults } from '@/types';
import ResultCard from './ResultCard.vue';

    type Props = {
        results: AccommidationResults;
    };

    const props = defineProps<Props>();
</script>



<template>
    <div class="w-full flex flex-wrap gap-20">
        <ResultCard v-for="accommodation in props.results"   :key="accommodation.id" class="w-full" :result="accommodation" />
    </div>
</template>