<script setup lang="ts">
    import type { AccommidationResult } from '@/types';

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
        score {{ props.result.score }}
    </div>
</template>