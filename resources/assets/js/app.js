// Other libs
window._ = require('lodash');
window.$ = window.jQuery = require('jquery');
window.axios = require('axios');

// Other config
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Vue libs
import Vue from 'vue';
import Vuetify from 'vuetify';
import VueSocketIO from 'vue-socket.io';

// Vue views
import App from './App.vue'

// Vue files
import router from './router/index';

// Vue use
Vue.use(Vuetify);
// Vue.use(VueSocketIO, 'http://localhost:6379/');

const app = new Vue({
    // sockets: {
    //     connect() {
    //         console.log('socket connected');
    //         this.$socket.emit('message', '123');
    //     },
    //     customEmit(val) {
    //         console.log('this method was fired by the socket server. eg: io.emit("customEmit", data)');
    //         console.log(val);
    //     }
    // },
    el: '#app',
    template: '<app></app>',
    components: {  App },
    router
});
