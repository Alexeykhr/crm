<template>
    <div class="template user">
        <div class="u-left">
            <div class="u-image">
                <img :src="image" :title="name" :alt="name">
            </div>
            <div class="u-info">
                <div class="u-name">{{ name }}</div>
                <div class="u-nickname">{{ user.nickname }}</div>
            </div>
            <div class="btn-edit"> <!-- TODO: v-if -->
                <v-btn to="/profile/edit" block black color="primary">Редагувати</v-btn>
            </div>
        </div>

        <div class="u-right">
            <div class="u-content">
                <div class="u-contacts">
                    <h5>Контакти</h5>
                    <div class="u-block-contacts">
                        <div class="phone" v-if="user.phone">
                            <span class="ttl">Домашній телефон</span>
                            <v-icon>call</v-icon>
                            <span>{{ user.phone }}</span>
                        </div>
                        <div class="phone-work" v-if="user.phone_work">
                            <span class="ttl">Робочій телефон</span>
                            <v-icon>call</v-icon>
                            <span>{{ user.phone_work }}</span>
                        </div>
                        <div class="email" v-if="user.email">
                            <span class="ttl">Домашній email</span>
                            <v-icon>email</v-icon>
                            <span>{{ user.email }}</span>
                        </div>
                        <div class="email-work" v-if="user.email_work">
                            <span class="ttl">Робочій email</span>
                            <v-icon>email</v-icon>
                            <span>{{ user.email_work }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Auth from '../../store/auth';

    export default {
        data() {
            return {
                user: Auth.state.user,
            }
        },

        computed: {
            name() {
                return this.user.first_name + ' ' +
                    (this.user.middle_name ? this.user.middle_name + ' ' : '') + this.user.last_name;
            },
            image() {
                return this.user.image ? this.user.image : '/img/user.png';
            },
        }
    }
</script>
