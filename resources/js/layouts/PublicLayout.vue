<script setup lang="ts">
</script>

<template>
    <div class="min-h-screen bg-white text-black dark:bg-gray-900 dark:text-white">
        <header class="shadow">
            <nav class="mx-auto max-w-7xl px-4 py-4">
                <span class="font-bold">Travel App</span>
                <button class="mx-2 border" href="/login">
                    Login
                </button>
            </nav>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-6">
            <slot />
        </main>
    </div>
</template>