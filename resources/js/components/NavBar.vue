<script setup lang="ts">
    import { usePage } from '@inertiajs/vue3';
    import { Circle } from 'lucide-vue-next';
    import { route } from 'ziggy-js';
    import type { NavItem } from '@/types';



    const page = usePage();

    const navItems: Array<NavItem> = [
        {
            title: 'Search',
            icon: Circle,
            href: 'search.index',
        },
        {
            title: 'Last Result',
            href: 'results.index',
        },
        {
            title: 'Wishlist',
            icon: Circle,
            href: 'wishlist.show',
        },
        {
            title: 'Manage Account',
            icon: Circle,
            href: 'profile.edit',
        },
    ];
</script>

<template>
    <header class="shadow">
        <span class="font-bold">Travel App</span>
        <span class="mx-2 font-bold">{{ page.props.auth.user.name }}</span>
        <nav class="mx-auto max-w-7xl px-4 py-4">
            <ul>
                <li v-for="item in navItems" :key="item.href">
                    <a
                        :href="route(item.href)"
                    >
                        {{ item.title }}
                    </a>
                </li>
            </ul>
        </nav>
    </header>
</template>