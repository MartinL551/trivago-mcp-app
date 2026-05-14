<script setup lang="ts">
    import type { AccommidationResult } from '@/types';
import ResultScore from './ResultScore.vue';

    type Props = {
        result: AccommidationResult
    };

    const props = defineProps<Props>();
</script>

<template>
    <div class="max-w-sm rounded overflow-hidden shadow-lg">
        name: {{  props.result.name }}
        location: {{  props.result.city }}
        address: {{ props.result.address }}
        budget per day: {{  props.result.price_per_day }}
        <ResultScore :score="props.result.scores[0]" />
    </div>
</template>