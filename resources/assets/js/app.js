import Vue from 'vue';

import App from './App.vue'
import router from './router/index';

require('./bootstrap');

window.Vue = require('vue');

const app = new Vue({
    el: '#app',
    template: '<app></app>',
    components: { App },
    router
});
