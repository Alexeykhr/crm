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
                </div>

                <div class="u-action">
                    <v-tooltip bottom>
                        <v-btn icon color="primary" :to="'/user/' + user.id" slot="activator">
                            <v-icon color="white">arrow_back</v-icon>
                        </v-btn>
                        <span>Вийти</span>
                    </v-tooltip>
                    <v-tooltip bottom v-if="myId != $route.params.id">
                        <v-btn flat icon color="red" slot="activator" @click="deleteUser">
                            <v-icon>delete</v-icon>
                        </v-btn>
                        <span>Видалити</span>
                    </v-tooltip>
                    <v-tooltip bottom v-if="myId != $route.params.id">
                        <v-btn flat icon color="black" slot="activator" @click="blockUser">
                            <v-icon>{{ user.is_active ? 'lock_outline' : 'lock_open' }}</v-icon>
                        </v-btn>
                        <span>Заблокувати</span>
                    </v-tooltip>
                </div>
            </div>
        </div>

        <v-btn color="primary" block @click="saveUser" :disabled="isProcessing">
            Зберегти
        </v-btn>
    </div>
</template>

<script>
    import AuthState from '../../store/auth';
    import { get, post } from '../../helpers/api';

    export default {
        data() {
            return {
                user: null,
                isProcessing: false,
            }
        },

        created() {
            get('/api/users/get', {
                id: this.$route.params.id
            })
                .then(res => {
                    this.user = res.data;

                    if (this.$route.params.id == AuthState.state.user.id) {
                        AuthState.setUser(this.user);
                    }
                });
        },

        methods: {
            saveUser() {
                console.log('Save');
            },
            blockUser() {
                post('/api/users/' + this.$route.params.id + '/block')
                    .then(res => console.log(res))
                    .catch(err => console.log(err));
            },
            deleteUser() {

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
                return this.user.image ? this.user.image : '/img/user.png';
            },
        }
    }
</script>
