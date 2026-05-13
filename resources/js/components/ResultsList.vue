<script setup lang="ts">
    import type { SearchResult } from '@/types';

    type Props = {
        searchResult: SearchResult
    };

    const props = withDefaults(defineProps<Props>(), {
    });
</script>



<template>
    <div>
       {{ props.searchResult }}
    </div>
</template>