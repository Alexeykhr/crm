<template>
    <div class="calendar">
        <div class="left-column">
            <div class="header">
                <md-button @click="prev()" class="md-raised">
                    <md-icon>keyboard_arrow_left</md-icon>
                </md-button>

                <span>{{ selected }}</span>

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

        <md-whiteframe md-elevation="1" class="phone-viewport right-column">
            <div class="header">
                <md-icon>cake</md-icon>
                <span>Дні народження</span>
            </div>

            <div class="body">
                <md-list class="md-double-line">
                    <md-list-item v-for="(user, index) in selectedUsers" :key="index" class="item">
                        <md-avatar>
                            <img :src="user.photo ? user.photo : 'img/user.png'" :alt="user.name">
                        </md-avatar>

                        <div class="md-list-text-container">
                            <span :title="user.name">{{ user.name }}</span>
                            <span>{{ formatBirthday(user.birth) }}</span>
                        </div>

                        <md-button class="md-icon-button" :href="'/users/' + user.id">
                            <md-icon>remove_red_eye</md-icon>
                        </md-button>
                    </md-list-item>
                </md-list>
            </div>
        </md-whiteframe>
    </div>
</template>

<script>
    export default {
        props: {
            me: {
                type: Object,
                required: true,
            },
            inUsers: {
                type: Array,
                required: true,
            },
            inMonth: {
                type: Number,
                required: true,
            },
            inYear: {
                type: Number,
                required: true,
            },
        },

        data() {
            return {
                users: this.inUsers,
                month: this.inMonth,
                year: this.inYear,

                sortableUsers: [],
                daysEmpty: 0,
                daysInMonth: 0,

                selected: '00 / 0000',
                selectedDay: null,
                selectedUsers: [],
            }
        },

        created() {
            this.sortUsers(this.users);

            this.daysInMonth = moment().daysInMonth();
            this.daysEmpty = moment().date(1).weekday();

            this.selected = this.month + ' / ' + this.year;

            this.selectDay(moment().date());
        },

        methods: {
            eventClass(day) {
                let classes = 'item';

                if (this.sortableUsers[day]) {
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
                    .then(res => this.sortUsers(res.data));

                this.selected = this.month + ' / ' + this.year;
                this.daysEmpty = moment(this.year+'.'+this.month+'.1', 'YYYY.MM.DD').weekday();
            },
            sortUsers(users) {
                let count = users.length,
                    arr = [];

                for (let i = 0; i < count; i++) {
                    let date = moment(users[i].birth).date();

                    if (! arr[date]) {
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
                return moment(birthday).fromNow(true);
            },
            formatSelectedDay(day) {
                return moment(this.year+'.'+this.month+'.'+this.selectedDay, 'YYYY.MM.DD').fromNow();
            },
        },
    }
</script>
