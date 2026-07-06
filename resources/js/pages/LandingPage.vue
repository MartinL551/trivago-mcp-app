<script setup lang="ts">
    import { usePage } from '@inertiajs/vue3';

    const page = usePage();
</script>

<template>
    <section class="py-12 md:py-20">
        <div class="mx-auto max-w-3xl text-center">
            <p class="mb-3 text-sm font-semibold uppercase tracking-wide text-[var(--primary)]">
                AI-powered hotel search
            </p>

            <h1 class="text-[var(--text)]">
                Find stays that match how you actually travel
            </h1>

            <p class="mt-4 text-lg text-[var(--text-muted)]">
                Describe the trip you have in mind, and {{ page.props.app_name }} turns it into hotel ideas you can review, save, and revisit later.
            </p>

            <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                <a
                    href="/login"
                    class="inline-flex items-center justify-center rounded border border-[var(--button-card-border)] bg-[var(--button-card-bg)] px-5 py-3 font-medium text-[var(--button-card-text)] transition-colors hover:bg-[var(--button-card-bg-hover)] hover:text-[var(--button-card-text-hover)]"
                >
                    Login to start searching
                </a>

                <span class="text-sm text-[var(--text-muted)]">
                    Sign in with Google in a few seconds.
                </span>
            </div>
        </div>
    </section>

    <section class="grid gap-5 pb-12 md:grid-cols-3">
        <article class="rounded border border-[var(--card-border)] bg-[var(--card-bg)] p-5 shadow-[var(--card-shadow)]">
            <h3 class="text-[var(--text)]">Search naturally</h3>
            <p class="mt-2 text-[var(--text-muted)]">
                Ask for what matters: location, vibe, budget, dates, amenities, or the kind of trip you want.
            </p>
        </article>

        <article class="rounded border border-[var(--card-border)] bg-[var(--card-bg)] p-5 shadow-[var(--card-shadow)]">
            <h3 class="text-[var(--text)]">Compare results</h3>
            <p class="mt-2 text-[var(--text-muted)]">
                Review matched hotels in one place, with the important details kept easy to scan.
            </p>
        </article>

        <article class="rounded border border-[var(--card-border)] bg-[var(--card-bg)] p-5 shadow-[var(--card-shadow)]">
            <h3 class="text-[var(--text)]">Save favourites</h3>
            <p class="mt-2 text-[var(--text-muted)]">
                Keep a wishlist of places you like, then come back when you are ready to decide.
            </p>
        </article>
    </section>
</template>
