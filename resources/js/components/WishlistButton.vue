<script setup lang="ts">
    import type { AccommidationResult } from '@/types';

    type Props = {
        result: AccommidationResult
    };

    const handleWishListAdd = () => {

    }
        
    const props = defineProps<Props>();
</script>

<template>
    <div>
        <button @click="handleWishListAdd"> Add To Wishlist </button>
    </div>
</template>