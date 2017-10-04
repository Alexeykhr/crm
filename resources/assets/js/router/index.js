import Vue from 'vue';
import VueRouter from 'vue-router';

import Login from '../views/auth/Login.vue';
import NotFound from '../views/NotFound.vue';

import Users from '../views/users/Users.vue';
import Profile from '../views/users/Profile.vue';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/', component: Login },
        { path: '/dashboard', component: Users }, // Temporary
        { path: '/users', component: Users },
        { path: '/profile', component: Profile },
        { path: '*', component: NotFound },
    ]
});

export default router;
