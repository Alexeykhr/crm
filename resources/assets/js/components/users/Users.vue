<template>
    <md-layout md-flex-xsmall="100" md-flex-small="50" md-flex-medium="100" md-align="center">
        <form novalidate @submit.stop.prevent="submit">
            <md-input-container>
                <label>Autocomplete (with fetch)</label>
                <md-input v-model="autocompleteValue" :fetch="fetchFunction(autocompleteValue)"></md-input>
            </md-input-container>
        </form>

        <md-table class="users_table" v-once>
            <md-table-header>
                <md-table-row>
                    <md-table-head>Фото</md-table-head>
                    <md-table-head>Користувач</md-table-head>
                    <md-table-head>Роль</md-table-head>
                    <md-table-head>Контакти</md-table-head>
                </md-table-row>
            </md-table-header>

            <md-table-body>
                <md-table-row v-for="user in users.data" :key="user.id">
                    <md-table-cell>
                        <img :src="user.photo ? user.photo : 'img/user.png'" :title="'Користувач: ' + user.name"
                             :atl="'Користувач: ' + user.name">
                    </md-table-cell>

                    <md-table-cell>
                        <b>{{ user.name }}</b><br>
                        <template v-if="user.active">
                            {{ user.nick }}
                        </template>

                        <template v-else>
                            Немає доступу
                        </template>
                    </md-table-cell>

                    <md-table-cell>
                        <md-chip v-if="user.active" :style="'color:#fff;background:' + user.role.color">{{ user.role.title }}</md-chip>
                    </md-table-cell>

                    <md-table-cell>
                        <md-menu md-align-trigger md-size="5">
                            <md-button class="md-icon-button" md-menu-trigger><md-icon>contact_mail</md-icon></md-button>

                            <md-menu-content>
                                <md-menu-item :href="'tel:+' + user.phone.replace(/\D/g,'')" v-if="user.phone">
                                    <md-icon>phone</md-icon>
                                    <span>{{ user.phone }}</span>
                                </md-menu-item>

                                <md-menu-item :href="'tel:+' + user.work_phone.replace(/\D/g,'')" v-if="user.work_phone">
                                    <md-icon>phone</md-icon>
                                    <span>{{ user.work_phone }}</span>
                                </md-menu-item>

                                <md-menu-item :href="'mailto:' + user.email" v-if="user.email">
                                    <md-icon>email</md-icon>
                                    <span>{{ user.email }}</span>
                                </md-menu-item>

                                <md-menu-item :href="'mailto:' + user.work_email" v-if="user.work_email">
                                    <md-icon>email</md-icon>
                                    <span>{{ user.work_email }}</span>
                                </md-menu-item>
                            </md-menu-content>
                        </md-menu>
                    </md-table-cell>
                </md-table-row>
            </md-table-body>

            <md-button href="/users/create" class="md-fab btn_fixed">
                <md-icon>add</md-icon>
            </md-button>
        </md-table>
    </md-layout>
</template>

<script>
    export default {
        props: [
            'data',
        ],

        data () {
            return {
                users: null,
                initialValue: 'My initial value',
                autocompleteValue: null,
            }
        },

        created () {
            this.users = JSON.parse(this.data);
        },

        // Temporary
        methods: {
            fetchFunction(param) {
                if (param === null)
                    return;

                for (let i = 0; i < this.users.total; i++) {
                    if (this.users.data[i].name.toLowerCase() === param.toLowerCase()) {
                        console.log(this.users.data[i].name);
                    }
                }
            },
        },
    }
</script>

<style>
    form {
        width: 100%;
    }
</style>
