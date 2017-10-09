<template>
    <div class="template user" v-if="user">
        <div class="u-left">
            <div class="u-image">
                <img :src="image" :title="name" :alt="name">
            </div>
            <div class="u-info">
                <div class="u-name">{{ name }}</div>
                <div class="u-nickname">{{ user.nickname }}</div>
            </div>
        </div>

        <div class="u-right">
            <div class="u-content">
                <v-list two-line subheader>
                    <v-subheader inset>Контакти</v-subheader>
                    <v-list-tile>
                        <v-list-tile-avatar>
                            <v-icon>call</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ user.phone }}</v-list-tile-title>
                            <v-list-tile-sub-title>Домашній телефон</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile>
                        <v-list-tile-avatar>
                            <v-icon>call</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ user.phone }}</v-list-tile-title>
                            <v-list-tile-sub-title>Робочий телефон</v-list-tile-sub-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                            <v-icon>call</v-icon>
                        </v-list-tile-action>
                    </v-list-tile>
                    <v-list-tile>
                        <v-list-tile-avatar>
                            <v-icon>email</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ user.email }}</v-list-tile-title>
                            <v-list-tile-sub-title>Домашній email</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile>
                        <v-list-tile-avatar>
                            <v-icon>email</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ user.email_work }}</v-list-tile-title>
                            <v-list-tile-sub-title>Робочий телефон</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
            </div>
            <div class="u-action"> <!-- TODO: is any can -->
                <v-tooltip bottom> <!-- TODO: v-if -->
                    <v-btn flat icon color="primary" :to="'/user/' + user.id + '/edit'" slot="activator">
                        <v-icon>edit</v-icon>
                    </v-btn>
                    <span>Редагувати</span>
                </v-tooltip>
                <!--<v-tooltip bottom> &lt;!&ndash; TODO: v-if &ndash;&gt;-->
                <!--<v-btn flat icon color="red" slot="activator">-->
                <!--<v-icon>delete</v-icon>-->
                <!--</v-btn>-->
                <!--<span>Видалити</span>-->
                <!--</v-tooltip>-->
                <!--<v-tooltip bottom> &lt;!&ndash; TODO: v-if &ndash;&gt;-->
                <!--<v-btn flat icon color="black" slot="activator">-->
                <!--<v-icon>block</v-icon>-->
                <!--</v-btn>-->
                <!--<span>Заблокувати</span>-->
                <!--</v-tooltip>-->

                <!--<div class="btn-edit"> &lt;!&ndash; TODO: v-if &ndash;&gt;-->
                <!--<v-btn :to="'/user/' + user.id + '/edit'" outline block black color="primary">Заблокувати доступ</v-btn>-->
                <!--</div>-->
                <!--<div class="btn-edit"> &lt;!&ndash; TODO: v-if &ndash;&gt;-->
                <!--<v-btn :to="'/user/' + user.id + '/edit'" outline block black color="primary">Видалити</v-btn>-->
                <!--</div>-->

                <!--<v-tooltip bottom v-if="user.phone">-->
                <!--<v-btn flat icon color="pink" slot="activator" :href="'tel:+' + user.phone.replace(/\D/g, '')">-->
                <!--<v-icon>call</v-icon>-->
                <!--</v-btn>-->
                <!--<span>{{ user.phone }}</span>-->
                <!--</v-tooltip>-->
                <!--<v-tooltip bottom v-if="user.phone_work">-->
                <!--<v-btn flat icon color="pink" slot="activator" :href="'tel:+' + user.phone_work.replace(/\D/g, '')">-->
                <!--<v-icon>call</v-icon>-->
                <!--</v-btn>-->
                <!--<span>{{ user.phone_work }}</span>-->
                <!--</v-tooltip>-->
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
