<template>

</template>

<script>
    import { get } from '../../helpers/api';

    export default {
        data() {
            return {
                count: 10,
            }
        },

        created() {
            this.all();
        },

        methods: {
            all() {
                get('/api/users/all', {
                    count: this.count,
                })
                    .then(res => console.log(res));
            },
        },
    }
</script>
