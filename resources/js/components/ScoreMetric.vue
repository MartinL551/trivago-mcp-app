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
    <article class="" :style="{ '--score-color': color }">
        <div class="">
            <component :is="icon" />
        </div>

        <div class="">
            <header class="s">
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
