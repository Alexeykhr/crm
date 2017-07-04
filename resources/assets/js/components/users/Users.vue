<template>
    <md-layout class="users_page" md-flex-xsmall="100" md-flex-small="50" md-flex-medium="100" md-align="center">
        <form novalidate @submit.stop.prevent="submit">
            <md-input-container>
                <md-icon>search</md-icon>
                <label>Пошук працівника</label>
                <md-input v-model="q" :fetch="search()"></md-input>
            </md-input-container>
        </form>

        <div class="pagination">
            <md-button :href="users.prev_page_url ? users.path+'?page=1' : ''" class="md-icon-button md-raised md-dense">
                <md-icon>first_page</md-icon>
            </md-button>
            <md-button :href="users.prev_page_url" class="md-icon-button md-raised md-dense">
                <md-icon>keyboard_arrow_left</md-icon>
            </md-button>

            <md-button class="md-dense">{{ users.from + '/' + users.to }}</md-button>

            <md-button :href="users.next_page_url" class="md-icon-button md-raised md-dense">
                <md-icon>keyboard_arrow_right</md-icon>
            </md-button>
            <md-button :href="users.next_page_url ? users.path+'?page='+users.last_page : ''" class="md-icon-button md-raised md-dense">
                <md-icon>last_page</md-icon>
            </md-button>
        </div>

        <md-table v-once>
            <md-table-header>
                <md-table-row>
                    <md-table-head>Фото</md-table-head>
                    <md-table-head>Користувач</md-table-head>
                    <md-table-head>Посада</md-table-head>
                    <md-table-head>Роль</md-table-head>
                    <md-table-head>Контакти</md-table-head>
                    <md-table-head></md-table-head>
                </md-table-row>
            </md-table-header>

            <md-table-body>
                <md-table-row v-for="user in users.data" :key="user.id">
                    <md-table-cell>
                        <md-avatar>
                            <!-- Temporary-->
                            <img :src="user.photo ? user.photo : 'https://randomuser.me/api/portraits/men/'+Math.floor(Math.random() * 100)+'.jpg'" :title="'Користувач: ' + user.name"
                                 :atl="'Користувач: ' + user.name">
                            <!--End-->

                            <!--<img :src="user.photo ? user.photo : 'img/user.png'" :title="'Користувач: ' + user.name"-->
                                 <!--:atl="'Користувач: ' + user.name">-->
                        </md-avatar>
                    </md-table-cell>

                    <md-table-cell>
                        <b>{{ user.name }}</b><br>
                        <span v-if="user.active">{{ user.nick }}</span>
                    </md-table-cell>

                    <md-table-cell>
                        {{ user.position }}
                    </md-table-cell>

                    <md-table-cell>
                        <md-chip v-if="user.active" :style="'color:#fff;background:' + user.role.color">{{ user.role.title }}</md-chip>
                    </md-table-cell>

                    <md-table-cell>
                        <md-menu md-align-trigger md-size="6" v-if="user.phone || user.work_phone || user.email || user.work_email">
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

                    <md-table-cell>
                        <md-button :href="'/user'+user.id+'/edit'" class="md-icon-button">
                            <md-icon>edit</md-icon>
                        </md-button>
                    </md-table-cell>

                </md-table-row>
            </md-table-body>

            <md-button href="/users/create" class="md-fab btn_fixed">
                <md-icon>add</md-icon>
            </md-button>
        </md-table>

        <div class="pagination">
            <md-button :href="users.prev_page_url ? users.path+'?page=1' : ''" class="md-icon-button md-raised md-dense">
                <md-icon>first_page</md-icon>
            </md-button>
            <md-button :href="users.prev_page_url" class="md-icon-button md-raised md-dense">
                <md-icon>keyboard_arrow_left</md-icon>
            </md-button>

            <md-button class="md-dense">{{ users.total }}</md-button>

            <md-button :href="users.next_page_url" class="md-icon-button md-raised md-dense">
                <md-icon>keyboard_arrow_right</md-icon>
            </md-button>
            <md-button :href="users.next_page_url ? users.path+'?page='+users.last_page : ''" class="md-icon-button md-raised md-dense">
                <md-icon>last_page</md-icon>
            </md-button>
        </div>
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
                q: null,
            }
        },

        created () {
            this.users = JSON.parse(this.data);
            console.log(this.users);
        },

        methods: {
            search() {
                if (this.q === null)
                    return;

                console.log('search');
            }
        }
    }
</script>
