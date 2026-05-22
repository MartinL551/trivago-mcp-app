import { createInertiaApp } from '@inertiajs/vue3';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import PublicLayout from './layouts/PublicLayout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (name) {
            case 'LandingPage':
            case 'auth/Login':
                return PublicLayout;

            default:
                return AppLayout;
        }
    },

});

// This will set light / dark mode on page load...
initializeTheme();
