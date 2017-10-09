<template>
    <div class="template" v-if="user">
        <div class="user">
            <div class="u-left">
                <div class="u-image">
                    <!--TODO: download image-->
                    <img :src="image" :title="name" :alt="name">
                </div>
                <div class="u-info">
                    <v-text-field
                            label="Логін"
                            :counter="40"
                            v-model="user.nickname"
                            :disabled="!user.is_active"
                    ></v-text-field>
                </div>
            </div>

            <div class="u-right">
                <div class="u-content">
                    <v-tabs light grow>
                        <v-tabs-bar class="grey lighten-4">
                            <v-tabs-slider></v-tabs-slider>
                            <v-tabs-item
                                    v-for="(item, i) in items"
                                    :key="i"
                                    :href="'#tab-' + (i + 1)"
                            >
                                {{ item }}
                            </v-tabs-item>
                        </v-tabs-bar>

                        <v-tabs-items>
                            <v-tabs-content id="tab-1">
                                <v-card flat>
                                    <v-card-text>
                                        <v-text-field
                                                label="Ім'я"
                                                :counter="20"
                                                v-model="user.first_name"
                                                :disabled="!user.is_active"
                                        ></v-text-field>
                                        <v-text-field
                                                label="По-батькові"
                                                :counter="20"
                                                v-model="user.middle_name"
                                                :disabled="!user.is_active"
                                        ></v-text-field>
                                        <v-text-field
                                                label="Прізвище"
                                                :counter="20"
                                                v-model="user.last_name"
                                                :disabled="!user.is_active"
                                        ></v-text-field>
                                    </v-card-text>
                                </v-card>
                            </v-tabs-content>
                            <v-tabs-content id="tab-2">
                                <v-card flat>
                                    <v-card-text>
                                        <v-text-field
                                                label="Домашній телефон"
                                                :counter="30"
                                                v-model="user.phone"
                                                :disabled="!user.is_active"
                                        ></v-text-field>
                                        <v-text-field
                                                label="Робочий телефон"
                                                :counter="30"
                                                v-model="user.phone_work"
                                                :disabled="!user.is_active"
                                        ></v-text-field>
                                        <v-text-field
                                                label="Домашній email"
                                                :counter="50"
                                                v-model="user.email"
                                                :disabled="!user.is_active"
                                        ></v-text-field>
                                        <v-text-field
                                                label="Робочий email"
                                                :counter="50"
                                                v-model="user.email_work"
                                                :disabled="!user.is_active"
                                        ></v-text-field>
                                    </v-card-text>
                                </v-card>
                            </v-tabs-content>
                        </v-tabs-items>
                    </v-tabs>
                </div>

                <div class="u-action">
                    <v-tooltip left>
                        <v-btn icon color="grey darken-4" :to="'/user/' + user.id" slot="activator" class="white--text">
                            <v-icon>arrow_back</v-icon>
                        </v-btn>
                        <span>Вийти</span>
                    </v-tooltip>
                    <v-tooltip left>
                        <v-btn flat icon color="primary" slot="activator" :disabled="isProcessing || !user.is_active" @click="saveUser">
                            <v-icon>save</v-icon>
                        </v-btn>
                        <span>Зберегти</span>
                    </v-tooltip>
                    <v-tooltip left>
                        <v-btn flat icon color="purple" slot="activator" :disabled="isProcessing" @click="changePassword">
                            <v-icon>security</v-icon>
                        </v-btn>
                        <span>Змінити пароль</span>
                    </v-tooltip>
                    <v-tooltip left v-if="myId != $route.params.id">
                        <v-btn flat icon color="red" slot="activator" :disabled="isProcessing" @click="deleteUser">
                            <v-icon>delete</v-icon>
                        </v-btn>
                        <span>Видалити</span>
                    </v-tooltip>
                    <v-tooltip left v-if="myId != $route.params.id">
                        <v-btn flat icon color="black" slot="activator" :disabled="isProcessing" @click="activeUser">
                            <v-icon>{{ user.is_active ? 'lock_outline' : 'lock_open' }}</v-icon>
                        </v-btn>
                        <span>{{ user.is_active ? 'Заблокувати' : 'Розблокувати' }}</span>
                    </v-tooltip>
                </div>
            </div>
        </div>

        <v-snackbar :color="snackbar.color" :timeout="snackbar.timeout" v-model="snackbar.model">
            {{ snackbar.text }}
            <v-btn dark flat @click.native="snackbar.model = false">Сховати</v-btn>
        </v-snackbar>
    </div>
</template>

<script>
    import AuthState from '../../store/auth';
    import { get, put, del } from '../../helpers/api';

    export default {
        data() {
            return {
                user: null,

                password: null,
                password_repeat: null,

                isProcessing: false,

//                TODO: Move to global snackbar
                snackbar: {
                    timeout: 2000,
                    model: false,
                    color: '',
                    text: ''
                },

                items: [
                    'Інформація', 'Контакти',
                ],
                text: 'Lorem ipsum dolor sit amet, aliquip ex ea commodo consequat.'
            }
        },

        created() {
            get('/api/users/get', {
                id: this.$route.params.id
            })
                .then(res => {
                    this.user = res.data;

                    if (this.$route.params.id == this.myId) {
                        AuthState.setUser(this.user);
                    }
                });
        },

        methods: {
            saveUser() {
                console.log('Save');
            },
            activeUser() {
                this.isProcessing = true;

                put('/api/users/' + this.$route.params.id + '/active')
                    .then(res => {
                        this.showSnackbar(res.data, 'success');
                        this.user.is_active = !this.user.is_active;
                        this.isProcessing = false;
                    })
                    .catch(err => {
                        this.showSnackbar(err.response.data, 'error');
                        this.isProcessing = false;
                    });
            },
            deleteUser() {
                this.isProcessing = true;

                del('/api/users/' + this.$route.params.id)
                    .then(res => {
//                        TODO: show snackbar - user is deleted
                        this.$router.push({ name: 'dashboard' });
                    })
                    .catch(err => {
                        this.showSnackbar(err.response.data, 'error');
                        this.isProcessing = false;
                    });
            },
            changePassword() {

            },
            showSnackbar(text, color) {
                this.snackbar.model = true;
                this.snackbar.text = text;
                this.snackbar.color = color;
            },
        },

        computed: {
            myId() {
                return AuthState.state.user.id;
            },
            name() {
                return this.user.first_name + ' ' + this.user.last_name;
            },
            image() {
                // TODO: img 200x200 px
                return this.user.image ? this.user.image : '/img/user.png';
            },
        },
    }
</script>
