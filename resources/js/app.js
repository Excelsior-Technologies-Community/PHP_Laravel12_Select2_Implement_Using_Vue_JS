import './bootstrap';

import { createApp } from 'vue';
import App from './App.vue';

import $ from 'jquery';
window.$ = window.jQuery = $;

import 'select2';
import 'select2/dist/css/select2.min.css';

createApp(App).mount('#app');