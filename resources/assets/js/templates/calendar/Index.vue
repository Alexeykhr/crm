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
                <div class="item" v-for="day in daysEmpty"></div>
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
            'iUser', 'inUsers', 'inMonth', 'inYear',
        ],

        data() {
            return {
                me: [],
                users: [],
                month: 0,
                year: 0,

                sortableUsers: [],
                daysEmpty: 0,
                daysInMonth: 0,
                selectedMonth: '00 / 0000',
                loading: false,
            }
        },

        created() {
            this.me = JSON.parse(this.iUser);
            this.month = this.inMonth;
            this.year = this.inYear;

            this.sortUsers(JSON.parse(this.inUsers));

            this.daysInMonth = moment().daysInMonth();
            this.daysEmpty = moment().date(1).weekday();

            this.selectedMonth = this.month + ' / ' + this.year;
        },

        methods: {
            eventClass(day) {
                let classes = 'item';

                if (this.sortableUsers[day] != null) {
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
                this.sortableUsers = [];

                axios.get('/axios/calendar.get', {
                    params: {
                        month: this.month,
                    }
                })
                    .then(res => this.sortUsers(res.data))
                    .catch(error => console.log('Error: ' + this.error));

                this.selectedMonth = this.month + ' / ' + this.year;
                this.daysEmpty = moment(this.year+'.'+this.month+'.'+1, 'YYYY.MM.DD').weekday();
            },
            sortUsers(users) {
                let count = users.length,
                    arr = [];

                for (let i = 0; i < count; i++) {
                    let date = moment(users[i].birth).date();

                    if (arr[date] == null) {
                        arr[date] = [];
                    }

                    arr[date].push(users[i]);
                }

                this.sortableUsers = arr;
            }
        },
    }
</script>
