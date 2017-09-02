<template>
    <md-layout class="list">
        <md-layout class="left-column" md-flex="75">
            <md-table @sort="onSort">
                <md-table-header>
                    <md-table-row>
                        <md-table-head md-sort-by="title">Назва</md-table-head>
                        <md-table-head md-sort-by="level">Рівень</md-table-head>
                        <md-table-head md-sort-by="users_count">Працівників</md-table-head>
                        <md-table-head></md-table-head>
                    </md-table-row>
                </md-table-header>

                <md-table-body>
                    <md-table-row v-for="(role, index) in roles.data" :key="role.id" class="list-row"
                                  :style="'border-left: 10px solid ' + role.color + ';'">
                        <md-table-cell>
                            <span class="title bold">{{ role.title }}</span>
                        </md-table-cell>

                        <md-table-cell>
                            <span>{{ role.level }}</span>
                        </md-table-cell>

                        <md-table-cell>
                            <span>{{ role.users_count }}</span>
                        </md-table-cell>

                        <md-table-cell>
                            <md-button :href="'/roles/' + role.id" class="md-icon-button">
                                <md-icon>remove_red_eye</md-icon>
                            </md-button>
                        </md-table-cell>
                    </md-table-row>
                </md-table-body>
            </md-table>

            <pagination :data="roles" :func="getRoles"></pagination>
        </md-layout>

        <md-layout class="right-column" md-flex="25">
            <md-input-container md-clearable>
                <md-icon>search</md-icon>
                <label>Пошук</label>
                <md-input v-model="q" autofocus></md-input>
            </md-input-container>

            <br>

            <md-input-container>
                <label for="count">Кількість ролей</label>
                <md-select name="count" id="count" v-model="count">
                    <md-option :value="10">10</md-option>
                    <md-option :value="25">25</md-option>
                    <md-option :value="50">50</md-option>
                    <md-option :value="75">75</md-option>
                    <md-option :value="100">100</md-option>
                </md-select>
            </md-input-container>
        </md-layout>
    </md-layout>
</template>

<script>
    export default {
        props: [
            'iUser', 'inRoles',
        ],

        data() {
            return {
                me: [],
                roles: [],

                q: '',
                count: 25,
                del: -1,

                sortColumn: '',
                sortType: '',
            }
        },

        created() {
            this.me = JSON.parse(this.iUser);
            this.roles = JSON.parse(this.inRoles);
        },

        methods: {
            getRoles(page = 1) {
                axios.get('/axios/roles.get', {
                    params: {
                        q: this.q,
                        count: this.count,
                        del: this.del,
                        page: page,

                        sortColumn: this.sortColumn,
                        sortType: this.sortType,
                    }
                })
                    .then(res => this.roles = res.data);

                $('body').animate({ scrollTop: $('.right-column')[0].offsetHeight + 48 }, 100);
                $('.md-table').animate({ scrollTop: 0 }, 100);
            },
            onSort(action) {
                this.sortColumn = action.name;
                this.sortType = action.type;
                this.getRoles(this.roles.current_page);
            },
        },

        watch: {
            q() {
                let len = this.q.length;

                if (len > 2 || len == 0) {
                    this.getRoles();
                }
            },
            count() {
                this.getRoles();
            },
        },
    }
</script>
