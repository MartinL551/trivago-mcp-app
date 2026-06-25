<script setup lang="ts">
    import type { AccommidationScore } from '@/types';

    type Props = {
        scores: AccommidationScore
    };

    const props = defineProps<Props>();
</script>

<template>
    <section v-if="props.scores" aria-label="Accommodation scores">
        <dl>
            <div>
                <dt class="py-1">Adventure</dt>
                <dd class="py-2">{{ props.scores.adventure }}</dd>
            </div>
            <div>
                <dt class="py-1">Romance</dt>
                <dd class="py-2">{{ props.scores.romance }}</dd>
            </div>
            <div>
                <dt class="py-1">Budget</dt>
                <dd class="py-2">{{ props.scores.budget }}</dd>
            </div>
            <div>
                <dt class="py-1">Luxury</dt>
                <dd class="py-2">{{ props.scores.luxury }}</dd>
            </div>
            <div>
                <dt class="py-1">Business</dt>
                <dd class="py-2">{{ props.scores.business }}</dd>
            </div>
            <div>
                <dt class="py-1">Family</dt>
                <dd class="py-2">{{ props.scores.family }}</dd>
            </div>
        </dl>

        <section aria-labelledby="score-reason-heading">
            <h4 class="py-2">Why has the AI scored this way?</h4>
            <p>{{ props.scores.why }}</p>
        </section>
    </section>
    <p v-else>
        Fetching Score...
    </p>
</template>
