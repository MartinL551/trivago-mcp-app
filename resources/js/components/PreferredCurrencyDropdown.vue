<script setup lang="ts">
type Props = {
    currencies: string[];
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

const model = defineModel<string | null>();
</script>

<template>
    <label :for="id" class="block space-y-2">
        <span class="text-sm font-medium">{{ label }}</span>

        <select
            :id="id"
            v-model="model"
            :disabled="disabled"
            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-gray-500 focus:outline-none focus:ring-1 focus:ring-gray-500 disabled:cursor-not-allowed disabled:opacity-60 dark:border-gray-700 dark:bg-gray-900"
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
