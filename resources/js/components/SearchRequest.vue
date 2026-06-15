<script setup lang="ts">
    import { useForm } from '@inertiajs/vue3';

    const form = useForm({
        prompt: '',
    });

    const submit = () => {
        form.post('/search');
    };
</script>

<template>
    <form @submit.prevent="submit" class="relative">
        <input
            v-model="form.prompt"
            class="w-full rounded-xl border border-[var(--border)] bg-[var(--surface)] outline-none focus:border-[var(--primary)] min-h-20 px-4"
            placeholder="I want a romantic weekend in Berlin with a spa..."
            maxlength="200"
        />

        <button
            type="submit"
            class="absolute top-1/2 right-3 flex -translate-y-1/2
                items-center gap-2 rounded-lg bg-[var(--primary)]
                px-5 py-3 font-medium text-white
                hover:bg-[var(--primary-hover)]"
        >
            Search
        </button>
    </form>
</template>