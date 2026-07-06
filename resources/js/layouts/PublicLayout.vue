<script setup lang="ts">
    import { usePage } from '@inertiajs/vue3';
    import AppFooter from '@/components/AppFooter.vue';
    import NavBar from '@/components/NavBar.vue';
    import FlashMessage from '@/components/SystemMessages/FlashMessage.vue';

    const page = usePage();
</script>

<template>
    <div class="flex min-h-screen flex-col bg-[var(--bg)] text-[var(--text)]">
        <NavBar v-if="page.props.auth.user" />

        <header v-else class="border-b border-[var(--border)] bg-[var(--surface)]">
            <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
                <a href="/" class="text-2xl font-semibold leading-none text-[var(--text)]">
                    {{ page.props.app_name }}
                </a>

                <a
                    class="inline-flex items-center justify-center rounded border border-[var(--border)] px-3 py-1.5 text-sm font-medium text-[var(--text-muted)] transition-colors hover:border-[var(--nav-hover-border)] hover:text-[var(--text)]"
                    href="/login"
                >
                    Login
                </a>
            </nav>
        </header>

        <FlashMessage />

        <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-6">
            <slot />
        </main>

        <AppFooter />
    </div>
</template>
