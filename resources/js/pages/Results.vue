<script setup lang="ts">
    import { provide } from 'vue';
    import ResultsList from '@/components/ResultsList.vue';
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
          const response = await fetch(`/wishlist/${id}/add`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })

        if(!response.ok){

        }

        if (response.status === 200 || response.status === 201) {
           const accommodation = accommodations.value.find(a => a.id === id);

            if (accommodation) {
                console.log('Updating Wishlist');
                accommodation.wishlisted = true;
            }
        }
    }

    
    provide('handleWishListAdd', handleWishListAdd);

</script>


<template>
    <div class="py-4 text-xl"> Prompt: {{ searchRequest.prompt }}</div>
    <div class="py-4 text-xl"> Status: {{ searchRequest.status }} </div>
    <ResultsList v-if="(searchRequest.status === 'scoring' || searchRequest.status === 'complete') && accommodations" :results="accommodations" />
    <div v-else>
        Fetching Results..
    </div>
</template>