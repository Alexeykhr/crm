<template>
    <div class="template user" v-if="user">
        <div class="u-left">
            <div class="image">
                <img :src="image" :title="name" :alt="name">
            </div>
            <div class="info">

            </div>
        </div>

        <div class="u-right">

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
