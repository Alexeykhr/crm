<template>
    <md-layout class="page" md-flex-xsmall="100" md-flex-small="50" md-flex-medium="100" md-align="center">
        <span>{{ u.name }}</span>
    </md-layout>
</template>

<script>
    export default {
        props: [
            'user', 'role',
        ],

        data () {
            return {
                u: null,
                r: null,
            }
        },

        created () {
            this.u = JSON.parse(this.user);
            this.r = JSON.parse(this.role);

            console.log(this.u);
            console.log(this.r);
        },
    }
</script>
