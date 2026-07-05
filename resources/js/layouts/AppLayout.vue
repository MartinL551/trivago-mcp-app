<script setup lang="ts">
    import NavBar from '@/components/NavBar.vue';
    import FlashMessage from '@/components/SystemMessages/FlashMessage.vue';
</script>

<template>
    <div class="min-h-screen text-[var(--text)] bg-[var(--bg)]">
        <nav-bar />
        <FlashMessage />
        <main class="mx-auto max-w-7xl px-2 py-6">
            <slot />
        </main>
    </div>
</template>
