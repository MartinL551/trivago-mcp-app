<script setup lang="ts">
    import { inject } from 'vue';
    import type { AccommidationResult } from '@/types';

    type Props = {
        result: AccommidationResult
    };

    const props = defineProps<Props>();

        
    const handleWishListAdd = inject<(id: number) => Promise<void>>('handleWishListAdd');
    const handleWishListRemove = inject<(id: number) => Promise<void>>('handleWishListRemove');

    const handleClick = async() =>  {
        if(props.result.wishlisted){
            await handleWishListRemove(props.result.id);
        } else {
            await handleWishListAdd(props.result.id);
        }
    }


        

</script>

<template>
    <div>
        <div> Wishlisted: {{  props.result.wishlisted }}</div>
        <button @click="handleClick"> 
            <span v-if="props.result.wishlisted"> Remove From</span>
            <span v-else> Add To</span>
            Wishlist 
        </button>
    </div>
</template>