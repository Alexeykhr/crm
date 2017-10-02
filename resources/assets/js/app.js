import Vue from 'vue';
import Vuetify from 'vuetify';

import App from './App.vue'
import router from './router/index';

require('./bootstrap');

// window.Vue = require('vue');
Vue.use(Vuetify);

const app = new Vue({
    el: '#app',
    template: '<app></app>',
    components: { App },
    router
});
