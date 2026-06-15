import type { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export type NavItem = {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
};
