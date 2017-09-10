<template>
    <md-layout class="list">
        <md-layout class="left-column" md-flex="75">
            <md-table @sort="onSort" md-sort="id" md-sort-type="asc">
                <md-table-header>
                    <md-table-row>
                        <md-table-head md-sort-by="id">#</md-table-head>
                        <md-table-head>Фото</md-table-head>
                        <md-table-head md-sort-by="name">Користувач</md-table-head>
                        <md-table-head v-if="me.role.acs_job">Посада</md-table-head>
                        <md-table-head v-if="me.role.acs_role">Роль</md-table-head>
                        <md-table-head>Контакти</md-table-head>
                        <md-table-head></md-table-head>
                    </md-table-row>
                </md-table-header>

                <md-table-body>
                    <md-table-row v-for="(user, index) in users.data" :key="user.id" :class="setClass(index)"
                                  :style="!user.delete && user.active && user.role.color ?
                                  'border-left: 5px solid rgb(' + user.role.color + ');' +
                                  'background: rgba(' + user.role.color + ',.05);' : 'border-left: 5px solid #fff;'">
                        <md-table-cell>
                            <span class="id">{{ user.id }}</span>
                        </md-table-cell>

                        <md-table-cell>
                            <md-avatar>
                                <img :src="user.photo ? user.photo : 'img/user.png'" :atl="'Користувач: ' + user.name">
                            </md-avatar>
                        </md-table-cell>

                        <md-table-cell>
                            <span class="title">{{ user.name }}</span><br>
                            <span v-if="user.nick && user.active">{{ user.nick }}</span>
                        </md-table-cell>

                        <md-table-cell v-if="me.role.acs_job">
                            <span v-if="user.job_id">{{ user.job.title }}</span>
                        </md-table-cell>

                        <md-table-cell v-if="me.role.acs_role">
                            <span v-if="user.active">{{ user.role.title }}</span>
                        </md-table-cell>

                        <md-table-cell>
                            <md-menu md-size="6" md-align-trigger
                                     v-if="user.phone || user.work_phone || user.email || user.work_email">
                                <md-button class="md-icon-button" md-menu-trigger>
                                    <md-icon>contact_mail</md-icon>
                                </md-button>

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

                        <md-table-cell class="flex-end">
                            <md-button class="md-icon-button" :href="'/users/' + user.id">
                                <md-icon>remove_red_eye</md-icon>
                                <md-tooltip md-direction="bottom">Переглянути</md-tooltip>
                            </md-button>

                            <md-button class="md-icon-button" v-if="canEdit && me.role.level >= user.role.level"
                                       :href="'/users/' + user.id + '/edit'">
                                <md-icon>edit</md-icon>
                                <md-tooltip md-direction="bottom">Редагувати</md-tooltip>
                            </md-button>

                            <md-button class="md-icon-button" v-if="canDelete && me.role.level >= user.role.level"
                                       @click="openDelete(index)">
                                <md-icon>delete</md-icon>
                                <md-tooltip md-direction="bottom">Видалити</md-tooltip>
                            </md-button>
                        </md-table-cell>
                    </md-table-row>
                </md-table-body>
            </md-table>

            <pagination :data="users" :func="getUsers"></pagination>
        </md-layout>

        <md-layout class="right-column" md-flex="25">
            <md-input-container md-clearable>
                <md-icon>search</md-icon>
                <label>[#] / Користувач</label>
                <md-input v-model="qUser" autofocus></md-input>
            </md-input-container>

            <md-input-container v-if="me.role.acs_role" md-clearable>
                <md-icon>search</md-icon>
                <label>[#] / Роль</label>
                <md-input v-model="qRole" autofocus></md-input>
            </md-input-container>

            <md-input-container v-if="me.role.acs_job" md-clearable>
                <md-icon>search</md-icon>
                <label>[#] / Посада</label>
                <md-input v-model="qJob" autofocus></md-input>
            </md-input-container>

            <br>

            <md-input-container>
                <label for="count">Кількість користувачів</label>
                <md-select name="count" id="count" v-model="count">
                    <md-option :value="10">10</md-option>
                    <md-option :value="25">25</md-option>
                    <md-option :value="50">50</md-option>
                    <md-option :value="75">75</md-option>
                    <md-option :value="100">100</md-option>
                </md-select>
            </md-input-container>

            <div class="choose">
                <span>Має доступ</span>
                <md-radio v-model="active" name="active" md-value="1">Так</md-radio>
                <md-radio v-model="active" name="active" md-value="0">-</md-radio>
                <md-radio v-model="active" name="active" md-value="-1">Ні</md-radio>
            </div>
        </md-layout>

        <md-dialog v-if="canDelete" ref="delete">
            <md-dialog-title v-if="delIndex > -1">
                Видалення: "{{ users.data[delIndex].title }}"
            </md-dialog-title>

            <md-dialog-content>Ви впевнені, що хочете видалити посаду?</md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary" @click="closeDialog('delete')">Ні</md-button>
                <md-button v-if="delIndex > -1" class="md-raised md-primary"
                           @click="deleteUser();">
                    Видалити
                </md-button>
            </md-dialog-actions>
        </md-dialog>
    </md-layout>
</template>

<script>
    export default {
        props: [
            'iUser', 'inUsers', 'canEdit', 'canDelete',
        ],

        data() {
            return {
                me: [],
                users: [],

                qUser: '',
                qRole: '',
                qJob: '',
                count: 10,
                active: 0,
                del: -1,

                sortColumn: '',
                sortType: '',

                response: '',
                delIndex: -1,
            }
        },

        created() {
            this.me = JSON.parse(this.iUser);
            this.users = JSON.parse(this.inUsers);
        },

        methods: {
            getUsers(page = 1) {
                axios.get('/users.get', {
                    params: {
                        qUser: this.qUser,
                        qRole: this.qRole,
                        qJob: this.qJob,
                        count: this.count,
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
            deleteUser() {
                axios.delete('/users/' + id)
                    .then(res => {
                        if (res.data == 1) {
                            this.users.data.splice(index, 1);
                            this.response = 'Користувач успішно видалений';

                            this.$refs.snackbar.open();
                            this.closeDialog('delete');
                        }
                    })
                    .catch(error => {
                        if (! error.response.data.error) {
                            this.response = 'Виникла помилка';
                            this.$refs.snackbar.open();
                            return;
                        }

                        switch (error.response.data.error[0]) {
                            case 'validation.empty':
                                this.response = 'Посади не існує';
                                break;

                            case 'validation.exists_users':
                                this.response = 'Користувачі прікріплені на цю посаду';
                                break;

                            default:
                                this.response = 'Виникла помилка';
                        }

                        this.$refs.snackbar.open();
                    });
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
            openDialog(ref) {
                this.$refs[ref].open();
            },
            closeDialog(ref) {
                this.$refs[ref].close();
            },
            openDelete(index) {
                this.delIndex = index;
                this.openDialog('delete');
            },
        },

        watch: {
            qUser() {
                this.getUsers();
            },
            qRole() {
                this.getUsers();
            },
            qJob() {
                this.getUsers();
            },
            count() {
                this.getUsers();
            },
            active() {
                this.getUsers();
            },
        },
    }
</script>
