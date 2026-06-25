<script setup lang="ts">
    import { provide } from 'vue';
    import GenericResultList from '@/components/GenericResultList.vue';
    import ResultCard from '@/components/ResultCard.vue';
    import { useSearchPolling } from '@/composables/useSearchPolling';
    import type { SearchResult, AccommidationResults } from '@/types';
    import { SearchCode } from 'lucide-vue-next';

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

        if (!accommodation) {
            return;
        }

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

        if (!accommodation) {
            return;
        }

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
    <div class="my-5 rounded border-3 border-[var(--card-border)] bg-[var(--card-bg)] p-4 shadow-[var(--card-shadow)]">
        <div class="flex items-center gap-4">
            <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[var(--primary-soft)] text-[var(--primary)]">
                <SearchCode class="size-6" />
            </div>

            <div class="min-w-0 flex-1">
                <h2 class="text-lg font-semibold leading-snug text-[var(--text)]">{{ searchRequest.prompt }}</h2>
                <p class="mt-1 text-sm font-medium text-[var(--text-muted)]">{{ searchRequest.status }}</p>
            </div>
        </div>
    </div>

    <GenericResultList v-if="(searchRequest.status === 'complete') && accommodations" :results="accommodations">
        <template #default="{ item: accommodation }">
            <ResultCard class="w-full" :result="accommodation" />
        </template>
    </GenericResultList>
    <div v-else>
        Fetching Results..
    </div>
</template>
