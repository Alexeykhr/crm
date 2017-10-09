<template>
    <div class="template" v-if="user">
        <v-alert color="error" icon="warning" value="true" v-if="!user.is_active">
            Без доступу до сайту.
        </v-alert>

        <div class="user">
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
                                    <v-list two-line subheader>
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
                                </v-card>
                            </v-tabs-content>
                        </v-tabs-items>
                    </v-tabs>

                    <!-- TODO: get job and role -->
                </div>

                <div class="u-action">
                    <v-tooltip left>
                        <!--TODO: if can-->
                        <v-btn icon color="grey darken-4" slot="activator" :to="'/user/' + user.id + '/edit'"
                               class="white--text">
                            <v-icon>edit</v-icon>
                        </v-btn>
                        <span>Редагувати</span>
                    </v-tooltip>
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

                items: ['Контакти', 'Інше'],
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
                // TODO: img 200x200 px
                return this.user.image ? this.user.image : '/img/user.png';
            },
        }
    }
</script>
