<script setup lang="ts">
    import { provide } from 'vue';
    import GenericResultList from '@/components/GenericResultList.vue';
    import ResultCard from '@/components/ResultCard.vue';
    import { useSearchPolling } from '@/composables/useSearchPolling';
    import type { SearchResult, AccommidationResults } from '@/types';




    type Props = {
        initialSearchRequest: SearchResult;
        initalAccommodations: AccommidationResults;
    };

    const props = defineProps<Props>();

    const {
        searchRequest,
        accommodations,
    } = useSearchPolling(
        props.initialSearchRequest,
        props.initalAccommodations,
    )
    
    const handleWishListAdd = async(id: number) => {
        const accommodation = accommodations.value.find(a => a.id === id);

        accommodation.wishlisted = true;

        const response = await fetch(`/wishlist/${id}/add`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })

        if(!response.ok) {
            accommodation.wishlisted = false;
        }
    }

    const handleWishListRemove = async(id: number) => {
        const accommodation = accommodations.value.find(a => a.id === id);

        accommodation.wishlisted = false;

        const response = await fetch(`/wishlist/${id}/remove`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })

        if(!response.ok) {
            accommodation.wishlisted = true;
        }
    }

    
    provide('handleWishListAdd', handleWishListAdd);
    provide('handleWishListRemove', handleWishListRemove);

</script>


<template>
    <div class="py-4 text-xl"> Prompt: {{ searchRequest.prompt }}</div>
    <div class="py-4 text-xl"> Status: {{ searchRequest.status }} </div>
    <GenericResultList v-if="(searchRequest.status === 'scoring' || searchRequest.status === 'complete') && accommodations" :results="accommodations">
        <template #default="{ item: accommodation }">
            <ResultCard class="w-full" :result="accommodation" />
        </template>
    </GenericResultList>
    <div v-else>
        Fetching Results..
    </div>
</template>