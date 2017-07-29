<template>
    <md-layout class="page">
        <md-input-container md-clearable>
            <md-icon>search</md-icon>
            <label>Пошук працівника</label>
            <md-input v-model="q" autofocus></md-input>
        </md-input-container>

        <pagination :data="logs" :func="getLogs"></pagination>

        <md-table>
            <md-table-header>
                <md-table-row>
                    <md-table-head>Користувач</md-table-head>
                    <md-table-head>Дія</md-table-head>
                    <md-table-head>Опис</md-table-head>
                    <md-table-head>Дата</md-table-head>
                    <md-table-head>Посилання</md-table-head>
                </md-table-row>
            </md-table-header>

            <md-table-body>
                <md-table-row v-for="log in logs.data" :key="logs.id">

                    <md-table-cell><b>{{ log.user.name }}</b></md-table-cell>
                    <md-table-cell>{{ log.action }}</md-table-cell>
                    <md-table-cell>{{ log.desc }}</md-table-cell>
                    <md-table-cell>{{ timestamp(log.date) }}</md-table-cell>
                    <!--<md-table-cell v-if="log.ref_id">-->
                    <md-table-cell>
                        <md-button :href="'/u/'+log.user.id" class="md-icon-button">
                            <md-icon>account_circle</md-icon>
                        </md-button>
                        <!--TODO: link-->
                        <md-button href="/" class="md-icon-button">
                            <md-icon>remove_red_eye</md-icon>
                        </md-button>
                    </md-table-cell>

                </md-table-row>
            </md-table-body>
        </md-table>

        <pagination :data="logs" :func="getLogs"></pagination>

        <md-button class="md-fab md-primary btn_fixed_bl" id="fab" @click="openDialog()">
            <md-icon>filter_list</md-icon>
        </md-button>

        <md-dialog @close="getLogs()" md-open-from="#fab" md-close-to="#fab" ref="filters">
            <md-dialog-title>Налаштування фільтрів

                <md-button @click="resetFilters()" class="md-icon-button md-raised md-accent md-dense">
                    <md-icon>undo</md-icon>
                </md-button>
            </md-dialog-title>

            <md-dialog-content>
                Фільтри
            </md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary" @click="closeDialog()">Вихід</md-button>
                <md-button class="md-primary md-raised" @click="setFilters()">Застосувати</md-button>
            </md-dialog-actions>
        </md-dialog>

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
                me: null,
                logs: null,

                q: '',
                count: 25,
            }
        },

        created () {
            this.me = JSON.parse(this.me);
            this.logs = JSON.parse(this.inLogs);

            console.log(this.logs);
        },

        methods: {
            timestamp (date) {
                return moment(date).format('llll');
            },
            openDialog() {
                this.$refs['filters'].open();
            },
            closeDialog() {
                this.$refs['filters'].close();
                this.getLogs();
            },
            resetFilters() {
                this.q = '';
                this.count = 25;
                // TODO: ..
                this.closeDialog();
            },
            getLogs (page = 1) {
                axios.get('/logs/get', {
                    params: {
                        count: this.count,
                        q: this.q,
                        page: page,
                    }
                })
                    .then(res => this.logs = res.data);

                let offset = $('.pagination').offset().top;

                if (window.pageYOffset > offset) {
                    window.scrollTo(0, offset - 10)
                }
            },
        },

        watch: {
            q () {
                this.getLogs();
            }
        },
    }
</script>
