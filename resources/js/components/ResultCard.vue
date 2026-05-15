<script setup lang="ts">
    import type { AccommidationResult } from '@/types';
import ResultScore from './ResultScore.vue';

    type Props = {
        result: AccommidationResult
    };

    const props = defineProps<Props>();
</script>

<template>
    <div class="max-w-sm rounded overflow-hidden shadow-lg dark:bg-gray-600">
        <div class="py-2">
            name: {{  props.result.name }}
        </div>
        <div class="py-2">
            location: {{  props.result.city }}
        </div>
        <div class="py-2">
            address: {{ props.result.address }}
        </div>
        <div class="py-2">
            budget per day: {{  props.result.price_per_day }}
        </div>

        <ResultScore :scores="props.result.scores" />
    </div>
</template>