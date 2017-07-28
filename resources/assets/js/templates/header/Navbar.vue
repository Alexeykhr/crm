<template>
    <div class="phone-viewport" v-once>
        <md-whiteframe md-tag="md-toolbar" class="md-dense">
            <div class="md-toolbar-container">
                <md-button class="md-icon-button" @click="$refs.sidenav.toggle()">
                    <md-icon>menu</md-icon>
                </md-button>

                <md-button href="/">
                    <span class="h_firm">{{ firm }}</span>
                </md-button>

                <span style="flex: 1"></span>

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
        </md-whiteframe>

        <md-sidenav class="md-left" ref="sidenav">
            <md-list>
                <md-list-item href="/">
                    <md-icon>home</md-icon> <span>Головна сторінка</span>
                </md-list-item>

                <md-list-item href="/me">
                    <md-icon>account_circle</md-icon> <span>Мій профіль</span>
                </md-list-item>

                <md-list-item v-if="me.role.acs_user % 2 == 1" href="/u">
                    <md-icon>people</md-icon> <span>Користувачі</span>
                </md-list-item>

                <md-list-item v-if="me.role.acs_role % 2 == 1" href="/r">
                    <md-icon>label</md-icon> <span>Ролі</span>
                </md-list-item>

                <md-list-item v-if="me.role.acs_job % 2 == 1" href="/j">
                    <md-icon>star</md-icon> <span>Посади</span>
                </md-list-item>

                <hr>

                <md-list-item href="/f">
                    <md-icon>folder</md-icon> <span>Папки</span>
                </md-list-item>

                <hr>

                <md-list-item v-if="me.role.acs_log" href="/logs">
                    <md-icon>event_note</md-icon> <span>Журнал</span>
                </md-list-item>

                <md-list-item v-if="me.role.acs_birthday" href="/birthday">
                    <md-icon>event</md-icon> <span>Дні народження</span>
                </md-list-item>
            </md-list>
        </md-sidenav>
    </div>
</template>

<script>
    export default {
        props: [
            'iUser', 'firm',
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
