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
            <dt class="py-1">Adventure</dt>
            <dd class="py-2">{{  props.score.adventure }}</dd>
            <dt class="py-1">Romance</dt>
            <dd class="py-2">{{  props.score.romance }}</dd>
            <dt class="py-1">Budget</dt>
            <dd class="py-2">{{  props.score.budget }}</dd>
        </dl>

        <div>
            <div class="py-2"> Why has the AI scored this way?</div>
            {{ props.score.why }}
        </div>
    </div>
    <div v-else>
        Fetching Score...
    </div>
</template>