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
import Users from '../views/users/Users.vue';
import Profile from '../views/users/Profile.vue';
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
        { path: '/profile', name: 'profile', component: Profile },
        { path: '/user/:id', name: 'user', component: Profile },
        { path: '*', component: NotFound },
    ]
});

// Axios global interceptors
axios.interceptors.response.use(null, err => {
    if (err.response.status === 401) {
        AuthStore.remove();
        router.push('login');
    }

    return Promise.reject(err);
});

// Protect router
router.beforeEach((to, from, next) => {
    if (! AuthStore.state.token && to.name !== 'login') {
        next({ name: 'login' });
    } else if (to.path === '/') {
        next({ name: 'dashboard' });
    } else if (to.name === 'user' && to.params.id == AuthStore.state.user.id) {
        next({ name: 'profile' });
    } else {
        next();
    }
});

export default router;
