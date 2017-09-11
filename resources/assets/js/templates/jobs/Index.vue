<template>
    <md-layout class="list">
        <md-layout class="left-column" md-flex="75">
            <md-table @sort="onSort" md-sort="id" md-sort-type="asc">
                <md-table-header>
                    <md-table-row>
                        <md-table-head md-sort-by="id">#</md-table-head>
                        <md-table-head md-sort-by="title">Назва</md-table-head>
                        <md-table-head md-sort-by="users_count">Користувачів</md-table-head>
                        <md-table-head></md-table-head>
                    </md-table-row>
                </md-table-header>

                <md-table-body>
                    <md-table-row v-for="(job, index) in jobs.data" :key="job.id" class="list-row">
                        <md-table-cell>
                            <span class="id">{{ job.id }}</span>
                        </md-table-cell>

                        <md-table-cell>
                            <span class="title bold">{{ job.title }}</span>
                        </md-table-cell>

                        <md-table-cell>
                            <span>{{ job.users_count }}</span>
                        </md-table-cell>

                        <md-table-cell class="flex-end">
                            <md-button class="md-icon-button" :href="'/jobs/' + job.id">
                                <md-icon>remove_red_eye</md-icon>
                                <md-tooltip md-direction="bottom">Переглянути</md-tooltip>
                            </md-button>

                            <md-button class="md-icon-button" v-if="canEdit" :href="'/jobs/' + job.id + '/edit'">
                                <md-icon>edit</md-icon>
                                <md-tooltip md-direction="bottom">Редагувати</md-tooltip>
                            </md-button>

                            <md-button class="md-icon-button" v-if="canDelete && job.users_count < 1" @click="openDelete(index)">
                                <md-icon>delete</md-icon>
                                <md-tooltip md-direction="bottom">Видалити</md-tooltip>
                            </md-button>

                            <md-button class="md-icon-button" v-if="canTransfer && job.users_count > 0" @click="openTransfer(index)">
                                <md-icon>people</md-icon>
                                <md-tooltip md-direction="bottom">Трансфер</md-tooltip>
                            </md-button>
                        </md-table-cell>
                    </md-table-row>
                </md-table-body>
            </md-table>

            <pagination :data="jobs" :func="getJobs"></pagination>
        </md-layout>

        <md-layout class="right-column" md-flex="25">
            <md-input-container md-clearable>
                <md-icon>search</md-icon>
                <label>[#] / Посада</label>
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

        <md-dialog v-if="canDelete" ref="delete">
            <md-dialog-title v-if="delIndex > -1">
                Видалення: "{{ jobs.data[delIndex].title }}"
            </md-dialog-title>

            <md-dialog-content>Ви впевнені, що хочете видалити посаду?</md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary" @click="closeDialog('delete')">Ні</md-button>
                <md-button v-if="delIndex > -1" class="md-raised md-primary"
                           @click="deleteJob();">
                    Видалити
                </md-button>
            </md-dialog-actions>
        </md-dialog>

        <md-dialog v-if="canTransfer" ref="transfer">
            <md-dialog-title v-if="transferIndex > -1">
                Трансфер: "{{ jobs.data[transferIndex].title }}"
            </md-dialog-title>

            <md-dialog-content>
                <md-input-container>
                    <label>Номер/назва нової посада</label>
                    <md-input v-model="transferJob"></md-input>
                </md-input-container>
            </md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary" @click="closeDialog('transfer')">Ні</md-button>
                <md-button v-if="transferIndex > -1 && transferJob" class="md-raised md-primary"
                           @click="transferUsers();">
                    Трансфер
                </md-button>
            </md-dialog-actions>
        </md-dialog>

        <md-snackbar class="snackbar-black" ref="snackbar" :md-duration="3000">
            <span>{{ response }}</span>
            <md-button @click="$refs.snackbar.close()">Сховати</md-button>
        </md-snackbar>
    </md-layout>
</template>

<script>
    export default {
        props: {
            inJobs: {
                type: Object,
                required: true,
            },
            canDelete: {
                type: Boolean,
            },
            canEdit: {
                type: Boolean,
            },
            canTransfer: {
                type: Boolean,
            }
        },

        data() {
            return {
                jobs: [],

                q: '',
                count: 10,

                sortColumn: '',
                sortType: '',

                response: '',
                delIndex: -1,

                transferJob: null,
                transferIndex: -1,
            }
        },

        created() {
            this.jobs = this.inJobs;
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
            deleteJob() {
                axios.delete('/jobs/' + this.jobs.data[this.delIndex].id)
                    .then(res => {
                        if (res.data == 1) {
                            this.jobs.data.splice(this.delIndex, 1);
                            this.response = 'Посада успішно видалена';

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
                                this.response = 'Посада не знайдена';
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
            transferUsers() {
                let transferJob = this.transferJob.toString().toLowerCase();

                axios.post('/jobs.transfer', {
                    from: this.jobs.data[this.transferIndex].id.toString(),
                    to: transferJob,
                })
                    .then(res => {
                        if (res.data > 0) {
                            this.jobs.data[this.transferIndex].users_count = 0;

                            this.jobs.data.forEach((obj, index) => {
                                if (obj.id == transferJob || obj.title.toLowerCase() == transferJob) {
                                    this.jobs.data[index].users_count += res.data;
                                }
                            });

                            this.response = 'Користувачі успішно перенесені';
                            this.$refs.snackbar.open();
                            this.closeDialog('transfer');
                        }
                    })
                    .catch(error => {
                        if (! error.response.data.to) {
                            this.response = 'Виникла помилка';
                            this.$refs.snackbar.open();
                            return;
                        }

                        switch (error.response.data.to[0]) {
                            case 'validation.min.numeric':
                            case 'validation.exists':
                                this.response = parseInt(transferJob) == transferJob
                                    ? 'Посада з таким номером не існує'
                                    : 'Посада з такою назвою не існує';
                                break;

                            case 'validation.different':
                                this.response = parseInt(transferJob) == transferJob
                                    ? 'Номера співпадають'
                                    : 'Назви співпадають';
                                break;

                            default:
                                this.response = 'Виникла помилка';
                        }

                        this.$refs.snackbar.open();
                    });
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
                this.transferJob = null;
                this.transferIndex = index;
                this.openDialog('transfer');
            },
        },

        watch: {
            q() {
                this.getJobs();
            },
            count() {
                this.getJobs();
            },
        },
    }
</script>
