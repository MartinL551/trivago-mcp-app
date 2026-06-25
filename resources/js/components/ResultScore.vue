<script setup lang="ts">
    import { BriefcaseBusiness, Gem, Heart, Mountain, Users, Wallet } from 'lucide-vue-next';
    import type { AccommodationScore } from '@/types';
    import ScoreMetric from './ScoreMetric.vue';

    type Props = {
        scores: AccommodationScore;
    };

    const props = defineProps<Props>();
</script>

<template>
    <section v-if="props.scores" aria-label="Accommodation scores" class="p-3 pb-3  border-1 shadow-[var(--card-shadow)] bg-[var(--chip-bg)] border-[var(--chip-border)] ">
        <ScoreMetric
            name="Adventure"
            :icon="Mountain"
            :score="props.scores.adventure"
            color="var(--score-adventure)"
        />
        <ScoreMetric
            name="Romance"
            :icon="Heart"
            :score="props.scores.romance"
            color="var(--score-romance)"
        />
        <ScoreMetric
            name="Budget"
            :icon="Wallet"
            :score="props.scores.budget"
            color="var(--score-budget)"
        />
        <ScoreMetric
            name="Luxury"
            :icon="Gem"
            :score="props.scores.luxury"
            color="var(--score-luxury)"
        />
        <ScoreMetric
            name="Business"
            :icon="BriefcaseBusiness"
            :score="props.scores.business"
            color="var(--score-business)"
        />
        <ScoreMetric
            name="Family"
            :icon="Users"
            :score="props.scores.family"
            color="var(--score-family)"
        />

        <section aria-labelledby="score-reason-heading">
            <h4 class="py-2">Why has the AI scored this way?</h4>
            <p>{{ props.scores.why }}</p>
        </section>
    </section>
    <p v-else>
        Fetching Score...
    </p>
</template>
