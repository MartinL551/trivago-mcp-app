import type { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export type NavItem<THref = NonNullable<InertiaLinkProps['href']>> = {
    title: string;
    href: THref;
    icon?: LucideIcon;
    isActive?: boolean;
};

export type BreadcrumbItem = {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
};
