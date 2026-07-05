<script setup lang="ts">
    import { router } from '@inertiajs/vue3';
    import { Heart } from 'lucide-vue-next';
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
    <div class="my-5 rounded border-3 border-[var(--card-border)] bg-[var(--card-bg)] p-4 shadow-[var(--card-shadow)]">
        <div class="flex items-center gap-4">
            <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[var(--primary-soft)] text-[var(--primary)]">
                <Heart class="size-6" />
            </div>

            <div class="min-w-0 flex-1">
                <h2 class="text-lg font-semibold leading-snug text-[var(--text)]">
                    View Wishlisted Hotels
                </h2>
                <p class="mt-1 text-[var(--text-muted)]">
                    Compare the hotels you have saved and remove any that no longer fit.
                </p>
            </div>
        </div>
    </div>

    <GenericResultList :results="props.wishListedAccoms">
        <template #default="{ item: accommodation }">
            <ResultCard class="w-full" :result="accommodation" />
        </template>
    </GenericResultList>
</template>
