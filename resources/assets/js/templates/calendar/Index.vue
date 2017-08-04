<template>
    <div class="calendar">
        <div class="header">

        </div>
        <div class="body">
            <div class="weeks">
                <span class="item">Пн</span>
                <span class="item">Вт</span>
                <span class="item">Ср</span>
                <span class="item">Чт</span>
                <span class="item">Пт</span>
                <span class="item">Сб</span>
                <span class="item">Нд</span>
            </div>
            <div class="dates">
                <template v-if="daysEmpty > 0">
                    <div class="item" v-for="day in daysEmpty"></div>
                </template>
                <div :class="eventClass(day)" v-for="day in daysInMonth">
                    <span>{{ day }}</span>
                </div>
            </div>
        </div>
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

            this.daysInMonth = moment().daysInMonth();
            this.daysEmpty = moment().day() - moment().date();

//            console.log(this.users);
            console.log(this.daysEmpty);
            console.log(moment().date());
        },

        mounted() {

        },

        methods: {
            eventClass(day) {
                let classes = 'item';

                for (let i = 0; i < this.users.length; i++) {
                    if (moment(this.users[i].birth).date() == day) {
                        classes += ' event';
                        break;
                    }
                }

                if (moment().date() == day) {
                    classes += ' today';
                }

                return classes;
            }
        },
    }
</script>
