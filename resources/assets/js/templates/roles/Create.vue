<template>
    <md-whiteframe md-elevation="2" class="page">
        <md-table v-once>
            <md-table-header>
                <md-table-row>
                    <md-table-head>Назва</md-table-head>
                    <md-table-head>Перегляд</md-table-head>
                    <md-table-head>Редагування</md-table-head>
                    <md-table-head>Створення</md-table-head>
                    <md-table-head>Видалення</md-table-head>
                </md-table-row>
            </md-table-header>

            <md-table-body>
                <md-table-row v-for="(row, i) in 5" :key="i">
                    <md-table-cell>Dessert Name</md-table-cell>
                    <md-table-cell v-for="(n, j) in 4" :key="j">
                        <md-checkbox v-model="action[i][j]"></md-checkbox>
                    </md-table-cell>
                </md-table-row>
            </md-table-body>

            <md-button @click="test">Test</md-button>
        </md-table>
    </md-whiteframe>
</template>

<script>
    export default {
        props: [
            'iUser',
        ],

        data() {
            return {
                me: [],
                action: [[], [], [], [], []],
                name: ['Користувачі', '', '', '']
            }
        },

        created() {
            this.me = JSON.parse(this.iUser);

            console.log(this.me);
        },

        methods: {
            findUser() {

            },
            test() {
                console.log(this.action);
            },
        },
    }
</script>
