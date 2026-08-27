import './bootstrap.js';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import vuetify from './vuetify';
//toast plugin
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css'; //theme-default - theme-bootstrap - theme-sugar

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue')
        return pages[`./Pages/${name}.vue`]()
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(vuetify)
            .use(ToastPlugin)
            .component('Link', Link)
            .mount(el)
    },
})