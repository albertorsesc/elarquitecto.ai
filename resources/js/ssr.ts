import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { renderToString } from '@vue/server-renderer';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { type DefineComponent, createSSRApp, h } from 'vue';
import { route as ziggyRoute } from 'ziggy-js';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Add interfaces needed for typescript
declare global {
    interface Window {
        route: any;
    }
    namespace NodeJS {
        interface Global {
            route: any;
        }
    }
}

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - ${appName}`,
        resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
        setup({ App, props, plugin }) {
            const app = createSSRApp({ render: () => h(App, props) });

            // Configure Ziggy for SSR...
            const ziggyConfig = typeof page.props.ziggy === 'object' ? page.props.ziggy : {};
            
            // Create route function...
            const routeFunction = (name: string, params?: any, absolute?: boolean) => 
                ziggyRoute(name, params, absolute, ziggyConfig as any);

            // Make route function available globally...
            // @ts-ignore - Type definitions for Ziggy routes are complex
            app.config.globalProperties.route = routeFunction;

            // Make route function available globally for SSR...
            if (typeof window === 'undefined') {
                (globalThis as any).route = routeFunction;
            }

            app.use(plugin);

            return app;
        },
    }),
);
