<template>
    <md-layout class="list">
        <md-layout class="left-column" md-flex="75">
            <pagination :data="roles" :func="getRoles"></pagination>

            <md-table>
                <md-table-header>
                    <md-table-row>
                        <md-table-head>Назва</md-table-head>
                        <md-table-head>Рівень</md-table-head>
                        <md-table-head>Працівників</md-table-head>
                        <md-table-head></md-table-head>
                    </md-table-row>
                </md-table-header>

                <md-table-body>
                    <md-table-row v-for="role in roles.data" :key="role.id"
                                  :style="(!role.delete && role.active && role.color ?
                                  'border-left: 10px solid ' + role.color : '') + ';'">
                        <md-table-cell>
                            <span>{{ role.title }}</span>
                        </md-table-cell>

                        <md-table-cell>
                            <span>{{ role.level }}</span>
                        </md-table-cell>

                        <md-table-cell>
                            <span>{{ role.users_count }}</span>
                        </md-table-cell>

                        <md-table-cell>
                            <md-button :href="'/roles/'+role.id" class="md-icon-button">
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

            <div class="choose">
                <span>Активний</span>
                <md-radio v-model="active" name="active" md-value="1">Так</md-radio>
                <md-radio v-model="active" name="active" md-value="0">-</md-radio>
                <md-radio v-model="active" name="active" md-value="-1">Ні</md-radio>
            </div>

            <div class="choose">
                <span>Видалений</span>
                <md-radio v-model="del" name="delete" md-value="1">Так</md-radio>
                <md-radio v-model="del" name="delete" md-value="0">-</md-radio>
                <md-radio v-model="del" name="delete" md-value="-1">Ні</md-radio>
            </div>
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
                active: 0,
                del: -1,
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
                        active: this.active,
                        page: page,
                    }
                })
                    .then(res => this.roles = res.data)
                    .catch(error => console.log('Error: ' + this.error));

                $('.left-column').scrollTop(0);
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
            del() {
                this.getRoles();
            },
            active() {
                this.getRoles();
            },
        },
    }
</script>
