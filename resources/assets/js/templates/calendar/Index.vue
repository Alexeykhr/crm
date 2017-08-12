<template>
    <div class="calendar">
        <div class="left-column">
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
                    <div :class="eventClass(day)" v-for="day in daysInMonth" @click="selectDay(day)">
                        <span>{{ day }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="right-column">
            <div class="header">
                <span v-if="selectedDay">{{ formatSelectedDay() }}</span>
                <span v-else>Виберіть день</span>
            </div>

            <div class="body">
                <md-whiteframe md-elevation="2" class="phone-viewport">
                    <md-list class="md-double-line">
                        <md-list-item v-for="(user, index) in selectedUsers" :key="index">
                            <md-avatar>
                                <img :src="user.photo ? user.photo : 'img/user.png'" :alt="user.name">
                            </md-avatar>

                            <div class="md-list-text-container">
                                <span>{{ user.name }}</span>
                                <span>{{ formatBirthday(user.birth) }}</span>
                            </div>

                            <md-icon>cake</md-icon>
                        </md-list-item>
                    </md-list>
                </md-whiteframe>
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
                selectedDay: null,
                selectedUsers: [],
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

            this.selectDay(moment().date());
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

                if (this.selectedDay == day) {
                    classes += ' selected';
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
                this.selectedDay = null;
                this.selectedUsers = [];

                axios.get('/axios/calendar.get', {
                    params: {
                        month: this.month,
                    }
                })
                    .then(res => this.sortUsers(res.data))
                    .catch(error => console.log('Error: ' + this.error));

                this.selectedMonth = this.month + ' / ' + this.year;
                this.daysEmpty = moment(this.year+'.'+this.month+'.1', 'YYYY.MM.DD').weekday();
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
            },
            selectDay(day) {
                if (! this.sortableUsers[day]) {
                    return;
                }

                this.selectedUsers = this.sortableUsers[day];
                this.selectedDay = day;
            },
            formatBirthday(birthday) {
                return moment(birthday).subtract(1, 'years').fromNow(true);
            },
            formatSelectedDay(day) {
                return moment(this.year+'.'+this.month+'.'+this.selectedDay, 'YYYY.MM.DD').fromNow();
            },
        },
    }
</script>
