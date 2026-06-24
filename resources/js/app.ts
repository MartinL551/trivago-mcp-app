import { createInertiaApp } from '@inertiajs/vue3';
import { createApp, h } from 'vue';
import { ZiggyVue, type Config as ZiggyConfig } from 'ziggy-js';
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
    setup({el, App, props, plugin}) {
            if (!el) {
                throw new Error('Inertia mount element not found');
            }

            createApp({ render: () => h(App, props) })
                .use(plugin)
                .use(ZiggyVue, props.initialPage.props.ziggy as ZiggyConfig)
                .mount(el);
    }
});

// This will set light / dark mode on page load...
initializeTheme();
