<script setup lang="ts">
    type Props = {
        googleLoginUrl: string;
    };

    const props = defineProps<Props>();
</script>

<template>
    <section class="flex min-h-[60vh] items-center justify-center py-10">
        <div class="w-full max-w-md rounded-2xl border border-[var(--border)] bg-[var(--surface-elevated)] p-6 shadow-[var(--card-shadow)]">
            <div class="mb-6 text-center">
                <h1 class="text-[var(--text)]">Welcome back</h1>
                <p class="mt-2 text-[var(--text-muted)]">
                    Sign in to continue planning your next stay.
                </p>
            </div>

            <div class="space-y-3">
                <a
                    :href="props.googleLoginUrl"
                    class="flex w-full items-center gap-3 rounded border border-[var(--border)] bg-[var(--surface)] px-4 py-3 font-medium text-[var(--text)] transition-colors hover:border-[var(--google)] hover:bg-[var(--google-soft)] hover:text-[var(--google-hover)]"
                >
                    <span
                        class="flex size-9 shrink-0 items-center justify-center rounded-full border border-[var(--google)] bg-[var(--surface)] text-lg font-bold text-[var(--google)]"
                    >
                        G
                    </span>

                    <span class="flex-1">
                        Continue with Google
                    </span>
                </a>
            </div>
        </div>
    </section>
</template>
