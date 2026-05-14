<script setup lang="ts">
    import type { AccommidationResult } from '@/types';

    type Props = {
        result: AccommidationResult
    };

    const props = defineProps<Props>();
</script>

<template>
    <div class="max-w-sm rounded overflow-hidden shadow-lg">
        <div v-for="(param, index) in props.result" :key="index"> 
            {{  param  }}
        </div>
    </div>
</template>