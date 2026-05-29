<script setup lang="ts">
    import type { AccommidationResults } from '@/types';
    import GenericCard from './GenericCard.vue';
    import ResultCard from './ResultCard.vue';


    type Props = {
        results: AccommidationResults;
    };

    const props = defineProps<Props>();
</script>



<template>
    <div class="w-full flex flex-wrap gap-10">
        <GenericCard v-for="accommodation in props.results" :key="accommodation.id">
              <ResultCard  class="w-full" :result="accommodation" />
        </GenericCard>
    </div>
</template>