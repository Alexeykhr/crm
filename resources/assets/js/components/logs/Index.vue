<template>
    <md-layout class="page" md-flex-xsmall="100" md-flex-small="50" md-flex-medium="100" md-align="center" v-once>
        <md-table>
            <md-table-header>
                <md-table-row>
                    <md-table-head>Користувач</md-table-head>
                    <md-table-head>Дія</md-table-head>
                    <md-table-head>Опис</md-table-head>
                    <md-table-head>Дата</md-table-head>
                    <md-table-head>Лінк</md-table-head>
                </md-table-row>
            </md-table-header>

            <md-table-body>
                <md-table-row v-for="log in logs.data" :key="logs.id">

                    <md-table-cell>{{ log.user_id }}</md-table-cell>
                    <md-table-cell>{{ log.action }}</md-table-cell>
                    <md-table-cell>{{ log.desc }}</md-table-cell>
                    <md-table-cell>{{ timestamp(log.date) }}</md-table-cell>
                    <!--<md-table-cell v-if="log.ref_id">-->
                    <md-table-cell>
                        <md-button :href="'/u/'+log.user_id" class="md-icon-button">
                            <md-icon>remove_red_eye</md-icon>
                        </md-button>
                    </md-table-cell>

                </md-table-row>
            </md-table-body>

        </md-table>
    </md-layout>
</template>

<script>
    var moment = require('moment');
    moment.locale('uk');

    export default {
        props: [
            'data',
        ],

        data () {
            return {
                logs: null,
            }
        },

        created () {
            this.logs = JSON.parse(this.data);

            console.log(this.logs);
        },

        methods: {
            timestamp: function(date) {
                return moment(date).format('llll');
            }
        },
    }
</script>
