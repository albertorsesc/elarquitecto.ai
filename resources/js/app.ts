import '../css/app.css';

import { createInertiaApp, Head } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createPinia } from 'pinia';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';

// Create Pinia instance
const pinia = createPinia();

// Extend ImportMeta interface for Vite...
declare global {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }
}

// Import the config
import config from '@/config';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Extract the base domain from the API URL for interceptor matching
const spotifyApiDomain = new URL(config.spotify.apiUrl).hostname;

// Fix for Spotify API errors
const originalFetch = window.fetch;
window.fetch = function(input, init) {
    // Check if this is a request to the problematic endpoint
    if (typeof input === 'string' && input.includes(spotifyApiDomain)) {
        // Check if this is a request to the event endpoint
        if (input.includes('/event/')) {
            console.log('Intercepting Spotify event API call:', input);
            // Return a mock successful response
            return Promise.resolve(new Response(JSON.stringify({ success: true }), {
                status: 200,
                headers: { 'Content-Type': 'application/json' }
            }));
        }
    }
    // Otherwise, proceed with the original fetch
    return originalFetch.apply(this, [input, init]);
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        console.log('Inertia app setup with props:', {
            hasProps: !!props,
            hasSeoData: props?.initialPage?.props?.seo ? true : false,
        });

        const app = createApp({ render: () => h(App, props) });
        app.use(plugin);
        app.use(ZiggyVue);
        app.use(pinia); // Use the pre-created Pinia instance

        // Register the Head component globally with a different name to avoid HTML collision
        app.component('InertiaHead', Head);

        console.log('Mounting Inertia app to element:', el);

        app.mount(el);

        console.log('Inertia app mounted successfully');

        return app;
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
