<script setup lang="ts">
import CurrencyIcon from '@/components/CurrencyIcon.vue';
import type { CurrencyCode } from '@/types';

type Props = {
    currencies: CurrencyCode[];
    id?: string;
    label?: string;
    placeholder?: string;
    disabled?: boolean;
};

withDefaults(defineProps<Props>(), {
    id: 'preferred-currency',
    label: 'Preferred currency',
    placeholder: 'Select a currency',
    disabled: false,
});

const model = defineModel<CurrencyCode | null>();
</script>

<template>
    <label :for="id" class="block space-y-2">
        <span class="text-sm font-medium text-[var(--text)]">{{ label }}</span>

        <div class="relative">
            <span
                v-if="model"
                class="pointer-events-none absolute left-3 top-1/2 flex size-5 -translate-y-1/2 items-center justify-center text-[var(--text-muted)]"
            >
                <CurrencyIcon :currency="model" :show-fallback="false" />
            </span>

            <select
                :id="id"
                v-model="model"
                :disabled="disabled"
                class="w-full rounded border border-[var(--border)] bg-[var(--surface-elevated)] py-2.5 pl-10 pr-3 text-sm text-[var(--text)] shadow-sm transition-colors focus:border-[var(--primary)] focus:outline-none focus:ring-2 focus:ring-[var(--focus-ring)] disabled:cursor-not-allowed disabled:opacity-60"
            >
                <option :value="null">
                    {{ placeholder }}
                </option>
                <option
                    v-for="currency in currencies"
                    :key="currency"
                    :value="currency"
                >
                    {{ currency }}
                </option>
            </select>
        </div>
    </label>
</template>
