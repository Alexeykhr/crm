<template>
    <md-layout class="page" md-align="center">
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
