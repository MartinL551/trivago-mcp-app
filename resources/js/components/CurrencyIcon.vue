<script setup lang="ts">
    import {
        DollarSign,
        Euro,
        IndianRupee,
        JapaneseYen,
        PoundSterling,
        SwissFranc,
    } from 'lucide-vue-next';
    import { computed } from 'vue';

    const props = withDefaults(defineProps<{
        currency: string;
        showFallback?: boolean;
    }>(), {
        showFallback: true,
    });

    const currencyCode = computed(() => props.currency.trim().toUpperCase());

    const currencyIcon = computed(() => {
        switch (currencyCode.value) {
            case 'AUD':
            case 'CAD':
            case 'HKD':
            case 'NZD':
            case 'SGD':
            case 'USD':
                return DollarSign;

            case 'EUR':
                return Euro;

            case 'GBP':
                return PoundSterling;

            case 'JPY':
                return JapaneseYen;

            case 'CHF':
                return SwissFranc;

            case 'INR':
                return IndianRupee;

            default:
                return null;
        }
    });
</script>

<template>
    <component
        :is="currencyIcon"
        v-if="currencyIcon"
        class="size-5 shrink-0"
        aria-hidden="true"
    />
    <span
        v-else-if="showFallback"
        class="inline-flex min-w-8 shrink-0 items-center justify-center text-xs font-semibold"
    >
        {{ currencyCode }}
    </span>
</template>
