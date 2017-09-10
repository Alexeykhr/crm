<template>
    <md-layout class="page">
        {{ u.name }}
    </md-layout>
</template>

<script>
    export default {
        props: [
            'user', 'canEdit',
        ],

        data () {
            return {
                u: null,
                isProfile: true,
            }
        },

        created () {
            if (this.user) {
                this.u = JSON.parse(this.user);
                this.isProfile = false;
            }

            console.log(this.canEdit);
        },
    }
</script>
