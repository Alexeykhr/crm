
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Vue.use(require('vue-material'));

window.Vue.material.registerTheme({
    default: {
        primary: {
            color: 'light-blue',
            hue: 700
        },
        accent: 'red'
    }
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('paginate', require('./components/parts/Pagination.vue'));

Vue.component('login', require('./components/auth/Login.vue'));
Vue.component('navbar', require('./components/header/Navbar.vue'));

Vue.component('users', require('./components/users/Index.vue'));
Vue.component('profile', require('./components/users/Profile.vue'));
Vue.component('user-create', require('./components/users/Create.vue'));

Vue.component('logs', require('./components/logs/Index.vue'));

const app = new Vue({
    el: '#app'
});
