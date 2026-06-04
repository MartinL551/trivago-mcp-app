<script setup lang="ts">
    import { router } from '@inertiajs/vue3';
    import { provide } from 'vue';
    import GenericCard from '@/components/GenericCard.vue';
    import ResultCard from '@/components/ResultCard.vue';
    import type { AccommidationResults } from '@/types';
  



    type Props = {
        wishListedAccoms: AccommidationResults;
    };
    

    const props = defineProps<Props>();

    const handleWishListAdd = async(id: number) => {
        const response = await fetch(`/wishlist/${id}/add`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })

        router.reload();

    }

    const handleWishListRemove = async(id: number) => {
        const response = await fetch(`/wishlist/${id}/remove`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })

        
        router.reload();
    }

    
    provide('handleWishListAdd', handleWishListAdd);
    provide('handleWishListRemove', handleWishListRemove);

    
</script>

<template>
    <div class="w-full flex flex-wrap gap-10">
        <GenericCard v-for="accommodation in props.wishListedAccoms" :key="accommodation.id">
              <ResultCard  class="w-full" :result="accommodation" />
        </GenericCard>
    </div>
</template>