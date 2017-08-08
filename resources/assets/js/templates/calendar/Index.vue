<template>
    <div class="calendar">
        <div class="header">
            <md-button @click="prev()" class="md-raised">
                <md-icon>keyboard_arrow_left</md-icon>
            </md-button>

            <span>{{ selectedMonth }}</span>

            <md-button @click="next()" class="md-raised">
                <md-icon>keyboard_arrow_right</md-icon>
            </md-button>
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
                <div :class="eventClass(index)" v-for="(user, index) in sortUsers()">
                    <span>{{ index }}</span>
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
            'iUser', 'inUsers', 'inMonth', 'inYear',
        ],

        data() {
            return {
                me: [],
                users: [],
                month: 0,
                year: 0,

                daysEmpty: 0,
                daysInMonth: 0,
                selectedMonth: '00 / 0000',
                loading: false,
            }
        },

        created() {
            this.me = JSON.parse(this.iUser);
            this.users = JSON.parse(this.inUsers);
            this.month = this.inMonth;
            this.year = this.inYear;

            this.sortUsers();

            this.daysInMonth = moment().daysInMonth();
            this.daysEmpty = moment().day() - 1;

            this.selectedMonth = this.month + ' / ' + this.year;
        },

        methods: {
            // TODO: very slow
            eventClass(day) {
                let classes = 'item';

                if (this.users[day] != null) {
                    classes += ' event';
                }

                if (moment().year() == this.year && moment().format('M') == this.month && moment().date() == day) {
                    classes += ' today';
                }

                return classes;
            },
            prev() {
                if (--this.month < 1) {
                    this.year--;
                    this.month = 12;
                }

                this.getMonth();
            },
            next () {
                if (++this.month > 12) {
                    this.year++;
                    this.month = 1;
                }

                this.getMonth();
            },
            getMonth() {
                axios.get('/axios/calendar.get', {
                    params: {
                        month: this.month,
                    }
                })
                    .then(res => this.users = res.data)
                    .catch(error => console.log('Error: ' + this.error));

                this.selectedMonth = this.month + ' / ' + this.year;
                this.daysEmpty = moment().year(this.year).month(this.month).day(1);
//                console.log(moment().date());
            },
            sortUsers() {
                let count = this.users.length,
                    arr = [];

                for (let i = 0; i < count; i++) {
                    let date = moment(this.users[i].birth).date();

                    if (arr[date] == null) {
                        arr[date] = [];
                    }

                    arr[date].push(this.users[i]);
                }

                return arr;
            }
        },
    }
</script>
