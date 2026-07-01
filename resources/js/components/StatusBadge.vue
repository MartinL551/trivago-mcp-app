<script setup lang="ts">
    import {
        AlertCircle,
        CheckCircle2,
        Clock,
        Hotel,
        SearchCode,
        Sparkles,
    } from 'lucide-vue-next';
    import { computed } from 'vue';
    import { SearchRequestStatus } from '@/types';

    const props = defineProps<{
        status: SearchRequestStatus;
    }>();

    const statusConfig = computed(() => ({
        [SearchRequestStatus.Pending]: {
            label: 'Pending',
            icon: Clock,
            class: 'border-amber-500/70 bg-amber-500/15 text-amber-400',
        },
        [SearchRequestStatus.Interpreting]: {
            label: 'Interpreting',
            icon: SearchCode,
            class: 'border-blue-500/70 bg-blue-500/15 text-blue-400',
        },
        [SearchRequestStatus.FetchingAccommodations]: {
            label: 'Fetching accommodations',
            icon: Hotel,
            class: 'border-blue-500/70 bg-blue-500/15 text-blue-400',
        },
        [SearchRequestStatus.Scoring]: {
            label: 'Scoring',
            icon: Sparkles,
            class: 'border-violet-500/70 bg-violet-500/15 text-violet-400',
        },
        [SearchRequestStatus.Complete]: {
            label: 'Complete',
            icon: CheckCircle2,
            class: 'border-emerald-500/70 bg-emerald-500/15 text-emerald-400',
        },
        [SearchRequestStatus.Failed]: {
            label: 'Failed',
            icon: AlertCircle,
            class: 'border-red-500/70 bg-red-500/15 text-red-400',
        },
    })[props.status]);
</script>

<template>
    <span
        class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-semibold"
        :class="statusConfig.class"
    >
        <component :is="statusConfig.icon" class="size-5 shrink-0" />
        <span>{{ statusConfig.label }}</span>
    </span>
</template>
