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
                <div :class="eventClass(day)" v-for="day in daysInMonth">
                    <span>{{ day }}</span>
                </div>
            </div>

            <!--TODO: Test-->
            <div class="loading" v-show="loading"><span>Завантажується..</span></div>
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

            this.daysInMonth = moment().daysInMonth();
            this.daysEmpty = moment().day() - moment().date();
            this.selectedMonth = this.month + ' / ' + this.year;
        },

        methods: {
            // TODO: very slow
            eventClass(day) {
                let classes = 'item';

                for (let i = 0; i < this.users.length; i++) {
                    if (moment(this.users[i].birth).date() == day) {
                        classes += ' event';
                        break;
                    }
                }

                if (moment().year() == this.year && moment().format('M') == this.month && moment().date() == day) {
                    classes += ' today';
                }

                console.log('Event');
                this.loading = false;
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
                this.loading = true;

                axios.get('/axios/calendar.get', {
                    params: {
                        month: this.month,
                    }
                })
                    .then(res => this.users = res.data)
                    .catch(error => console.log('Error: ' + this.error));

                console.log('--------------');
                this.selectedMonth = this.month + ' / ' + this.year;
            },
        },
    }
</script>
