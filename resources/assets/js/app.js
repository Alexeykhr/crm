
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

Vue.component('login', require('./templates/auth/Login.vue'));

Vue.component('navbar', require('./templates/header/Navbar.vue'));
Vue.component('pagination', require('./templates/parts/Pagination.vue'));

Vue.component('users', require('./templates/users/Index.vue'));
Vue.component('profile', require('./templates/users/Profile.vue'));
Vue.component('users-create', require('./templates/users/Create.vue'));

Vue.component('roles', require('./templates/roles/Index.vue'));
Vue.component('roles-page', require('./templates/roles/Page.vue'));

Vue.component('jobs', require('./templates/jobs/Index.vue'));
Vue.component('jobs-page', require('./templates/jobs/Page.vue'));

Vue.component('folders', require('./templates/folders/Index.vue'));

Vue.component('logs', require('./templates/logs/Index.vue'));
Vue.component('calendar', require('./templates/calendar/Index.vue'));

const app = new Vue({
    el: '#app'
});
