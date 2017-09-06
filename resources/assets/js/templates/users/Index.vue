<template>
    <md-layout class="list">
        <md-layout class="left-column" md-flex="75">
            <md-table @sort="onSort">
                <md-table-header>
                    <md-table-row>
                        <md-table-head>Фото</md-table-head>
                        <md-table-head md-sort-by="name">Користувач</md-table-head>
                        <!--TODO: props-->
                        <md-table-head v-if="me.role.acs_job">Посада</md-table-head>
                        <md-table-head v-if="me.role.acs_role">Роль</md-table-head>
                        <md-table-head>Контакти</md-table-head>
                        <md-table-head></md-table-head>
                    </md-table-row>
                </md-table-header>

                <md-table-body>
                    <md-table-row v-for="(user, index) in users.data" :key="user.id" :class="setClass(index)"
                                  :style="!user.delete && user.active && user.role.color ?
                                  'border-left: 10px solid ' + user.role.color + ';' : ''">
                        <md-table-cell>
                            <md-avatar>
                                <img :src="user.photo ? user.photo : 'img/user.png'" :atl="'Користувач: ' + user.name">
                            </md-avatar>
                        </md-table-cell>

                        <md-table-cell>
                            <span class="title">{{ user.name }}</span><br>
                            <span v-if="user.active">{{ user.nick }}</span>
                        </md-table-cell>

                        <md-table-cell v-if="me.role.acs_job">
                            <span v-if="user.job_id">{{ user.job.title }}</span>
                        </md-table-cell>

                        <md-table-cell v-if="me.role.acs_role">
                            <span v-if="!user.delete && user.active">{{ user.role.title }}</span>
                        </md-table-cell>

                        <md-table-cell>
                            <md-menu md-align-trigger v-if="user.phone || user.work_phone || user.email || user.work_email">
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
                            <md-menu md-size="4">
                                <md-button class="md-icon-button" md-menu-trigger>
                                    <md-icon>more_vert</md-icon>
                                </md-button>

                                <md-menu-content>
                                    <md-menu-item :href="'/users/' + user.id">
                                        <md-icon>remove_red_eye</md-icon> <span>Переглянути</span>
                                    </md-menu-item>
                                    <md-menu-item :href="'/users/' + user.id + '/edit'">
                                        <md-icon>edit</md-icon> <span>Редагувати</span>
                                    </md-menu-item>
                                    <md-menu-item>
                                        <md-icon>delete</md-icon> <span>Видалити</span>
                                    </md-menu-item>
                                </md-menu-content>
                            </md-menu>
                        </md-table-cell>
                    </md-table-row>
                </md-table-body>
            </md-table>

            <pagination :data="users" :func="getUsers"></pagination>
        </md-layout>

        <md-layout class="right-column" md-flex="25">
            <md-input-container md-clearable>
                <md-icon>search</md-icon>
                <label>Пошук</label>
                <md-input v-model="q" autofocus></md-input>
            </md-input-container>

            <br>

            <md-input-container>
                <label for="count">Кількість працівників</label>
                <md-select name="count" id="count" v-model="count">
                    <md-option :value="10">10</md-option>
                    <md-option :value="25">25</md-option>
                    <md-option :value="50">50</md-option>
                    <md-option :value="75">75</md-option>
                    <md-option :value="100">100</md-option>
                </md-select>
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

            <div class="choose">
                <span>Має доступ</span>
                <md-radio v-model="active" name="active" md-value="1">Так</md-radio>
                <md-radio v-model="active" name="active" md-value="0">-</md-radio>
                <md-radio v-model="active" name="active" md-value="-1">Ні</md-radio>
            </div>
        </md-layout>
    </md-layout>
</template>

<script>
    export default {
        props: [
            'iUser', 'inUsers', 'inJobs', 'inRoles',
        ],

        data() {
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

                sortColumn: '',
                sortType: '',
            }
        },

        created() {
            this.me = JSON.parse(this.iUser);
            this.users = JSON.parse(this.inUsers);
            this.jobs = JSON.parse(this.inJobs);
            this.roles = JSON.parse(this.inRoles);
        },

        methods: {
            getUsers(page = 1) {
                axios.get('/users.get', {
                    params: {
                        q: this.q,
                        count: this.count,
                        role: this.role,
                        job: this.job,
                        active: this.active,
                        page: page,

                        sortColumn: this.sortColumn,
                        sortType: this.sortType,
                    }
                })
                    .then(res => this.users = res.data);

                $('body').animate({ scrollTop: $('.right-column')[0].offsetHeight + 48 }, 100);
                $('.md-table').animate({ scrollTop: 0 }, 100);
            },
            setClass(id) {
                let classes = 'list-row';

                classes += this.users.data[id].active ? ' active' : ' no-active';

                return classes;
            },
            onSort(action) {
                this.sortColumn = action.name;
                this.sortType = action.type;
                this.getUsers(this.users.current_page);
            },
        },

        watch: {
            q() {
                let len = this.q.length;

                if (len > 2 || len == 0) {
                    this.getUsers();
                }
            },
            count() {
                this.getUsers();
            },
            role() {
                this.getUsers();
            },
            job() {
                this.getUsers();
            },
            active() {
                this.getUsers();
            },
        },
    }
</script>
