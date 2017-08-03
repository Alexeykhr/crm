<template>
    <div>
        {{ me.name }}
    </div>
</template>

<script>
    let moment = require('moment');
    moment.locale('uk');

    export default {
        props: [
            'iUser', 'inUsers',
        ],

        data() {
            return {
                me: [],
                users: [],
            }
        },

        created() {
            this.me = JSON.parse(this.iUser);
            this.users = JSON.parse(this.inUsers);

//            console.log(this.me);
//            console.log(this.users);
            console.log(moment().daysInMonth());
        },
    }
</script>
