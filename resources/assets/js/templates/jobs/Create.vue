<template>
    <md-whiteframe md-elevation="2" class="page create">
        <h1>Створення посади</h1>

        <md-input-container>
            <label>Назва</label>
            <md-input v-model="title" @change.native="findJob()" autofocus></md-input>
        </md-input-container>
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
            }
        },

        created() {
            this.me = JSON.parse(this.iUser);
        },

        methods: {
            findJob() {
                console.log('Find Job');

                axios.get('/axios/job.find', {
                    params: {
                        title: this.title,
                    }
                })
                    .then(res => console.log(res))
                    .catch(error => console.log(error));
            },
        },
    }
</script>
