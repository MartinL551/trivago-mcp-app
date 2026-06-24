<script setup lang="ts">
    import { router } from '@inertiajs/vue3';
    import { provide } from 'vue';
    import GenericResultList from '@/components/GenericResultList.vue';
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

        if(response.ok) {
            router.reload();
        }
    }

    const handleWishListRemove = async(id: number) => {
        const response = await fetch(`/wishlist/${id}/remove`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })

        
        if(response.ok) {
            router.reload();
        }
    }

    
    provide('handleWishListAdd', handleWishListAdd);
    provide('handleWishListRemove', handleWishListRemove);
</script>

<template>
    <GenericResultList :results="props.wishListedAccoms">
        <template #default="{ item: accommodation }">
            <ResultCard class="w-full" :result="accommodation" />
        </template>
    </GenericResultList>
</template>