<script setup lang="ts">
    import type { AccommodationResult } from '@/types';
    import ResultScore from './ResultScore.vue';
    import WishlistButton from './WishlistButton.vue';


    type Props = {
        result: AccommodationResult
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
        <div v-if="props.result.distance_string" class="py-2">
            distance: {{ props.result.distance_string }}
        </div>
        <div class="py-2">
            price per night: {{ props.result.currency }} {{ props.result.price_per_night }}
        </div>
        <div class="py-2">
            price per stay: {{ props.result.currency }} {{ props.result.price_per_stay }}
        </div>
        <div class="py-2">
            hotel rating: {{ props.result.hotel_rating }}
        </div>
        <div class="py-2">
            review rating: {{ props.result.review_rating }} ({{ props.result.review_count }} reviews)
        </div>
        <div class="py-2">
            amenities: {{ props.result.amenites }}
        </div>
        <div v-if="props.result.arrival && props.result.departure" class="py-2">
            dates: {{ props.result.arrival }} to {{ props.result.departure }}
        </div>
        <div v-if="props.result.advertiser" class="py-2">
            advertiser: {{ props.result.advertiser }}
        </div>
        <div v-if="props.result.latitude && props.result.longitude" class="py-2">
            coordinates: {{ props.result.latitude }}, {{ props.result.longitude }}
        </div>
        <div>
            <a :href="props.result.trivago_url"> View Now On Trivago! </a>
        </div>
    

        <ResultScore :scores="props.result.scores" />
        <WishlistButton :result="props.result"  />
    </div>
</template>
