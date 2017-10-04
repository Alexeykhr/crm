<template>
    <div>
        <v-app v-if="this.authState.token && this.authState.user" light toolbar>
            <v-navigation-drawer hide-overlay persistent clipped :mini-variant.sync="mini" v-model="drawer">
                <v-toolbar flat class="transparent">
                    <v-list class="pa-0">
                        <v-list-tile avatar>
                            <v-list-tile-avatar>
                                <img src="https://randomuser.me/api/portraits/men/85.jpg" />
                            </v-list-tile-avatar>
                            <v-list-tile-content>
                                <v-list-tile-title>John Leider</v-list-tile-title>
                            </v-list-tile-content>
                            <v-list-tile-action>
                                <v-btn icon @click.native.stop="mini = !mini">
                                    <v-icon>chevron_left</v-icon>
                                </v-btn>
                            </v-list-tile-action>
                        </v-list-tile>
                    </v-list>
                </v-toolbar>
                <v-list class="pt-0" @click.native.stop="mini = true">
                    <v-divider></v-divider>
                    <v-list-tile v-for="item in items" :key="item.title" :to="item.href">
                        <v-list-tile-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
            </v-navigation-drawer>
            <v-toolbar fixed dark>
                <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
                <v-toolbar-title>CRM</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon>
                    <v-icon>search</v-icon>
                </v-btn>
                <v-btn icon>
                    <v-icon>notifications_none</v-icon>
                </v-btn>
                <v-btn icon>
                    <v-icon>more_vert</v-icon>
                </v-btn>
            </v-toolbar>
            <main>
                <v-container fluid>
                    <router-view></router-view>
                </v-container>
            </main>
        </v-app>

        <template v-else>
            <router-view></router-view>
        </template>
    </div>
</template>

<script>
    import Auth from './store/auth'
    import Flash from './helpers/flash'
    import { post, interceptors } from './helpers/api'

    export default {
        data() {
            return {
                authState: Auth.state,
                flash: Flash.state,

                drawer: true,
                items: [
                    { title: 'Головна сторінка', href: '/dashboard', icon: 'dashboard' },
                    { title: 'Профіль', href: '/profile', icon: 'perm_identity' },
                    { title: 'Чат', href: '/char', icon: 'chat' },
                    { title: 'Новини', href: '/news', icon: 'book' },
                    { title: 'Користувачі', href: '/users', icon: 'people' },
                    { title: 'Посади', href: '/jobs', icon: 'work' },
                    { title: 'Сховище', href: '/dir', icon: 'storage' },
                    { title: 'Календар', href: '/calendar', icon: 'event' },
                    { title: 'Журнал', href: '/logs', icon: 'history' },
                    { title: 'Статистика', href: '/statistics', icon: 'insert_chart' },
                ],
                mini: true,
            }
        },
        created() {
            interceptors(err => {
                if (err.response.status === 401) {
                    Auth.remove();
                    this.$router.push('/login');
                }
                else if (err.response.status === 500) {
                    Flash.setError(err.response.statusText);
                }
                else if (err.response.status === 404) {
                    this.$router.push('/not-found');
                }
            });

            Auth.initialize();

            if (! this.authState.token) {
                this.$router.push('/login');
            }
        },
        computed: {
            auth() {
                return !!this.authState.token;
            },
            guest() {
                return !this.auth;
            }
        },
        methods: {
            logout() {
                post('/api/logout')
                    .then(res => {
                        if (res.data.done) {
                            // remove token
                            Auth.remove();
                            Flash.setSuccess('You have successfully logged out.');
                            this.$router.push('/login');
                        }
                    })
            }
        }
    }
</script>
