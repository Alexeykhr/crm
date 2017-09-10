<template>
    <md-whiteframe md-elevation="2" class="page action">
        <form novalidate @submit.stop.prevent="submit">
            <div class="header">
                <template v-if="inJob">
                    <h1 :title="'Посада: #' + job.id">Посада: #{{ job.id }}</h1>
                </template>
                <template v-else>
                    <h1 title="Створення посади">Створення посади</h1>
                </template>

                <span style="flex: 1"></span>

                <md-button v-if="canView" class="md-icon-button" :href="'/jobs/' + job.id">
                    <md-icon>remove_red_eye</md-icon>
                    <md-tooltip md-direction="bottom">Переглянути</md-tooltip>
                </md-button>

                <md-button v-if="canEdit" class="md-icon-button" :href="'/jobs/' + job.id + '/edit'">
                    <md-icon>edit</md-icon>
                    <md-tooltip md-direction="bottom">Редагувати</md-tooltip>
                </md-button>

                <md-button v-if="canDelete && job.users_count < 1" class="md-icon-button" @click="openDialog('delete');">
                    <md-icon>delete</md-icon>
                    <md-tooltip md-direction="bottom">Видалити</md-tooltip>
                </md-button>

                <md-button v-if="canTransfer && job.users_count > 0" class="md-icon-button" @click="openDialog('transfer');">
                    <md-icon>people</md-icon>
                    <md-tooltip md-direction="bottom">Трансфер</md-tooltip>
                </md-button>

                <md-button v-if="inJob" class="md-icon-button" href="/jobs/create">
                    <md-icon>add</md-icon>
                    <md-tooltip md-direction="bottom">Створити посаду</md-tooltip>
                </md-button>
            </div>

            <md-input-container :class="duplicateTitle ? 'md-input-invalid' : ''">
                <label>Назва</label>
                <md-input :readonly="action == 'view'" :maxlength="60" v-model="title" @change.native="findJob()"
                          required autofocus></md-input>
                <span v-if="duplicateTitle" class="md-error">Посада вже існує</span>
            </md-input-container>

            <md-input-container>
                <label>Опис</label>
                <md-textarea :readonly="action == 'view'" v-model="desc" maxlength="255"></md-textarea>
            </md-input-container>

            <md-input-container v-if="inJob">
                <label>Кількість користувачів</label>
                <md-input v-model="job.users_count" readonly :disabled="action == 'edit'"></md-input>
            </md-input-container>

            <md-input-container v-if="inJob">
                <label>Останнє оновлення</label>
                <md-input v-model="job.updated_at" readonly :disabled="action == 'edit'"></md-input>
            </md-input-container>

            <md-input-container v-if="inJob">
                <label>Створений</label>
                <md-input v-model="job.created_at" readonly :disabled="action == 'edit'"></md-input>
            </md-input-container>

            <md-button v-if="action == 'create'" :disabled="search || this.title.length < 3"
                       class="md-raised md-primary btn-action" @click="createJob()">
                Створити
            </md-button>
            <md-button v-if="action == 'edit'" :disabled="search || this.title.length < 3"
                       class="md-raised md-primary btn-action" @click="updateJob()">
                Оновити
            </md-button>
        </form>

        <md-dialog v-if="canDelete" ref="delete">
            <md-dialog-title>
                Видалення: "{{ title }}"
            </md-dialog-title>

            <md-dialog-content>Ви впевнені, що хочете видалити посаду?</md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary" @click="closeDialog('delete')">Ні</md-button>
                <md-button class="md-raised md-primary" @click="deleteJob();">
                    Видалити
                </md-button>
            </md-dialog-actions>
        </md-dialog>

        <md-dialog v-if="canTransfer" ref="transfer">
            <md-dialog-title>
                Трансфер: "{{ title }}"
            </md-dialog-title>

            <md-dialog-content>
                <md-input-container>
                    <label>Номер/назва нової посада</label>
                    <md-input v-model="transferJob"></md-input>
                </md-input-container>
            </md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary" @click="closeDialog('transfer')">Ні</md-button>
                <md-button v-if="transferJob" class="md-raised md-primary" @click="transferUsers();">
                    Трансфер
                </md-button>
            </md-dialog-actions>
        </md-dialog>

        <md-snackbar class="snackbar-black" ref="snackbar" :md-duration="2000">
            <span>{{ response }}</span>
            <md-button @click="$refs.snackbar.close()">Сховати</md-button>
        </md-snackbar>
    </md-whiteframe>
