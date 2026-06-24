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
            title: 'Past Searches',
            href: 'search.show',
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
    <header class="border-b border-[var(--border)] flex flex-col md:flex-row items-center">
        <div class="px-4 py-4">
            <h3>Travel App</h3>
        </div>
       
        <nav class="mx-auto max-w-7xl px-4 py-4">
            <ul class="flex flex-col gap-2 md:flex-row md:gap-6">
                <li class="relative flex h-10 items-center gap-2 px-4" v-for="item in navItems" :key="item.href">
                    <a
                        :href="route(item.href)"
                    >
                        {{ item.title }}
                    </a>
                </li>
            </ul>
        </nav>

        <div class="px-4 py-4"> 
            <h5 class="mx-2 font-bold">{{ page.props.auth.user.name }}</h5>
        </div>
    </header>
</template>