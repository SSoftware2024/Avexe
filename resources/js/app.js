import './bootstrap.js';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import vuetify from './vuetify';
//css json editor - package: json-editor-vue
import "vanilla-jsoneditor/themes/jse-theme-dark.css";
//toast plugin: 
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css'; //theme-default - theme-bootstrap - theme-sugar
//input money mask - package: v-money3
import money from 'v-money3'
import { Money3Component } from 'v-money3'

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
            .use(money)
            .component('Link', Link)
            .component('v-input-money', Money3Component)
            .mount(el)
    },
})