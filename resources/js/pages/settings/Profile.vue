<script setup lang="ts">
    import { Head} from '@inertiajs/vue3';
    import DeleteUser from '@/components/DeleteUser.vue';
    import ProfileSettingsForm from '@/components/ProfileSettingsForm.vue';

    type Props = {
        preferredCurrencies: Array<string>;
        initialPreferredCurrency: string | null;
    };

    const props = defineProps<Props>();
</script>

<template>
    <Head title="Profile settings" />
    <ProfileSettingsForm
        :preferred-currencies="props.preferredCurrencies"
        :initial-preferred-currency="props.initialPreferredCurrency"
    />
    <DeleteUser />
</template>
