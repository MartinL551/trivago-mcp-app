<script setup lang="ts">
    import { Head } from '@inertiajs/vue3';
    import DeleteUser from '@/components/DeleteUser.vue';
    import ProfileSettingsForm from '@/components/ProfileSettingsForm.vue';
    import type { CurrencyCode } from '@/types';

    type Props = {
        preferredCurrencies: Array<CurrencyCode>;
        initialPreferredCurrency: CurrencyCode | null;
    };

    const props = defineProps<Props>();
</script>

<template>
    <Head title="Profile settings" />
    <div class="mx-auto max-w-5xl space-y-6 px-4 py-6">
        <ProfileSettingsForm
            :preferred-currencies="props.preferredCurrencies"
            :initial-preferred-currency="props.initialPreferredCurrency"
        />
        <DeleteUser />
    </div>
</template>
