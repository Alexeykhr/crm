<template>
    <md-layout class="page" md-flex-xsmall="100" md-flex-small="50" md-flex-medium="100" md-align="center">
        {{ u.name }}
    </md-layout>
</template>

<script>
    export default {
        props: [
            'user', 'role', 'job',
        ],

        data () {
            return {
                u: null,
                r: null,
                j: null,
            }
        },

        created () {
            this.u = JSON.parse(this.user);
            this.r = JSON.parse(this.role);
            this.j = JSON.parse(this.job);

            console.log(this.u);
            console.log(this.r);
            console.log(this.j);
        },
    }
</script>
