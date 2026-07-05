<script setup lang="ts">
    import { Link, usePage } from '@inertiajs/vue3';
    import { Circle } from 'lucide-vue-next';
    import { route } from 'ziggy-js';
    import { logout } from '@/routes';
    import type { NavItem } from '@/types';

    const page = usePage();

    const navItems: Array<NavItem<string>> = [
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
    <header class="flex flex-col items-center gap-4 border-b border-[var(--border)] px-4 py-5 text-center md:flex-row md:items-center md:gap-0 md:px-0 md:py-0 md:text-left">
        <div class="md:p-4">
            <h3 class="text-[var(--text)]">{{ page.props.app_name }}</h3>
        </div>
       
        <nav class="w-full md:mx-auto md:w-auto md:max-w-7xl md:p-4">
            <ul class="flex flex-col items-center gap-3 md:flex-row md:gap-3">
                <li class="w-full md:w-auto" v-for="item in navItems" :key="item.href">
                    <a
                        :href="route(item.href)"
                        class="flex min-h-11 w-full items-center justify-center rounded border border-[var(--border)] bg-[var(--surface)] px-4 py-2 font-medium text-[var(--text)] transition-colors hover:border-[var(--nav-hover-border)] hover:bg-[var(--surface-hover)] hover:text-[var(--primary-hover)] md:w-auto"
                    >
                        {{ item.title }}
                    </a>
                </li>
            </ul>
        </nav>

        <div class="flex flex-wrap items-center justify-center gap-3 md:flex-nowrap md:p-4">
            <Link
                :href="logout.url()"
                method="post"
                as="button"
                class="shrink-0 rounded border border-[var(--border)] px-3 py-1.5 text-sm font-medium text-[var(--text-muted)] transition-colors hover:border-[var(--nav-hover-border)] hover:text-[var(--text)]"
            >
                Logout
            </Link>

            <h5 class="font-bold text-[var(--text)]">{{ page.props.auth.user.name }}</h5>
        </div>
    </header>
</template>
