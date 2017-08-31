<template>
    <md-whiteframe md-elevation="2" class="page create">
        <form novalidate @submit.stop.prevent="open">
            <h1>Посада</h1>

            <md-input-container :class="error ? 'md-input-invalid' : ''">
                <label>Назва</label>
                <md-input :maxlength="255" v-model="title" @change.native="findJob()" required autofocus></md-input>
                <span v-if="error" class="md-error">Посада вже існує</span>
            </md-input-container>

            <md-button :disabled="find" class="md-raised md-primary btn-create" @click="createJob()">Створити</md-button>
        </form>

        <md-snackbar :md-position="'top right'" class="success" ref="snackbar" :md-duration="5000">
            <span>Посада створена!</span>
            <md-button class="md-accent" @click="$refs.snackbar.close()">Сховати</md-button>
        </md-snackbar>
    </md-whiteframe>
</template>

<script>
    export default {
        props: [
            'iUser',
        ],

        data() {
            return {
                title: '',
                find: true,
                success: false,
                error: false,
            }
        },

        created() {
            this.me = JSON.parse(this.iUser);
        },

        methods: {
            findJob() {
                if (this.title.length < 3) {
                    return;
                }

                axios.get('/axios/jobs.exist', {
                    params: {
                        title: this.title,
                    }
                })
                    .then(res => this.find = res.data)
                    .catch(error => console.log(error));
            },
            createJob() {
                this.success = false;
                this.error = false;

                axios.post('/jobs', {
                    title: this.title,
                })
                    .then(res => {
                        this.$refs.snackbar.open();
                        this.title = '';
                    })
                    .catch(error => this.error = true);
            },
        },

        watch: {
            title() {
                this.find = this.title.length < 3;
            },
        },
    }
</script>
