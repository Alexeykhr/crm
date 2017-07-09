<template>
    <md-layout class="page" md-flex-xsmall="100" md-flex-small="50" md-flex-medium="100" md-align="center">
        {{ u.name }}
    </md-layout>
</template>

<script>
    export default {
        props: [
            'iUser',
        ],

        data () {
            return {
                u: null,
            }
        },

        created () {
            this.u = JSON.parse(this.iUser);
        },
    }
</script>
