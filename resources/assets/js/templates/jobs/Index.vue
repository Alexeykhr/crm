<template>
    <md-layout class="list">
        <md-layout class="left-column" md-flex="75">
            <md-table @sort="onSort">
                <md-table-header>
                    <md-table-row>
                        <md-table-head md-sort-by="title">Назва</md-table-head>
                        <md-table-head md-sort-by="users_count">Працівників</md-table-head>
                        <md-table-head></md-table-head>
                    </md-table-row>
                </md-table-header>

                <md-table-body>
                    <md-table-row v-for="(job, index) in jobs.data" :key="job.id" class="list-row">
                        <md-table-cell>
                            <span class="title bold">{{ job.title }}</span>
                        </md-table-cell>

                        <md-table-cell>
                            <span>{{ job.users_count }}</span>
                        </md-table-cell>

                        <md-table-cell>
                            <md-menu md-size="4">
                                <md-button class="md-icon-button" md-menu-trigger>
                                    <md-icon>more_vert</md-icon>
                                </md-button>

                                <md-menu-content>
                                    <md-menu-item :href="'/jobs/' + job.id">
                                        <md-icon>remove_red_eye</md-icon> <span>Переглянути</span>
                                    </md-menu-item>
                                    <md-menu-item v-if="canEdit" :href="'/jobs/' + job.id + '/edit'">
                                        <md-icon>edit</md-icon> <span>Редагувати</span>
                                    </md-menu-item>
                                    <md-menu-item v-if="canDelete" @click="openDelete(index)">
                                        <md-icon>delete</md-icon> <span>Видалити</span>
                                    </md-menu-item>
                                    <md-menu-item v-if="canTransfer && job.users_count > 0" @click="openTransfer(index)">
                                        <md-icon>people</md-icon> <span>Трансфер</span>
                                    </md-menu-item>
                                </md-menu-content>
                            </md-menu>
                        </md-table-cell>
                    </md-table-row>
                </md-table-body>
            </md-table>

            <pagination :data="jobs" :func="getJobs"></pagination>
        </md-layout>

        <md-layout class="right-column" md-flex="25">
            <md-input-container md-clearable>
                <md-icon>search</md-icon>
                <label>Пошук</label>
                <md-input v-model="q" autofocus></md-input>
            </md-input-container>

            <br>

            <md-input-container>
                <label for="count">Кількість посад</label>
                <md-select name="count" id="count" v-model="count">
                    <md-option :value="10">10</md-option>
                    <md-option :value="25">25</md-option>
                    <md-option :value="50">50</md-option>
                    <md-option :value="75">75</md-option>
                    <md-option :value="100">100</md-option>
                </md-select>
            </md-input-container>
        </md-layout>

        <md-dialog ref="delete">
            <md-dialog-title v-if="delIndex > -1">{{ jobs.data[delIndex].title }}</md-dialog-title>

            <md-dialog-content>Ви впевнені, що хочете видалити посаду?</md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary" @click="closeDialog('delete')">Ні</md-button>
                <md-button v-if="delIndex > -1" class="md-raised md-primary"
                           @click="deleteJob(jobs.data[delIndex].id, delIndex); closeDialog('delete');">
                    Так
                </md-button>
            </md-dialog-actions>
        </md-dialog>

        <md-dialog ref="transfer">
            <md-dialog-title v-if="transferIndex > -1">{{ jobs.data[transferIndex].title }}</md-dialog-title>

            <md-dialog-content>

            </md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary" @click="closeDialog('transfer')">Ні</md-button>
                <md-button v-if="transferIndex > -1" class="md-raised md-primary"
                           @click="transferUsers(jobs.data[transferIndex].id, 1, transferIndex); closeDialog('transfer');">
                    Так
                </md-button>
            </md-dialog-actions>
        </md-dialog>

        <md-snackbar class="snackbar-black" :md-position="'top right'" ref="snackbar" :md-duration="5000">
            <span>{{ response }}</span>
            <md-button @click="$refs.snackbar.close()">Сховати</md-button>
        </md-snackbar>
    </md-layout>
</template>

<script>
    export default {
        props: [
            'iUser', 'inJobs', 'canDelete', 'canEdit', 'canTransfer',
        ],

        data() {
            return {
                jobs: [],

                q: '',
                count: 25,
                active: 0,

                sortColumn: '',
                sortType: '',

                delIndex: -1,
                transferIndex: -1,
                response: '',
            }
        },

        created() {
            this.jobs = JSON.parse(this.inJobs);
        },

        methods: {
            getJobs(page = 1) {
                axios.get('/jobs.get', {
                    params: {
                        q: this.q,
                        count: this.count,
                        page: page,

                        sortColumn: this.sortColumn,
                        sortType: this.sortType,
                    }
                })
                    .then(res => this.jobs = res.data);

                $('body').animate({ scrollTop: $('.right-column')[0].offsetHeight + 48 }, 100);
                $('.md-table').animate({ scrollTop: 0 }, 100);
            },
            deleteJob(id, index) {
                axios.delete('/jobs/' + id)
                    .then(res => {
                        if (res.data == 1) {
                            this.jobs.data.splice(index, 1);
                        } else {
                            this.response = res.data;
                            this.$refs.snackbar.open();
                        }
                    });
            },
            transferUsers(fromId, toId, index) {
                axios.post('/jobs.transfer', {
                    from: fromId,
                    to: toId,
                })
                    .then(res => console.log(res));
            },
            onSort(action) {
                this.sortColumn = action.name;
                this.sortType = action.type;
                this.getJobs(this.jobs.current_page);
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
            openTransfer(index) {
                this.transferIndex = index;
                this.openDialog('transfer');
            },
        },

        watch: {
            q() {
                let len = this.q.length;

                if (len > 2 || len == 0) {
                    this.getJobs();
                }
            },
            count() {
                this.getJobs();
            },
            active() {
                this.getJobs();
            },
        },
    }
</script>
