<script setup lang="ts">
    import type { AccommidationScore } from '@/types';

    type Props = {
        scores: AccommidationScore
    };

    const props = defineProps<Props>();
</script>

<template>
    <div v-if="props.scores">
        <dl>
            <dt class="py-1">Adventure</dt>
            <dd class="py-2">{{  props.scores[0].adventure }}</dd>
            <dt class="py-1">Romance</dt>
            <dd class="py-2">{{  props.scores[0].romance }}</dd>
            <dt class="py-1">Budget</dt>
            <dd class="py-2">{{  props.scores[0].budget }}</dd>
            <dt class="py-1">Luxury</dt>
            <dd class="py-2">{{  props.scores[0].luxury }}</dd>
            <dt class="py-1">Business</dt>
            <dd class="py-2">{{  props.scores[0].business }}</dd>
            <dt class="py-1">Family</dt>
            <dd class="py-2">{{  props.scores[0].family }}</dd>
        </dl>

        <div>
            <div class="py-2"> Why has the AI scored this way?</div>
            {{ props.scores[0].why }}
        </div>
    </div>
    <div v-else>
        Fetching Score...
    </div>
</template>