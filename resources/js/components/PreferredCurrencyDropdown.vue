<script setup lang="ts">
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

        <select
            :id="id"
            v-model="model"
            :disabled="disabled"
            class="w-full rounded border border-[var(--border)] bg-[var(--surface-elevated)] px-3 py-2.5 text-sm text-[var(--text)] shadow-sm transition-colors focus:border-[var(--primary)] focus:outline-none focus:ring-2 focus:ring-[var(--focus-ring)] disabled:cursor-not-allowed disabled:opacity-60"
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
    </label>
</template>
