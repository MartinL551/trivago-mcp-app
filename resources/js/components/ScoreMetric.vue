<script setup lang="ts">
    import type { Component } from 'vue';

    defineProps<{
        name: string;
        icon: Component;
        score: number | string;
        color: string;
    }>();
</script>

<template>
    <article :style="{ '--score-color': color }" class="py-2">
        <div>
            <component :is="icon" />
        </div>

        <div>
            <header>
                <h4>{{ name }}</h4>
                <data :value="score">{{ score }}</data>
            </header>

            <div
                role="meter"
                :aria-label="`${name} score`"
                aria-valuemin="0"
                aria-valuemax="100"
                :aria-valuenow="Number(score)"
                class="h-2 w-full overflow-hidden rounded-full bg-slate-700/20"
            >
                <div
                    class="h-full rounded-full bg-[var(--score-color)]"
                    :style="{ width: `${score}%` }"
                />
            </div>
        </div>
    </article>
</template>
