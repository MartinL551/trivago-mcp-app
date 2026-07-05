<script setup lang="ts">
    import AppFooter from '@/components/AppFooter.vue';
    import NavBar from '@/components/NavBar.vue';
    import FlashMessage from '@/components/SystemMessages/FlashMessage.vue';
</script>

<template>
    <div class="flex min-h-screen flex-col bg-[var(--bg)] text-[var(--text)]">
        <nav-bar />
        <FlashMessage />
        <main class="mx-auto w-full max-w-7xl flex-1 px-2 py-6">
            <slot />
        </main>
        <AppFooter />
    </div>
</template>
