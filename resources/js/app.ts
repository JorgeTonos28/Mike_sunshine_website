import "../css/app.css";
import "../css/store.css";
import "../css/admin.css";
import "../css/refinements.css";
import "../css/paint-cursor.css";
import "../css/home-motion.css";
import "../css/about.css";
import "../css/mixer-scrollbar.css";
import { createApp, h, Fragment, type DefineComponent } from "vue";
import MixerScrollbar from "./components/MixerScrollbar.vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";

createInertiaApp({
    title: (title) => `${title} | Mike Sunshine`,
    resolve: (name) =>
        resolvePageComponent<DefineComponent>(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>("./pages/**/*.vue"),
        ),
    setup({ el, App, props, plugin }) {
        createApp({
            render: () => h(Fragment, [h(App, props), h(MixerScrollbar)]),
        })
            .use(plugin)
            .mount(el);
    },
    progress: { color: "#d9ad62", includeCSS: false },
});
