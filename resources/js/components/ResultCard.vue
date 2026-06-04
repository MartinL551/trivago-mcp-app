<script setup lang="ts">
    import type { AccommidationResult } from '@/types';
    import ResultScore from './ResultScore.vue';
import WishlistButton from './WishlistButton.vue';


    type Props = {
        result: AccommidationResult
    };

    function imageUrl(width: number): string {
        const height = Math.round(width * 534 / 800);

        return props.result.trivago_image_url
            .replace(/w_\d+/, `w_${width}`)
            .replace(/h_\d+/, `h_${height}`);
    }

    const props = defineProps<Props>();
</script>

<template>
    <div>
        <div>
            <img
                :src="imageUrl(800)"
                :srcset="`
                    ${imageUrl(400)} 400w,
                    ${imageUrl(800)} 800w,
                    ${imageUrl(1200)} 1200w
                `"
                sizes="(max-width: 768px) 100vw, 400px"
                alt="Hotel image"
                loading="lazy"
                decoding="async"
            />
        </div>
        <div class="py-2">
            name: {{  props.result.name }}
        </div>
        <div class="py-2">
            location: {{  props.result.city }}
        </div>
        <div class="py-2">
            address: {{ props.result.address }}
        </div>
        <div class="py-2">
            budget per day: {{  props.result.price_per_day }}
        </div>
        <div class="py-2">
            Rating: {{  props.result.rating }}
        </div>

        <ResultScore :scores="props.result.scores" />
        <WishlistButton :result="props.result"  />
    </div>
</template>