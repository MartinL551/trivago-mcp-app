<script setup lang="ts">
    import type { SearchResult } from '@/types';

    type Props = {
        result: SearchResult
    };

    const props = defineProps<Props>();
</script>

<template>
    <div class="space-y-2 p-4">
        <div>
            <span class="font-medium">Search #{{ props.result.id }}</span>
        </div>

        <div>
            <span class="font-medium">Prompt:</span>
            {{ props.result.prompt }}
        </div>

        <div>
            <span class="font-medium">Status:</span>
            {{ props.result.status }}
        </div>

        <a
            :href="`/results/${props.result.id}`"
        >
            View results
        </a>
    </div>
</template>
