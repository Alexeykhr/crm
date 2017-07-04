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
            <md-button :href="users.prev_page_url ? users.path+'?page=1'+getAttribute() : ''" class="md-icon-button md-raised md-dense">
                <md-icon>first_page</md-icon>
            </md-button>
            <md-button :href="users.prev_page_url ? users.prev_page_url+getAttribute() : ''" class="md-icon-button md-raised md-dense">
                <md-icon>keyboard_arrow_left</md-icon>
            </md-button>

            <md-button class="md-dense">{{ users.current_page + ' / ' + users.last_page }}</md-button>

            <md-button :href="users.next_page_url ? users.next_page_url+getAttribute() : ''" class="md-icon-button md-raised md-dense">
                <md-icon>keyboard_arrow_right</md-icon>
            </md-button>
            <md-button :href="users.next_page_url ? users.path+'?page='+users.last_page+getAttribute() : ''" class="md-icon-button md-raised md-dense">
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
                        <md-chip v-if="!user.active">Немає доступ</md-chip>
                        <md-chip v-else :style="'color:#fff;background:' + user.role.color">{{ user.role.title }}</md-chip>
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
        </md-table>

        <div class="pagination">
            <md-button :href="users.prev_page_url ? users.path+'?page=1'+getAttribute() : ''" class="md-icon-button md-raised md-dense">
                <md-icon>first_page</md-icon>
            </md-button>
            <md-button :href="users.prev_page_url ? users.prev_page_url+getAttribute() : ''" class="md-icon-button md-raised md-dense">
                <md-icon>keyboard_arrow_left</md-icon>
            </md-button>

            <md-button class="md-dense">{{ users.current_page + ' / ' + users.last_page }}</md-button>

            <md-button :href="users.next_page_url ? users.next_page_url+getAttribute() : ''" class="md-icon-button md-raised md-dense">
                <md-icon>keyboard_arrow_right</md-icon>
            </md-button>
            <md-button :href="users.next_page_url ? users.path+'?page='+users.last_page+getAttribute() : ''" class="md-icon-button md-raised md-dense">
                <md-icon>last_page</md-icon>
            </md-button>
        </div>

        <md-button href="/users/create" class="md-fab btn_fixed_br">
            <md-icon>add</md-icon>
        </md-button>

        <md-button class="md-fab md-primary btn_fixed_bl" id="fab" @click="openDialog('filters')">
            <md-icon>filter_list</md-icon>
        </md-button>

        <md-dialog md-open-from="#fab" md-close-to="#fab" ref="filters">
            <md-dialog-title>Налаштування фільтрів</md-dialog-title>

            <md-dialog-content>
                <md-input-container>
                    <label>Кількість працівників</label>
                    <md-input min="1" :value="count > 100 ? 100 : count"
                              max="100" type="number" v-model="g_count"></md-input>
                </md-input-container>
                <md-input-container>
                    <label for="role">Ролі</label>
                    <md-select name="role" id="role" v-model="g_role">
                        <md-option v-for="role in roles"
                                   :key="role.id"
                                   :value="role.id">
                            {{ role.title }}
                        </md-option>
                    </md-select>
                </md-input-container>
            </md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary" @click="closeDialog('filters')">Вихід</md-button>
                <md-button class="md-primary" @click="setFilters()">Застосувати</md-button>
            </md-dialog-actions>
        </md-dialog>
    </md-layout>
</template>

<script>
    export default {
        props: [
            'inUsers', 'inRoles', 'count', 'role', 'active'
        ],

        data () {
            return {
                users: [],
                roles: [],

                q: null,

                g_count: 10,
                g_role: null,
                g_active: 1,
                g_delete: 0,
            }
        },

        created () {
            this.users = JSON.parse(this.inUsers);
            this.roles = JSON.parse(this.inRoles);

            this.g_count = this.count;
            this.g_role = this.roles[this.role - 1].id;

            console.log(this.users);
            console.log(this.roles);
        },

        methods: {
            search() {
                if (this.q === null)
                    return;

                console.log('search');
            },
            openDialog(ref) {
                this.$refs[ref].open();
            },
            closeDialog(ref) {
                this.$refs[ref].close();
            },
            setFilters() {
                if (this.g_count > 100) {
                    this.g_count = 100;
                } else if (this.g_count < 1) {
                    this.g_count = 10;
                }

                console.log(this.g_count);
                location.href = this.users.path+'?page=1'+this.getAttribute();
            },
            getAttribute() {
                return '&count='+this.g_count+'&role='+this.g_role+'&active='+this.g_active+'&delete='+this.g_delete;
            }
        }
    }
</script>
