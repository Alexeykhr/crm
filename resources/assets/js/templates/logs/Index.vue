<template>
    <md-layout class="list">
        <md-layout class="left-column" md-flex="75">
            <md-table>
                <md-table-header>
                    <md-table-row>
                        <md-table-head>Користувач</md-table-head>
                        <md-table-head>Модуль</md-table-head>
                        <md-table-head>Дія</md-table-head>
                        <md-table-head>Опис</md-table-head>
                        <md-table-head>Дата</md-table-head>
                        <md-table-head>Посилання</md-table-head>
                    </md-table-row>
                </md-table-header>

                <md-table-body>
                    <md-table-row v-for="log in logs.data" :key="log.id">

                        <md-table-cell><b>{{ log.user.name }}</b></md-table-cell>
                        <md-table-cell>{{ log.module }}</md-table-cell>
                        <md-table-cell>{{ log.action }}</md-table-cell>
                        <md-table-cell>{{ log.desc }}</md-table-cell>
                        <md-table-cell>{{ timestamp(log.date) }}</md-table-cell>
                        <md-table-cell>
                            <md-button :href="'/users/'+log.user.id" class="md-icon-button">
                                <md-icon>account_circle</md-icon>
                            </md-button>
                            <!--TODO: link (ref_id)-->
                            <md-button href="/" class="md-icon-button">
                                <md-icon>remove_red_eye</md-icon>
                            </md-button>
                        </md-table-cell>

                    </md-table-row>
                </md-table-body>
            </md-table>

            <pagination :data="logs" :func="getLogs"></pagination>
        </md-layout>

        <md-layout class="right-column" md-flex="25">
            <md-input-container md-clearable>
                <md-icon>search</md-icon>
                <label>Пошук</label>
                <md-input v-model="q" autofocus></md-input>
            </md-input-container>

            <br>

            <md-input-container>
                <label for="module">Модуль</label>
                <md-select name="module" id="module" v-model="module">
                    <md-option value="">Всі</md-option>
                    <md-option value="Авторизація">Авторизація</md-option>
                    <md-option value="Профіль">Журнал</md-option>
                    <md-option value="Користувачі">Користувачі</md-option>
                    <md-option value="Роль">Роль</md-option>
                    <md-option value="Посада">Посада</md-option>
                    <md-option value="Каталог">Каталог</md-option>
                    <md-option value="Журнал">Журнал</md-option>
                    <md-option value="Календар">Календар</md-option>
                </md-select>
            </md-input-container>

            <md-input-container>
                <label for="action">Дія</label>
                <md-select name="action" id="action" v-model="action">
                    <md-option value="">Всі</md-option>
                    <md-option value="Створення">Створення</md-option>
                    <md-option value="Видалення">Видалення</md-option>
                    <md-option value="Редагування">Редагування</md-option>
                    <md-option value="Перегляд">Перегляд</md-option>
                    <md-option value="Інше">Інше</md-option>
                </md-select>
            </md-input-container>
        </md-layout>
    </md-layout>
</template>

<script>
    let moment = require('moment');
    moment.locale('uk');

    export default {
        props: [
            'iUser', 'inLogs',
        ],

        data () {
            return {
                me: [],
                logs: [],

                q: '',
                module: '',
                action: '',
                count: 25,
            }
        },

        created () {
            this.me = JSON.parse(this.iUser);
            this.logs = JSON.parse(this.inLogs);
        },

        methods: {
            timestamp(date) {
                return moment(date).format('llll');
            },
            getLogs(page = 1) {
                axios.get('/axios/logs.get', {
                    params: {
                        q: this.q,
                        module: this.module,
                        action: this.action,
                        count: this.count,
                        page: page,
                    }
                })
                    .then(res => this.logs = res.data)
                    .catch(error => console.log(this.error));

                $('body').animate({ scrollTop: $('.right-column')[0].offsetHeight + 48 }, 100);
                $('.md-table').animate({ scrollTop: 0 }, 100);
            },
        },

        watch: {
            q() {
                let len = this.q.length;

                if (len > 2 || len == 0) {
                    this.getLogs();
                }
            },
            action() {
                this.getLogs();
            },
            module() {
                this.getLogs();
            },
        },
    }
</script>
