<script setup lang="ts">
    import type { AccommidationScore } from '@/types';

    type Props = {
        score: AccommidationScore
    };

    const props = defineProps<Props>();
</script>

<template>
    <div v-if="props.score">
        <dl>
            <dt>Adventure</dt>
            <dd>{{  props.score.adventure }}</dd>
            <dt>Romance</dt>
            <dd>{{  props.score.romance }}</dd>
            <dt>Budget</dt>
            <dd>{{  props.score.budget }}</dd>
        </dl>

        <div>
            {{ props.score.why }}
        </div>
    </div>
    <div v-else>
        Fetching Score...
    </div>
</template>