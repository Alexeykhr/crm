<template>
    <div class="text-xs-center">
        <h3>Вітаємо</h3>

        <v-text-field
            name="nick"
            prepend-icon="perm_identity"
            label="Логін"
            id="nick"
            type="text"
            autofocus
            required
        ></v-text-field>

        <v-text-field
            name="password"
            prepend-icon="lock_outline"
            label="Пароль"
            id="password"
            type="password"
            required
        ></v-text-field>

        <v-checkbox label="Запам'ятати мене" v-model="remember" primary dark></v-checkbox>
        <input type="hidden" name="remember" :value="remember ? 'on' : ''">

        <v-btn class="blue darken-2" type="submit" block secondary light>Увійти</v-btn>

        <v-snackbar
            :error="true"
            :top="true"
            :right="true"
            v-model="snackbar">
                Неправильний логін або пароль
                <v-btn light flat @click.native="snackbar = false">Close</v-btn>
        </v-snackbar>
    </div>
</template>

<script>
    export default {
        props: [
            'is-errors'
        ],

        data () {
            return {
                snackbar: false,
                remember: true,
            }
        },

        mounted () {
            this.snackbar = this.isErrors;
        }
    }
</script>
