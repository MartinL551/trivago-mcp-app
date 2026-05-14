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
    <form @submit.prevent="submit">
        <textarea
            v-model="form.prompt"
            class="w-full rounded border p-2"
        />

        <button
            type="submit"
            class="rounded bg-blue-600 px-4 py-2 text-white"
        >
            Search
        </button>
    </form>
</template>