<template>
    <span>It's dashboard</span>
</template>

<script>
    import { get } from '../helpers/api';

    export default {
        created() {
            get('/api/v1/me');
        }
    }
</script>
