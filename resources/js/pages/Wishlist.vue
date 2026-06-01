<script setup lang="ts">
    import GenericCard from '@/components/GenericCard.vue';
    import ResultCard from '@/components/ResultCard.vue';
    import type { AccommidationResults } from '@/types';

    type Props = {
        wishListedAccoms: AccommidationResults;
    };
    

    const props = defineProps<Props>();
</script>

<template>
    <div class="w-full flex flex-wrap gap-10">
        <GenericCard v-for="accommodation in props.wishListedAccoms" :key="accommodation.id">
              <ResultCard  class="w-full" :result="accommodation" />
        </GenericCard>
    </div>
</template>