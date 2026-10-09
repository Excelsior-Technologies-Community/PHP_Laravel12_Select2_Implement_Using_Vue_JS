import './bootstrap';
import $ from 'jquery';
import select2 from 'select2';

if (typeof window.$ === 'undefined') {
    window.$ = window.jQuery = $;
}

if (typeof $.fn.select2 === 'undefined') {
    select2(window, $);
}

import 'select2/dist/css/select2.min.css';

import { createApp } from 'vue';
import App from './App.vue';

createApp(App).mount('#app');