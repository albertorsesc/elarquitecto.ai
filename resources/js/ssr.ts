import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { renderToString } from '@vue/server-renderer';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createSSRApp, h } from 'vue';
import { route as ziggyRoute } from 'ziggy-js';
import type { SharedData } from './types';

declare global {
    interface Window {
        route: typeof ziggyRoute;
    }
}

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - ${appName}`,
        resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
        setup({ App, props, plugin }) {
            const app = createSSRApp({ render: () => h(App, props) });

            // Configure Ziggy for SSR...
            const ziggyConfig = {
                ...(page.props as unknown as SharedData).ziggy,
                location: new URL((page.props as unknown as SharedData).ziggy.url),
            };

            // Create route function...
            const route = function(...args: any[]) {
                if (args.length === 0) {
                    return ziggyRoute(undefined, undefined, undefined, ziggyConfig);
                }

                const [name, params, absolute] = args;
                return ziggyRoute(name, params, absolute, ziggyConfig);
            };

            // Make route function available globally...
            app.config.globalProperties.route = route as typeof ziggyRoute;

            // Make route function available globally for SSR...
            if (typeof window === 'undefined') {
                (global as any).route = route;
            }

            app.use(plugin);

            return app;
        },
    }),
);
