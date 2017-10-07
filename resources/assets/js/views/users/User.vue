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
                <div class="u-contacts">
                    <h5>Контакти</h5>
                    <div class="u-block-contacts">
                        <span class="ttl">Домашній телефон</span>
                        <v-icon>call</v-icon>
                        <span>{{ user.phone }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
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
                .then(res => this.user = res.data);
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
