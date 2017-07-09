<template>
    <md-layout class="page" md-flex-xsmall="100" md-flex-small="50" md-flex-medium="100" md-align="center" v-once>

    </md-layout>
</template>

<script>
    export default {
        props: [
            'data',
        ],

        data () {
            return {
                logs: null,
            }
        },

        created () {
            this.logs = JSON.parse(this.data);

            console.log(this.logs);
        },
    }
</script>
