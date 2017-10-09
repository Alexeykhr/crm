<template>
    <v-app :class="guest ? 'auth' : ''">
        <template v-if="auth">
            <v-navigation-drawer persistent clipped enable-resize-watcher v-model="drawerRight" right light app>
                <v-list dense>
                    <v-list-tile @click.stop="drawerRight = !drawerRight">
                        <v-list-tile-action>
                            <v-icon>exit_to_app</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>Користувачі онлайн</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
            </v-navigation-drawer>

            <v-navigation-drawer persistent enable-resize-watcher clipped app :mini-variant.sync="mini" v-model="drawer">
                <v-toolbar flat class="transparent">
                    <v-list class="pa-0">
                        <v-list-tile avatar>
                            <v-list-tile-avatar>
                                <img :src="image" :alt="name" :title="name">
                            </v-list-tile-avatar>
                            <v-list-tile-content>
                                <v-list-tile-title>{{ name }}</v-list-tile-title>
                            </v-list-tile-content>
                            <v-list-tile-action>
                                <v-btn icon @click.native.stop="mini = !mini">
                                    <v-icon>chevron_left</v-icon>
                                </v-btn>
                            </v-list-tile-action>
                        </v-list-tile>
                    </v-list>
                </v-toolbar>

                <v-list class="pt-0" dense>
                    <v-divider></v-divider>
                    <template v-for="(item, i) in items">
                        <v-divider v-if="item.divider" dark class="my-2" :key="i"></v-divider>
                        <v-list-tile v-else :to="item.to" @click.native.stop="!!mini">
                            <v-list-tile-action>
                                <v-icon>{{ item.icon }}</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                                <v-list-tile-title>{{ item.text }}</v-list-tile-title>
                            </v-list-tile-content>
                            <v-list-tile-action v-if="item.subTo">
                                <v-tooltip bottom>
                                    <v-btn icon ripple :to="item.subTo" slot="activator">
                                        <v-icon>{{ item.subIcon }}</v-icon>
                                    </v-btn>
                                    <span>{{ item.subText }}</span>
                                </v-tooltip>
                            </v-list-tile-action>
                        </v-list-tile>
                    </template>
                </v-list>
            </v-navigation-drawer>

            <v-toolbar class="blue darken-3" dark app clipped-left clipped-right fixed>
                <v-toolbar-title style="width: 300px" class="ml-0 pl-3">
                    <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
                    Компанія
                </v-toolbar-title>
                <v-text-field solo prepend-icon="search" placeholder="Search"></v-text-field>
                <v-spacer></v-spacer>
                <v-tooltip bottom>
                    <v-btn @click="logout" icon slot="activator">
                        <v-icon>exit_to_app</v-icon>
                    </v-btn>
                    <span>Вийти</span>
                </v-tooltip>
                <v-tooltip bottom>
                    <v-btn icon slot="activator">
                        <v-icon>notifications</v-icon>
                    </v-btn>
                    <span>Повідомлення</span>
                </v-tooltip>
                <v-tooltip bottom>
                    <v-toolbar-side-icon @click.stop="drawerRight = !drawerRight" slot="activator"></v-toolbar-side-icon>
                    <span>{{ drawerRight ? 'Сховати' : 'Показати' }} онлайн</span>
                </v-tooltip>
            </v-toolbar>
            <main>
                <v-content>
                    <router-view></router-view>
                </v-content>
            </main>
        </template>

        <router-view v-else></router-view>
    </v-app>
</template>

<script>
    import io from 'socket.io-client';
    import Auth from './store/auth'
    import { post } from './helpers/api'

    var socket = io.connect('http://localhost:6379/');

    export default {
        data() {
            return {
                authState: Auth.state,

                drawer: true,
                mini: true,
                drawerRight: false,
                items: [
                    {
                        icon: 'dashboard', text: 'Головна сторінка', to: '/dashboard'
                    }, {
                        icon: 'account_circle', text: 'Профіль', to: '/profile',
                        subIcon: 'edit', subText: 'Налаштування', subTo: '/profile/edit'
                    }, {
                        icon: 'message', text: 'Чат', to: '/chat'
                    }, {
                        icon: 'description', text: 'Новини', to: '/news',
                        subIcon: 'add', subText: 'Додати', subTo: '/news/create'
                    }, {
                        icon: 'event', text: 'Календар', to: '/calendar',
                        subIcon: 'add', subText: 'Додати', subTo: '/event/create'
                    }, {
                        divider: true
                    }, {
                        icon: 'storage', text: 'Сховище', to: '/storage'
                    }, {
                        divider: true
                    }, {
                        icon: 'people', text: 'Користувачі', to: '/users',
                        subIcon: 'add', subText: 'Додати', subTo: '/user/create'
                    }, {
                        icon: 'stars', text: 'Ролі', to: '/roles',
                        subIcon: 'add', subText: 'Додати', subTo: '/role/create'
                    }, {
                        icon: 'local_offer', text: 'Посади', to: '/jobs',
                        subIcon: 'add',subText: 'Додати', subTo: '/job-create'
                    }, {
                        divider: true
                    }, {
                        icon: 'security', text: 'Журнал', to: '/logs'
                    }, {
                        icon: 'timeline', text: 'Статистика   ', to: '/statistics'
                    },
                ]
            }
        },
        mounted() {
            console.log('Mounted');
            console.log(Auth.state.token);
            if (Auth.state.token) {
                socket.on('connect', () => {
                    console.log('connect');
                    socket.emit('authenticate', {token: Auth.state.token});

                    socket.emit('public-my-message', {'msg': 'Hi, Every One.'});
                });

                socket.on('user-id', function (data) {
                    console.log('user-id');
                    console.log(data);
                });

                socket.on('receive-my-message', function (data) {
                    console.log('receive-my-message');
                    this.items = console.log(data);
                });
            }
        },
        computed: {
            auth() {
                return !!this.authState.token;
            },
            guest() {
                return !this.auth;
            },
            name() {
                return this.authState.user.first_name + ' ' + this.authState.user.last_name;
            },
            image() {
                return this.authState.user.image ? this.authState.user.image : '/img/user.png';
            },
        },
        methods: {
            logout() {
                Auth.remove();
                this.$router.push('login');
            }
        },
        watch: {
            auth() {
                console.log('Auth');
            }
        }
    }
</script>