</template>

<script>
    export default {
        props: [
            'inJob', 'action', 'canEdit', 'canView', 'canTransfer', 'canDelete',
        ],

        data() {
            return {
                job: [],
                title: '',
                desc: '',

                search: false,
                duplicateTitle: false,
                response: '',

                transferJob: '',
            }
        },

        created() {
            if (this.inJob) {
                this.job = JSON.parse(this.inJob);
                this.title = this.job.title;
                this.desc = this.job.desc;
            }
        },

        methods: {
            findJob() {
                this.duplicateTitle = false;

                if (this.title.length < 3) {
                    return;
                }

                if (this.inJob) {
                    if (this.job.title.toLowerCase() === this.title.toLowerCase()) {
                        return;
                    }
                }

                axios.get('/jobs.exist', {
                    params: {
                        title: this.title,
                    }
                })
                    .then(res => {
                        this.search = res.data;

                        if (res.data) {
                            this.duplicateTitle = true;
                        }
                    });
            },
            createJob() {
                axios.post('/jobs', {
                    title: this.title,
                    desc: this.desc,
                })
                    .then(res => {
                        this.title = '';
                        this.desc = '';
                        this.response = 'Посада створена';
                        this.$refs.snackbar.open();
                    })
                    .catch(error => this.parseError(error));
            },
            updateJob() {
                axios.put('/jobs/' + this.job.id, {
                    title: this.title,
                    desc: this.desc,
                })
                    .then(res => {
                        this.response = 'Посада оновлена';
                        this.$refs.snackbar.open();
                    })
                    .catch(error => this.parseError(error));
            },
            deleteJob() {
                axios.delete('/jobs/' + this.job.id)
                    .then(res => {
                        if (res.data == 1) {
                            location.href = '/jobs'
                        }
                    })
                    .catch(error => this.parseError(error));
            },
            transferUsers() {
                let transferJob = this.transferJob.toString().toLowerCase();

                axios.post('/jobs.transfer', {
                    from: this.job.id.toString(),
                    to: transferJob,
                })
                    .then(res => {
                        if (res.data > 0) {
                            this.job.users_count = 0;

                            this.response = 'Користувачі успішно перенесені';
                            this.$refs.snackbar.open();
                            this.closeDialog('transfer');
                        }
                    })
                    .catch(error => this.parseError(error));
            },
            openDialog(ref) {
                this.$refs[ref].open();
            },
            closeDialog(ref) {
                this.$refs[ref].close();
            },
            parseError(error) {
                this.response = 'Виникла помилка';

                if (error.response.data.title) {
                    switch (error.response.data.title[0]) {
                        case 'validation.unique':
                            this.duplicateTitle = true;
                            return;

                        case 'validation.required':
                            this.response = 'Назва обов\'язкова';
                            break;

                        case 'validation.min.string':
                            this.response = 'Назва має бути більше 2 символів';
                            break;

                        case 'validation.max.string':
                            this.response = 'Назва має бути менше 61 символа';
                            break;
                    }
                }
                else if (error.response.data.desc) {
                    switch (error.response.data.desc[0]) {
                        case 'validation.max.string':
                            this.response = 'Опис має бути менше 256 символів';
                            break;
                    }
                }
                else if (error.response.data.to) {
                    switch (error.response.data.to[0]) {
                        case 'validation.min.numeric':
                        case 'validation.exists':
                            this.response = parseInt(this.transferJob) == this.transferJob
                                ? 'Посада з таким номером не існує'
                                : 'Посада з такою назвою не існує';
                            break;

                        case 'validation.different':
                            this.response = parseInt(this.transferJob) == this.transferJob
                                ? 'Номера співпадають'
                                : 'Назви співпадають';
                            break;
                    }
                }
                else if (error.response.data.error) {
                    switch (error.response.data.error[0]) {
                        case 'validation.empty':
                            this.response = 'Посади не існує';
                            break;

                        case 'validation.exists_users':
                            this.response = 'Користувачі прікріплені на цю посаду';
                            break;
                    }
                }

                this.$refs.snackbar.open();
            },
        },
    }
</script>
