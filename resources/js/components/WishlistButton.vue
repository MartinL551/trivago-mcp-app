<script setup lang="ts">
    import { Heart } from 'lucide-vue-next';
    import { inject } from 'vue';
    import type { AccommidationResult } from '@/types';


    type Props = {
        result: AccommidationResult
    };

    const props = defineProps<Props>();

        
    const handleWishListAdd = inject<(id: number) => Promise<void>>('handleWishListAdd');
    const handleWishListRemove = inject<(id: number) => Promise<void>>('handleWishListRemove');

    const handleClick = async() =>  {
        if (!handleWishListAdd || !handleWishListRemove) {
            return;
        }

        if(props.result.wishlisted){
            await handleWishListRemove(props.result.id);
        } else {
            await handleWishListAdd(props.result.id);
        }
    }


        

</script>

<template>
    <div>
        <button @click="handleClick" class="absolute right-3 top-3 rounded-full bg-white/90 p-2 text-slate-700 shadow hover:text-red-500" aria-label="Add to wishlist"> 
            <Heart class="h-5 w-5" />
            <!-- Styling for wislisted. Need to addd in later -->
            <!-- <Heart class="h-5 w-5 fill-red-500 text-red-500" /> -->
        </button>
    </div>
</template>
