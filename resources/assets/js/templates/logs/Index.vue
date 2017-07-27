<template>
    <md-layout class="page" md-align="center" v-once>

        <paginate :data="logs" :attr="getCurrentAttribute()"></paginate>

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

                    <md-table-cell>
                        <md-button class="btn_width" :href="'/u/'+log.user_id">
                            {{ log.user.name }}
                        </md-button>
                    </md-table-cell>
                    <md-table-cell>{{ log.action }}</md-table-cell>
                    <md-table-cell>{{ log.desc }}</md-table-cell>
                    <md-table-cell>{{ timestamp(log.date) }}</md-table-cell>
                    <!--<md-table-cell v-if="log.ref_id">-->
                    <md-table-cell>
                        <md-button v-if="log.module != 'auth'" :href="'/u/'+log.ref_id" class="md-icon-button">
                            <md-icon>remove_red_eye</md-icon>
                        </md-button>
                    </md-table-cell>

                </md-table-row>
            </md-table-body>
        </md-table>

        <paginate :data="logs" :attr="getCurrentAttribute()"></paginate>

        <md-button class="md-fab md-primary btn_fixed_bl" id="fab" @click="openDialog('filters')">
            <md-icon>filter_list</md-icon>
        </md-button>

        <md-dialog md-open-from="#fab" md-close-to="#fab" ref="filters">
            <md-dialog-title>Налаштування фільтрів

                <md-button @click="resetFilters()" class="md-icon-button md-raised md-accent md-dense">
                    <md-icon>undo</md-icon>
                </md-button>
            </md-dialog-title>

            <md-dialog-content>
                Фільтри
            </md-dialog-content>

            <md-dialog-actions>
                <md-button class="md-primary" @click="closeDialog('filters')">Вихід</md-button>
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
            timestamp (date) {
                return moment(date).format('llll');
            },
            openDialog(ref) {
                this.$refs[ref].open();
            },
            closeDialog(ref) {
                this.$refs[ref].close();
            },
            setFilters() {
                location.href = this.users.path+'?page=1'+this.getNewAttribute();
            },
            resetFilters() {
                location.href = this.users.path;
            },
            getNewAttribute() {
                return '';
            },
            getCurrentAttribute() {
                return '';
            },
        },
    }
</script>
