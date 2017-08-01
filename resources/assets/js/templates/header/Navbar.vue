<template>
    <div class="phone-viewport" v-once>
        <md-toolbar class="md-dense">
            <div class="md-toolbar-container">
                <md-button class="md-icon-button" @click="$refs.sidenav.toggle()">
                    <md-icon>menu</md-icon>
                </md-button>

                <md-button href="/" class="h_firm">
                    <span>{{ firm }}</span>
                </md-button>

                <span style="flex: 1"></span>

                <template v-if="btnTitle && btnHref">
                    <md-button :href="btnHref" :class="btnClass">
                        <md-icon v-if="btnIcon">{{ btnIcon }}</md-icon> <span class="h_btn">{{ btnTitle }}</span>
                    </md-button>
                </template>

                <md-menu md-align-trigger>
                    <md-button md-menu-trigger>
                        <span class="h_name">{{ me.name }}</span>
                        <md-avatar>
                            <img :src="me.photo ? me.photo : '/img/user.png'" :alt="'Користувач: ' + me.name">
                        </md-avatar>
                    </md-button>

                    <md-menu-content>
                        <md-menu-item href="/me">Профіль</md-menu-item>
                        <md-menu-item @click.pervent="logout()">Вихід</md-menu-item>
                    </md-menu-content>
                </md-menu>
            </div>
        </md-toolbar>

        <md-sidenav class="md-left" ref="sidenav">
            <md-list>
                <md-list-item href="/">
                    <md-icon>home</md-icon> <span>Головна сторінка</span>
                </md-list-item>

                <md-list-item href="/me">
                    <md-icon>account_circle</md-icon> <span>Мій профіль</span>
                </md-list-item>

                <md-list-item v-if="me.role.acs_user % 2 == 1" href="/users">
                    <md-icon>people</md-icon> <span>Користувачі</span>
                </md-list-item>

                <md-list-item v-if="me.role.acs_role % 2 == 1" href="/roles">
                    <md-icon>label</md-icon> <span>Ролі</span>
                </md-list-item>

                <md-list-item v-if="me.role.acs_job % 2 == 1" href="/jobs">
                    <md-icon>star</md-icon> <span>Посади</span>
                </md-list-item>

                <br>

                <md-list-item href="/folders">
                    <md-icon>folder</md-icon> <span>Папки</span>
                </md-list-item>

                <br>

                <md-list-item v-if="me.role.acs_log" href="/logs">
                    <md-icon>event_note</md-icon> <span>Журнал</span>
                </md-list-item>

                <md-list-item v-if="me.role.acs_calendar % 2 == 1" href="/calendar">
                    <md-icon>event</md-icon> <span>Календар</span>
                </md-list-item>
            </md-list>
        </md-sidenav>
    </div>
</template>

<script>
    export default {
        props: [
            'iUser', 'firm', 'btnHref', 'btnTitle', 'btnClass', 'btnIcon',
        ],

        data () {
            return {
                me: null,
            }
        },

        created () {
            this.me = JSON.parse(this.iUser);
        },

        methods: {
            logout() {
                axios.post('/logout')
                    .then(response => location.href = '/login' )
                    .catch(error => console.log(error) );
            }
        },
    }
</script>
