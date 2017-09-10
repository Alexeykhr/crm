<template>
    <md-whiteframe md-elevation="2" class="page action">
        <form novalidate @submit.stop.prevent="submit">
            <div class="header">
                <template v-if="inJob">
                    <h1 :title="'Посада #' + job.id">Посада: #{{ job.id }}</h1>
                </template>
                <template v-else>
                    <h1 title="Створення посади">Створення посади</h1>
                </template>

                <span style="flex: 1"></span>

                <template v-if="canEdit">
                    <md-button class="md-icon-button" :href="'/jobs/' + job.id + '/edit'">
                        <md-icon>edit</md-icon>
                        <md-tooltip md-direction="bottom">Відредагувати</md-tooltip>
                    </md-button>
                </template>

                <!--TODO: canDelete, canTransfer with errors-->

                <template v-if="canView">
                    <md-button class="md-icon-button" :href="'/jobs/' + job.id">
                        <md-icon>remove_red_eye</md-icon>
                        <md-tooltip md-direction="bottom">Переглянути</md-tooltip>
                    </md-button>
                </template>
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
                <label>Кількість працівників</label>
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

        <md-snackbar class="snackbar-black" ref="snackbar" :md-duration="2000">
            <span>{{ response }}</span>
            <md-button @click="$refs.snackbar.close()">Сховати</md-button>
        </md-snackbar>
    </md-whiteframe>
</template>

<script>
    export default {
        props: [
            'iUser', 'inJob', 'action', 'canEdit', 'canView',
        ],

        data() {
            return {
                job: [],
                title: '',
                desc: '',

                search: false,
                duplicateTitle: false,
                response: '',
            }
        },

        created() {
            this.me = JSON.parse(this.iUser);

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
            parseError(error) {
                if (!error.response.data.title && !error.response.data.desc) {
                    this.response = 'Виникла помилка';
                    this.$refs.snackbar.open();
                    return false;
                }

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

                        default:
                            this.response = 'Виникла помилка в назві';
                    }

                    this.$refs.snackbar.open();
                    return;
                }

                if (error.response.data.desc) {
                    switch (error.response.data.desc[0]) {
                        case 'validation.max.string':
                            this.response = 'Опис має бути менше 256 символів';
                            break;

                        default:
                            this.response = 'Виникла помилка в описі';
                    }

                    this.$refs.snackbar.open();
                }
            },
        },
    }
</script>
