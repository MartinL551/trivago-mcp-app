<script setup lang="ts">
    import { Activity } from 'lucide-vue-next';
    import { computed } from 'vue';

    type Props = {
        count: number;
        limit: number;
    };

    const props = defineProps<Props>();

    const percentage = computed(() => {
        if (props.limit <= 0) {
            return 0;
        }

        return Math.min((props.count / props.limit) * 100, 100);
    });

    const remaining = computed(() => Math.max(props.limit - props.count, 0));

    const toneClasses = computed(() => {
        if (percentage.value >= 100) {
            return {
                icon: 'bg-[var(--danger-bg)] text-[var(--danger-text)]',
                bar: 'bg-[var(--danger)]',
                text: 'text-[var(--danger-text)]',
            };
        }

        if (percentage.value >= 60) {
            return {
                icon: 'bg-[var(--warning-bg)] text-[var(--warning-text)]',
                bar: 'bg-[var(--warning)]',
                text: 'text-[var(--warning-text)]',
            };
        }

        return {
            icon: 'bg-[var(--success-bg)] text-[var(--success-text)]',
            bar: 'bg-[var(--success)]',
            text: 'text-[var(--success-text)]',
        };
    });
</script>

<template>
    <div class="rounded border border-[var(--card-border)] bg-[var(--card-bg)] p-4 shadow-[var(--card-shadow)]">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div
                    class="flex size-11 shrink-0 items-center justify-center rounded-full"
                    :class="toneClasses.icon"
                >
                    <Activity class="size-5" />
                </div>

                <div>
                    <h5 class="text-[var(--text)]">Daily search usage</h5>
                    <p class="text-sm text-[var(--text-muted)]">
                        {{ count }} of {{ limit }} searches used in the last 24 hours.
                    </p>
                </div>
            </div>

            <p class="text-sm font-semibold" :class="toneClasses.text">
                {{ remaining }} remaining
            </p>
        </div>

        <div class="mt-4 h-2 overflow-hidden rounded-full bg-[var(--surface)]">
            <div
                class="h-full rounded-full transition-all"
                :class="toneClasses.bar"
                :style="{ width: `${percentage}%` }"
            />
        </div>
    </div>
</template>
