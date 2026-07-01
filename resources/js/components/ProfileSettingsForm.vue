<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { Save } from 'lucide-vue-next';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import InputError from '@/components/InputError.vue';
import PreferredCurrencyDropdown from '@/components/PreferredCurrencyDropdown.vue';
import { Button } from '@/components/ui/button';
import type { CurrencyCode, User } from '@/types';

type Props = {
    preferredCurrencies: CurrencyCode[];
    initialPreferredCurrency?: CurrencyCode | null;
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
    <form
        class="rounded border-3 border-[var(--card-border)] bg-[var(--card-bg)] p-6 shadow-[var(--card-shadow)]"
        @submit.prevent="submit"
    >
        <input v-model="form.name" type="hidden" name="name" />

        <div class="space-y-5">
            <div>
                <h3 class="text-[var(--text)]">Account preferences</h3>
                <p class="mt-1 text-sm text-[var(--text-muted)]">
                    Choose how prices are shown throughout your searches.
                </p>
            </div>

            <PreferredCurrencyDropdown
                v-model="form.preferred_currency"
                :currencies="preferredCurrencies"
                :disabled="form.processing"
            />

            <InputError :message="form.errors.preferred_currency" />

            <div class="flex flex-wrap items-center gap-4">
                <Button
                    class="border border-[var(--button-card-border)] bg-[var(--button-card-bg)] text-[var(--button-card-text)] hover:bg-[var(--button-card-bg-hover)] hover:text-[var(--button-card-text-hover)]"
                    type="submit"
                    :disabled="form.processing"
                >
                    <Save class="size-4" />
                    Save
                </Button>

                <p v-if="form.recentlySuccessful" class="text-sm text-[var(--success-text)]">
                    Saved.
                </p>
            </div>
        </div>
    </form>
</template>
