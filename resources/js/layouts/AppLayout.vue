<script setup lang="ts">
    import { usePage } from '@inertiajs/vue3'
    import { route } from 'ziggy-js';

    const page = usePage();

    const navItems = [
            {
                label: 'Search',
                route: 'search.index',
            },
            {
                label: 'Last Result',
                route: 'results.index',
            },
            // {
            //     label: 'Wishlist',
            //     route: 'wishlist.index',
            // },
        ];
</script>

<template>
    <div class="min-h-screen bg-white text-black dark:bg-gray-900 dark:text-white">
        <header class="shadow">
            <span class="font-bold">Travel App</span>
            <span class="mx-2 font-bold">{{ page.props.auth.user.name }}</span>
            <nav class="mx-auto max-w-7xl px-4 py-4">
                <ul>
                    <li v-for="item in navItems" :key="item.route">
                        <a
                            :href="route(item.route)"
                        >
                            {{ item.label }}
                        </a>
                    </li>
                </ul>
            </nav>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-6">
            <slot />
        </main>
    </div>
</template>