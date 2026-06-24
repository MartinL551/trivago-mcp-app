<script setup lang="ts" generic="T">
    import { computed } from 'vue';
    import GenericCard from './GenericCard.vue';


    const props = defineProps<{
        results: Iterable<T>;
        getKey?: (item: T, index: number) => string | number | symbol;
    }>();

    defineSlots<{
        default(props: {item: T; index: number }): unknown;
    }>();

    const items = computed(() => Array.from(props.results));


    const keyFor = (item: T, index: number) => {
        return props.getKey?.(item, index) ?? index;
    };
</script>



<template>
    <div class="w-full flex flex-wrap gap-10">
        <GenericCard v-for="(item, index) in items" :key="keyFor(item, index)">
            <slot :item="item" :index="index" />
        </GenericCard>
    </div>
</template>
