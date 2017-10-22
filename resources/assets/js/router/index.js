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

// Vue router
const router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/', redirect: { name: 'dashboard' } },
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
    let token = window.localStorage.getItem('token')

    const originalRequest = err.config

    if (err.response.status === 401 && !originalRequest._retry) {
        if (! token) {
            router.push({ name: 'login' })
        } else {
            return get('/api/refresh-token')
                .then(res => {
                    if (res.data.token) {
                        window.localStorage.setItem('token', res.data.token)
                        originalRequest.headers['Authorization'] = 'Bearer ' + res.data.token
                        return axios(originalRequest)
                    }
                })
                .catch(err => {
                    window.localStorage.removeItem('token')
                    router.push({ name: 'login' })
                })
        }
    } else if (err.response.status === 404) {
        router.push({ name: 'not-found' })
    }

    return Promise.reject(err)
})

// Protect router
router.beforeEach((to, from, next) => {
    if (! AuthStore.state.token && to.name !== 'login') {
        next({ name: 'login' })
    } else if (AuthStore.state.token && to.path === '/login') {
        next({ name: 'dashboard' })
    } else {
        next()
    }
})

export default router
