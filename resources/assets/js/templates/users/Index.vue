<template>
    <md-layout class="page">
        <md-input-container md-clearable>
            <md-icon>search</md-icon>
            <label>Пошук працівника</label>
            <md-input v-model="q" autofocus></md-input>
        </md-input-container>

        <pagination :data="users" :func="getUsers"></pagination>

        <md-table>
            <md-table-header>
                <md-table-row>
                    <md-table-head>Фото</md-table-head>
                    <md-table-head>Користувач</md-table-head>
                    <md-table-head v-if="me.role.acs_job">Посада</md-table-head>
                    <md-table-head v-if="me.role.acs_role">Роль</md-table-head>
                    <md-table-head>Контакти</md-table-head>
                    <md-table-head></md-table-head>
                </md-table-row>
            </md-table-header>

            <md-table-body>
                <md-table-row v-for="user in users.data" :key="user.id">
                    <md-table-cell>
                        <md-avatar>
                            <!-- Temporary-->
                            <!--<img :src="user.photo ? user.photo : 'https://randomuser.me/api/portraits/men/'+Math.floor(Math.random() * 100)+'.jpg'" :title="'Користувач: ' + user.name"-->
                                 <!--:atl="'Користувач: ' + user.name">-->
                            <!--End-->

                            <img :src="user.photo ? user.photo : 'img/user.png'" :title="'Користувач: ' + user.name"
                                 :atl="'Користувач: ' + user.name">
                        </md-avatar>
                    </md-table-cell>

                    <md-table-cell>
                        <b>{{ user.name }}</b><br>
                        <span v-if="user.active">{{ user.nick }}</span>
                    </md-table-cell>

                    <md-table-cell v-if="me.role.acs_job">
                        <span v-if="user.job_id">{{ user.job.title }}</span>
                    </md-table-cell>

                    <md-table-cell v-if="me.role.acs_role">
                        <md-chip v-if="user.delete">Видалений</md-chip>
                        <md-chip v-else-if="!user.active">Немає доступ</md-chip>
                        <md-chip v-else :style="'color:' + user.role.color + ';background:' + user.role.background + ';'">
                            {{ user.role.title }}
                        </md-chip>
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
                        <md-button :href="'/u/'+user.id" class="md-icon-button">
                            <md-icon>remove_red_eye</md-icon>
                        </md-button>
                    </md-table-cell>

                </md-table-row>
            </md-table-body>
        </md-table>

        <pagination :data="users" :func="getUsers"></pagination>

        <md-button v-if="canCreate" href="/u/create" class="md-fab btn_fixed_br">
            <md-icon>add</md-icon>
        </md-button>

        <md-button class="md-fab md-primary btn_fixed_bl" id="fab" @click="openDialog()">
            <md-icon>filter_list</md-icon>
        </md-button>

        <md-dialog @close="getUsers()" md-open-from="#fab" md-close-to="#fab" ref="filters">
            <md-dialog-title>Налаштування фільтрів

                <md-button @click="resetFilters()" class="md-icon-button md-raised md-accent md-dense">
                    <md-icon>undo</md-icon>
                </md-button>
            </md-dialog-title>

            <md-dialog-content>
                <md-input-container>
                    <label>Кількість працівників</label>
                    <md-input min="1" :value="count > 100 ? 100 : count"
                              max="100" type="number" v-model="count"></md-input>
                </md-input-container>
                <md-input-container v-if="me.role.acs_role">
                    <label for="role">Роль</label>
                    <md-select name="role" id="role" v-model="role">
                        <md-option :key="-1" :value="-1">Всі</md-option>
                        <md-option v-for="role in roles"
                                   :key="role.id"
                                   :value="role.id">
                            {{ role.title }}
                        </md-option>
                    </md-select>
                </md-input-container>
                <md-input-container v-if="me.role.acs_job">
                    <label for="job">Посада</label>
                    <md-select name="job" id="job" v-model="job">
                        <md-option :key="-1" :value="-1">Всі</md-option>
                        <md-option v-for="job in jobs"
                                   :key="job.id"
                                   :value="job.id">
                            {{ job.title }}
                        </md-option>
                    </md-select>
                </md-input-container>
                <div>
                    <span>Має доступ:</span>
                    <md-radio v-model="active" name="active" md-value="1">Так</md-radio>
                    <md-radio v-model="active" name="active" md-value="0">-</md-radio>
                    <md-radio v-model="active" name="active" md-value="-1">Ні</md-radio>
                </div>
                <div>
                    <span>Видалений:</span>
                    <md-radio v-model="del" name="delete" md-value="1">Так</md-radio>
                    <md-radio v-model="del" name="delete" md-value="0">-</md-radio>
                    <md-radio v-model="del" name="delete" md-value="-1">Ні</md-radio>
                </div>
            </md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary md-raised" @click="closeDialog()">Закрити вікно</md-button>
            </md-dialog-actions>
        </md-dialog>
    </md-layout>
</template>

<script>
    export default {
        props: [
            'iUser', 'inUsers', 'inJobs', 'inRoles', 'canCreate',
        ],

        data () {
            return {
                me: [],
                users: [],
                roles: [],
                jobs: [],

                q: '',
                count: 25,
                role: -1,
                job: -1,
                active: 0,
                del: -1,
            }
        },

        created () {
            this.me = JSON.parse(this.iUser);
            this.jobs = JSON.parse(this.inJobs);
            this.roles = JSON.parse(this.inRoles);
            this.users = JSON.parse(this.inUsers);
        },

        methods: {
            openDialog() {
                this.$refs['filters'].open();
            },
            closeDialog() {
                this.$refs['filters'].close();
                this.getUsers();
            },
            resetFilters () {
                this.q = '';
                this.count = 25;
                this.role = -1;
                this.job = -1;
                this.active = 0;
                this.del = -1;
                this.closeDialog();
            },
            getUsers (page = 1) {
                axios.get('/users/get', {
                    params: {
                        count: this.count,
                        role: this.role,
                        job: this.job,
                        del: this.del,
                        active: this.active,
                        q: this.q,
                        page: page,
                    }
                })
                .then(res => this.users = res.data);
            },
        },

        watch: {
            q () {
                this.getUsers();
            }
        }
    }
</script>
