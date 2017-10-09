<template>
    <div class="template" v-if="user">
        <div class="user">
            <div class="u-left">
                <div class="u-image">
                    <img :src="image" :title="name" :alt="name">
                </div>
                <div class="u-info">
                    <div class="u-name">{{ name }}</div>
                    <div class="u-nickname">{{ user.nickname }}</div>
                </div>
                <v-btn color="primary" block outline :to="'/user/' + user.id + '/edit'">
                    Редагувати
                </v-btn>
            </div>

            <div class="u-right">
                <div class="u-content">
                    <v-list two-line subheader v-if="user.phone || user.phone_work || user.email || user.email_works">
                        <v-subheader inset>Контакти</v-subheader>
                        <v-list-tile v-if="user.phone" :href="'tel:+' + user.phone.replace(/\D/g, '')">
                            <v-list-tile-avatar>
                                <v-icon>call</v-icon>
                            </v-list-tile-avatar>
                            <v-list-tile-content>
                                <v-list-tile-title>{{ user.phone }}</v-list-tile-title>
                                <v-list-tile-sub-title>Домашній телефон</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile v-if="user.phone_work" :href="'tel:+' + user.phone_work.replace(/\D/g, '')">
                            <v-list-tile-avatar>
                                <v-icon>call</v-icon>
                            </v-list-tile-avatar>
                            <v-list-tile-content>
                                <v-list-tile-title>{{ user.phone }}</v-list-tile-title>
                                <v-list-tile-sub-title>Робочий телефон</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile v-if="user.email" :href="'mailto:' + user.email">
                            <v-list-tile-avatar>
                                <v-icon>email</v-icon>
                            </v-list-tile-avatar>
                            <v-list-tile-content>
                                <v-list-tile-title>{{ user.email }}</v-list-tile-title>
                                <v-list-tile-sub-title>Домашній email</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile v-if="user.email_work" :href="'mailto:' + user.email_work">
                            <v-list-tile-avatar>
                                <v-icon>email</v-icon>
                            </v-list-tile-avatar>
                            <v-list-tile-content>
                                <v-list-tile-title>{{ user.email_work }}</v-list-tile-title>
                                <v-list-tile-sub-title>Робочий телефон</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list>

                    <!-- TODO: get job and role -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Auth from '../../store/auth';
    import { get } from '../../helpers/api';

    export default {
        data() {
            return {
                user: null,
            }
        },

        created() {
            get('/api/users/get', {
                id: this.$route.params.id
            })
                .then(res => {
                    this.user = res.data;

                    if (this.$route.params.id == Auth.state.user.id) {
                        Auth.setUser(this.user);
                    }
                });
        },

        computed: {
            name() {
                return this.user.first_name + ' ' + this.user.last_name;
            },
            image() {
                return this.user.image ? this.user.image : '/img/user.png';
            },
        }
    }
</script>
