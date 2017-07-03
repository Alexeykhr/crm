<template>
    <md-whiteframe md-tag="md-toolbar" md-elevation="0">
        <div class="md-toolbar-container">
            <md-button href="/">
                <span class="h_firm">{{ firm }}</span>
            </md-button>

            <span style="flex: 1"></span>

            <md-menu md-align-trigger>
                <md-button md-menu-trigger>
                    <span class="h_name">{{ name }}</span>
                    <md-avatar>
                        <img :src="photo ? photo : '/img/user.png'" :alt="'Користувач ' + name">
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
            'name', 'photo', 'firm'
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
