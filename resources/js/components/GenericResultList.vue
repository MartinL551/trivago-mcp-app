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
    <div class="grid w-full grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
        <GenericCard v-for="(item, index) in items" :key="keyFor(item, index)">
            <slot :item="item" :index="index" />
        </GenericCard>
    </div>
</template>
