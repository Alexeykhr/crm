<template>
    <!--TODO: complete page-->
    <md-whiteframe md-elevation="2" class="page action max">
        <form novalidate @submit.stop.prevent="submit">
            <div class="header">
                <template v-if="inRole">
                    <h1 :title="'Роль: #' + role.id">Роль: #{{ role.id }}</h1>
                </template>
                <template v-else>
                    <h1 title="Створення ролі">Створення ролі</h1>
                </template>
            </div>

            <md-input-container :class="duplicateTitle ? 'md-input-invalid' : ''">
                <label>Назва</label>
                <md-input :readonly="action == 'view'" :maxlength="60" v-model="title" @change.native="findRole()"
                          required autofocus></md-input>
                <span v-if="duplicateTitle" class="md-error">Роль вже існує</span>
            </md-input-container>

            <md-input-container>
                <label>Опис</label>
                <md-textarea :readonly="action == 'view'" v-model="desc" maxlength="255"></md-textarea>
            </md-input-container>

            <md-input-container>
                <label>Рівень</label>
                <md-textarea :readonly="action == 'view'" v-model="desc"></md-textarea>
            </md-input-container>

            <md-table>
                <md-table-header>
                    <md-table-row>
                        <md-table-head>Модуль</md-table-head>
                        <md-table-head><md-icon>remove_red_eye</md-icon></md-table-head>
                        <md-table-head><md-icon>edit</md-icon></md-table-head>
                        <md-table-head><md-icon>add</md-icon></md-table-head>
                        <md-table-head><md-icon>delete</md-icon></md-table-head>
                        <md-table-head><md-icon>people</md-icon></md-table-head>
                    </md-table-row>
                </md-table-header>

                <md-table-body>
                    <md-table-row>
                        <md-table-cell>Користувачі</md-table-cell>
                        <md-table-cell v-for="(col, index) in 4" :key="index">
                            <md-checkbox></md-checkbox>
                        </md-table-cell>
                        <md-table-cell></md-table-cell>
                    </md-table-row>

                    <md-table-row>
                        <md-table-cell>Ролі</md-table-cell>
                        <md-table-cell v-for="(col, index) in 5" :key="index">
                            <md-checkbox></md-checkbox>
                        </md-table-cell>
                    </md-table-row>

                    <md-table-row>
                        <md-table-cell>Посада</md-table-cell>
                        <md-table-cell v-for="(col, index) in 5" :key="index">
                            <md-checkbox></md-checkbox>
                        </md-table-cell>
                    </md-table-row>

                    <md-table-row>
                        <md-table-cell>Календар</md-table-cell>
                        <md-table-cell v-for="(col, index) in 4" :key="index">
                            <md-checkbox></md-checkbox>
                        </md-table-cell>
                        <md-table-cell></md-table-cell>
                    </md-table-row>
                </md-table-body>
            </md-table>

            <md-button v-if="action == 'create'" :disabled="search || this.title.length < 3"
                       class="md-raised md-primary btn-action" @click="createRole()">
                Створити
            </md-button>
            <md-button v-if="action == 'edit'" :disabled="search || this.title.length < 3"
                       class="md-raised md-primary btn-action" @click="updateRole()">
                Оновити
            </md-button>
        </form>

        <md-snackbar class="snackbar-black" ref="snackbar" :md-duration="2000">
            <span>{{ response }}</span>
            <md-button @click="$refs.snackbar.close()">Сховати</md-button>
        </md-snackbar>
    </md-whiteframe>
</template>

<script>
    import MdIcon from "../../../../../node_modules/vue-material/src/components/mdIcon/mdIcon.vue";

    export default {
        components: {MdIcon},
        props: [
            'iUser', 'inRole', 'action',
        ],

        data() {
            return {
                me: [],
                role: [],
                title: '',
                desc: '',

                search: false,
                duplicateTitle: false,
                response: '',
            }
        },

        created() {
            this.me = JSON.parse(this.iUser);

            if (this.inRole) {
                this.job = JSON.parse(this.inRole);
            }

            console.log(this.action);
        },

        methods: {
            findRole() {
                this.duplicateTitle = false;

                if (this.title.length < 3) {
                    return;
                }

                if (this.inRole) {
                    if (this.job.title.toLowerCase() === this.title.toLowerCase()) {
                        return;
                    }
                }

                axios.get('/roles.exist', {
                    params: {
                        title: this.title,
                    }
                })
                    .then(res => {
                        this.search = res.data;

                        if (res.data) {
                            this.duplicateTitle = true;
                        }
                    });
            },
            createRole() {

            },
            updateRole() {

            },
        },
    }
</script>
