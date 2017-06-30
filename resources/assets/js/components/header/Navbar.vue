<template>
    <md-whiteframe md-tag="md-toolbar" md-elevation="2">
        <div class="md-toolbar-container">
            <md-button href="/">
                Crew Resource Management
            </md-button>

            <span style="flex: 1"></span>

            <md-button class="md-icon-button">
                <md-icon>view_module</md-icon>
            </md-button>

            <md-menu md-align-trigger>
                <md-button md-menu-trigger>{{ nick }}
                    <md-avatar>
                        <img :src="photo ? photo : '/img/user.png'" :alt="'Користувач ' + nick">
                    </md-avatar>
                </md-button>

                <md-menu-content>
                    <md-menu-item href="/profile">Профіль</md-menu-item>
                    <md-menu-item @click.pervent="logout()">Вихід</md-menu-item>
                </md-menu-content>
            </md-menu>
        </div>
    </md-whiteframe>
</template>

<script>
    export default {
        props: [
            'nick', 'photo'
        ],

        data () {
            return {
                
            }
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
