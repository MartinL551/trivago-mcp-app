<script setup lang="ts">
    import { Calendar, MapPinHouse, StarIcon, Target, UserCheck } from 'lucide-vue-next';
    import { computed } from 'vue';
    import type { AccommodationResult } from '@/types';
    import AmenitiesList from './AmenitiesList.vue';
    import ResultScore from './ResultScore.vue';
    import WishlistButton from './WishlistButton.vue';




    const amenities = computed(() => {
        if (!props.result.amenites) {
            return [];
        }

        return props.result.amenites
            .split(',')
            .map((amenity) => amenity.trim())
            .filter(Boolean);
    });


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
    <article class="relative pb-3">
        <img
            class="w-full"
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
        <header class="my-4 px-3">
            <h3> {{ props.result.name }} </h3>
        </header>

        <WishlistButton :result="props.result"  />

        <div class="px-3">
            <div class="py-2">
                <dl>
                    <div>
                        <dt class="sr-only">Location</dt>
                        <dd><MapPinHouse /> {{ props.result.city }}</dd>
                    </div>
                    <div v-if="props.result.distance_string">
                        <dt class="sr-only">Distance</dt>
                        <dd><Target /> {{ props.result.distance_string }}</dd>
                    </div>
                    <div>
                        <dt class="sr-only">Hotel rating</dt>
                        <dd><StarIcon /> {{ props.result.hotel_rating }} Stars</dd>
                    </div>
                    <div>
                        <dt class="sr-only">Review rating</dt>
                        <dd><UserCheck /> Trivago User Score {{ props.result.review_rating }} - review count {{ props.result.review_count }}</dd>
                    </div>
                </dl>
            </div>
            <div class="py-2 flex gap-10">
                <div>
                    <h5>{{ props.result.currency }} {{ props.result.price_per_night }}</h5>
                    <p>price per day</p>
                </div>
                <div>
                    <h5>{{ props.result.currency }} {{ props.result.price_per_stay }}</h5>
                    <p>total for stay</p>
                </div>
            </div>
            <div class="py-2">
                <AmenitiesList :amenities="amenities" />
            </div>
            <div class="py-2">
                <ResultScore v-if="props.result.scores" :scores="props.result.scores" />
            </div>
        </div>

  
        <div class="w-full flex flex-wrap justify-between gap-2 px-3">
            <p v-if="props.result.advertiser">
                {{ props.result.advertiser }}
            </p>
            <a class="border-1" :href="props.result.trivago_url"> View Now On Trivago! </a>
        </div>
    </article>
</template>
