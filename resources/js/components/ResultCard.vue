<script setup lang="ts">
    import type { AccommidationResult } from '@/types';

    type Props = {
        result: AccommidationResult
    };

    const props = defineProps<Props>();
</script>

<template>
    <div class="max-w-sm rounded overflow-hidden shadow-lg">
        {{ props.result.name }}
    </div>
</template>