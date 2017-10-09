// Vue libs
import Vue from 'vue';
import VueRouter from 'vue-router';

// Other libs
import axios from 'axios';

// Store
import AuthStore from '../store/auth';

// Vue views
import Login from '../views/auth/Login.vue';
import Dashboard from '../views/Dashboard.vue';
import UserEdit from '../views/users/Edit.vue';
import User from '../views/users/User.vue';
import Users from '../views/users/Users.vue';
import NotFound from '../views/NotFound.vue';

// Vue use
Vue.use(VueRouter);

// Auth store set
AuthStore.initialize();

// Vue router
const router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/login', name: 'login', component: Login },
        { path: '/dashboard', name: 'dashboard', component: Dashboard },
        { path: '/users', name: 'users', component: Users },
        { path: '/user/:id/edit', name: 'user-edit', component: UserEdit, meta: { isAdmin: true } },
        { path: '/user/:id', name: 'user', component: User },
        { path: '*', name: 'not-found', component: NotFound },
    ]
});

// Axios global interceptors
axios.interceptors.response.use(null, err => {
    if (err.response.status === 401) {
        AuthStore.remove();
        router.push({ name: 'login' });
    } else if (err.response.status === 404) {
        router.push({ name: 'not-found' });
    }

    return Promise.reject(err);
});

// Protect router
router.beforeEach((to, from, next) => {
    if (! AuthStore.state.token && to.name !== 'login') {
        next({ name: 'login' });
    } else if (AuthStore.state.token && to.path === '/login') {
        next({ name: 'dashboard' });
    } else if (to.path === '/') {
        next({ name: 'dashboard' });
    } else if (to.path === '/profile') {
        next({ path: '/user/' + AuthStore.state.user.id });
    } else if (to.path === '/profile/edit') {
        next({ path: '/user/' + AuthStore.state.user.id + '/edit' });
    } else {
        next();
    }
});

export default router;
