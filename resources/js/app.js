import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import moment from 'moment'
import Vuex from 'vuex'
import store from './store';

moment.locale('pt-br');

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(Vuex)

        store.dispatch('userStateAction')
        app.config.globalProperties.$filters = {
            formatDate(value) {
                return moment(value).format('DD/MM/YYYY HH:mm')
            }
        }
        return app.mount(el)
    },
    progress: {
        color: '#4B5563',
    },
});
