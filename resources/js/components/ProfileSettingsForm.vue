<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import InputError from '@/components/InputError.vue';
import PreferredCurrencyDropdown from '@/components/PreferredCurrencyDropdown.vue';
import { Button } from '@/components/ui/button';
import type { User } from '@/types';

type Props = {
    preferredCurrencies: string[];
    initialPreferredCurrency?: string | null;
    action?: string;
};

const props = withDefaults(defineProps<Props>(), {
    initialPreferredCurrency: null,
    action: ProfileController.update.url(),
});

const user = usePage().props.auth.user as User;

const form = useForm({
    name: user.name,
    preferred_currency: props.initialPreferredCurrency,
});

const submit = () => {
    form.patch(props.action, {
        preserveScroll: true,
    });
};
</script>

<template>
    <form class="space-y-4" @submit.prevent="submit">
        <input v-model="form.name" type="hidden" name="name" />

        <PreferredCurrencyDropdown
            v-model="form.preferred_currency"
            :currencies="preferredCurrencies"
            :disabled="form.processing"
        />

        <InputError :message="form.errors.preferred_currency" />

        <div class="flex items-center gap-4">
            <Button class="button" type="submit" :disabled="form.processing">
                Save
            </Button>

            <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">
                Saved.
            </p>
        </div>
    </form>
</template>
