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
                            <v-list-tile-title>Hide live</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
            </v-navigation-drawer>

            <v-navigation-drawer persistent enable-resize-watcher disable-route-watcher clipped app
                                 :mini-variant.sync="mini" v-model="drawer">
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
                        <v-divider v-if="item.divider" dark class="my-4" :key="i"></v-divider>
                        <v-list-tile v-else :to="item.to">
                            <v-list-tile-action >
                                <v-icon>{{ item.icon }}</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                                <v-list-tile-title>{{ item.text }}</v-list-tile-title>
                            </v-list-tile-content>
                            <v-list-tile-action>
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
                    Bloody Swords
                </v-toolbar-title>
                <v-text-field solo prepend-icon="search" placeholder="Search"></v-text-field>
                <v-spacer></v-spacer>
                <v-btn @click="logout" icon>
                    <v-icon>exit_to_app</v-icon>
                </v-btn>
                <v-btn icon> <!-- TODO: ..-->
                    <v-icon>notifications</v-icon>
                </v-btn>
                <v-toolbar-side-icon @click.stop="drawerRight = !drawerRight"></v-toolbar-side-icon>
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
    import Auth from './store/auth'
    import { post, interceptors } from './helpers/api'

    export default {
        data() {
            return {
                authState: Auth.state,

                drawer: true,
                mini: true,
                drawerRight: true,
                items: [
                    { icon: 'dashboard', text: 'Dashboard', to: 'dashboard' },
                    { icon: 'account_circle', text: 'Profile', to: 'profile',
                        subIcon: 'edit', subText: 'Settings', subTo: 'edit' },
                    { icon: 'message', text: 'Chat', to: 'chat' },
                    { icon: 'description', text: 'News', to: 'news',
                        subIcon: 'add', subText: 'Add news', subTo: 'news-create' },
                    { icon: 'event', text: 'Calendar', to: 'calendar',
                        subIcon: 'add', subText: 'Add event', subTo: 'event-create' },
                    { divider: true },
                    { icon: 'storage', text: 'Storage', to: 'storage' },
                    { divider: true },
                    { icon: 'people', text: 'Users', to: 'users',
                        subIcon: 'add', subText: 'Add user', subTo: 'user-create' },
                    { icon: 'stars', text: 'Roles', to: 'roles',
                        subIcon: 'add', subText: 'Add role', subTo: 'role-create' },
                    { icon: 'local_offer', text: 'Jobs', to: 'jobs',
                        subIcon: 'add',subText: 'Add job', subTo: 'job-create' },
                    { divider: true },
                    { icon: 'security', text: 'Logs', to: 'logs' },
                    { icon: 'timeline', text: 'Statistics', to: 'statistics' },
                ]
            }
        },
        computed: {
            auth() {
                return !!this.authState.token && !!this.authState.user;
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
        }
    }
</script>
