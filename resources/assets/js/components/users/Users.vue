<template>
    <md-table v-once>
        <md-table-header>
            <md-table-row>
                <md-table-head>Користувач / Логін</md-table-head>
                <md-table-head>П.І.Б.</md-table-head>
                <md-table-head>Контакти</md-table-head>
            </md-table-row>
        </md-table-header>

        <md-table-body>
            <md-table-row v-for="user in users.data" :key="user.id">
                <md-table-cell>
                    <img :src="user.photo ? user.photo : 'img/user.png'">

                    <template v-if="user.active">
                        {{ user.nick }}
                        {{ '['+user.role.title+']' }}
                    </template>

                    <template v-else>
                        No
                    </template>
                </md-table-cell>

                <md-table-cell>
                    {{ user.name }}
                </md-table-cell>

                <md-table-cell>
                    <span class="user_phone" v-if="user.phone"><md-icon>call</md-icon> {{ user.phone }}</span>
                </md-table-cell>
            </md-table-row>
        </md-table-body>
    </md-table>
</template>

<script>
    export default {
        props: [
            'data'
        ],

        data () {
            return {
                users: null,
            }
        },

        created () {
            this.users = JSON.parse(this.data);

            console.log(this.users.data);
        },
    }
</script>
