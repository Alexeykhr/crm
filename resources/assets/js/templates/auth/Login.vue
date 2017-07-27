<template>
    <div class="auth">
        <h2>Вітаємо</h2>

        <form action="login" method="POST">
            <input type="hidden" name="_token" :value="csrf">

            <md-input-container style="margin-bottom: 10px;">
                <md-icon>perm_identity</md-icon>
                <label>Логін</label>
                <md-input name="nick" :value="nick" autofocus required></md-input>
            </md-input-container>

            <md-input-container>
                <md-icon>lock_outline</md-icon>
                <label>Пароль</label>
                <md-input type="password" name="password" required></md-input>
            </md-input-container>

            <md-checkbox name="remember" class="md-primary" v-model="save">Запам'ятати мене</md-checkbox>

            <md-button class="md-raised md-primary" type="submit">Увійти</md-button>
        </form>

        <md-snackbar md-position="top right" ref="snackbar" md-duration="4000">
            <span>Логін або пароль невірний</span>
            <md-button class="md-primary" style="color: #fff;" @click="$refs.snackbar.close()">Закрити</md-button>
        </md-snackbar>
    </div>
</template>

<script>
    export default {
        props: [
            'error', 'csrf', 'nick', 'remember'
        ],

        data () {
            return {
                save: this.remember,
            }
        },

        mounted () {
            if (this.error) {
                this.$nextTick(() => {
                    this.$refs.snackbar.open();
                });
            }
        }
    }
</script>
