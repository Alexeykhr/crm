<template>
    <md-layout class="page" md-align="center">
        {{ me.name }}
    </md-layout>
</template>

<script>
    export default {
        props: [
            'user', 'canEdit',
        ],

        data () {
            return {
                me: null,
                u: null,
                isProfile: true,
            }
        },

        created () {
            this.me = JSON.parse(this.iUser);

            if (this.user) {
                this.u = JSON.parse(this.user);
                this.isProfile = false;
            }

            console.log(this.canEdit);
        },
    }
</script>
