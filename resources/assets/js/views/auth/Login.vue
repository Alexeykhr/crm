<template>
    <form @submit.prevent="login">
        <h2>Авторизація</h2>

        <v-text-field
            label="Логін"
            v-model="form.nickname"
            single-line
            prepend-icon="perm_identity"
            required
        ></v-text-field>

        <v-text-field
            label="Пароль"
            v-model="form.password"
            single-line
            prepend-icon="lock_outline"
            type="password"
            required
        ></v-text-field>

        <v-btn @click.prevent="login" block color="primary" :disabled="isProcessing">Увійти</v-btn>

        <v-snackbar v-model="snackbar.model">
            {{ snackbar.text }}
        </v-snackbar>
    </form>
</template>

<script>
    import { post } from '../../helpers/api'

    export default {
        data() {
            return {
                form: {
                    login: '',
                    password: ''
                },
                snackbar: {
                    model: false,
                    text: ''
                },
                isProcessing: false
            }
        },
        methods: {
            login() {
                this.isProcessing = true

                post('api/login', this.form)
                    .then(res => {
                        if (res.data.response.token) {
                            this.$router.push('dashboard')
                        }
                        this.isProcessing = false
                    })
                    .catch(err => {
                        if (err.response.data.error.message) {
                            this.snackbar.model = true
                            this.snackbar.text = err.response.data.error.message
                            // TODO: show normalize errors
                        }
                        this.isProcessing = false
                    })
            }
        }
    }
</script>
