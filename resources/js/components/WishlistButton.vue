<script setup lang="ts">
    import { inject } from 'vue';
    import type { AccommidationResult } from '@/types';

    type Props = {
        result: AccommidationResult
    };

    const props = defineProps<Props>();

        
    const handleWishListAdd = inject<(id: number) => Promise<void>>('handleWishListAdd');

    const handleClick = async() =>  {
        if(props.result.wishlisted){

        }

        await handleWishListAdd(props.result.id);
    }


        

</script>

<template>
    <div>
        <div> Wishlisted: {{  props.result.wishlisted }}</div>
        <button @click="handleClick"> Add To Wishlist </button>
    </div>
</template>