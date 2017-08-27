<template>
    <md-layout class="list">
        <md-layout class="left-column" md-flex="75">
            <md-table @sort="onSort">
                <md-table-header>
                    <md-table-row>
                        <md-table-head md-sort-by="title">Назва</md-table-head>
                        <md-table-head md-sort-by="users_count">Працівників</md-table-head>
                        <md-table-head></md-table-head>
                    </md-table-row>
                </md-table-header>

                <md-table-body>
                    <md-table-row v-for="(job, index) in jobs.data" :key="job.id" :class="setClass(index)"
                                  :style="!job.delete && job.active ? 'border-left: 10px solid #333;' : ''">
                        <md-table-cell>
                            <span><b>{{ job.title }}</b></span>
                        </md-table-cell>

                        <md-table-cell>
                            <span>{{ job.users_count }}</span>
                        </md-table-cell>

                        <md-table-cell>
                            <md-button :href="'/jobs/'+job.id" class="md-icon-button">
                                <md-icon>remove_red_eye</md-icon>
                            </md-button>
                        </md-table-cell>
                    </md-table-row>
                </md-table-body>
            </md-table>

            <pagination :data="jobs" :func="getJobs"></pagination>
        </md-layout>

        <md-layout class="right-column" md-flex="25">
            <md-input-container md-clearable>
                <md-icon>search</md-icon>
                <label>Пошук</label>
                <md-input v-model="q" autofocus></md-input>
            </md-input-container>

            <br>

            <md-input-container>
                <label for="count">Кількість посад</label>
                <md-select name="count" id="count" v-model="count">
                    <md-option :value="10">10</md-option>
                    <md-option :value="25">25</md-option>
                    <md-option :value="50">50</md-option>
                    <md-option :value="75">75</md-option>
                    <md-option :value="100">100</md-option>
                </md-select>
            </md-input-container>

            <div class="choose">
                <span>Активний</span>
                <md-radio v-model="active" name="active" md-value="1">Так</md-radio>
                <md-radio v-model="active" name="active" md-value="0">-</md-radio>
                <md-radio v-model="active" name="active" md-value="-1">Ні</md-radio>
            </div>

            <div class="choose">
                <span>Видалений</span>
                <md-radio v-model="del" name="delete" md-value="1">Так</md-radio>
                <md-radio v-model="del" name="delete" md-value="0">-</md-radio>
                <md-radio v-model="del" name="delete" md-value="-1">Ні</md-radio>
            </div>
        </md-layout>
    </md-layout>
</template>

<script>
    export default {
        props: [
            'iUser', 'inJobs',
        ],

        data() {
            return {
                me: [],
                jobs: [],

                q: '',
                count: 25,
                active: 0,
                del: -1,

                sortColumn: '',
                sortType: '',
            }
        },

        created() {
            this.me = JSON.parse(this.iUser);
            this.jobs = JSON.parse(this.inJobs);
        },

        methods: {
            getJobs(page = 1) {
                axios.get('/axios/jobs.get', {
                    params: {
                        q: this.q,
                        count: this.count,
                        del: this.del,
                        active: this.active,
                        page: page,

                        sortColumn: this.sortColumn,
                        sortType: this.sortType,
                    }
                })
                    .then(res => this.jobs = res.data)
                    .catch(error => console.log(this.error));

                $(window).scrollTop($('.right-column')[0].scrollHeight + 48);
            },
            setClass(id) {
                let classes = 'list-row';

                classes += this.jobs.data[id].delete ? ' delete' : ' no-delete';
                classes += this.jobs.data[id].active ? ' active' : ' no-active';

                return classes;
            },
            onSort(action) {
                this.sortColumn = action.name;
                this.sortType = action.type;
                this.getJobs(this.jobs.current_page);
            },
        },

        watch: {
            q() {
                let len = this.q.length;

                if (len > 2 || len == 0) {
                    this.getJobs();
                }
            },
            count() {
                this.getJobs();
            },
            del() {
                this.getJobs();
            },
            active() {
                this.getJobs();
            },
        },
    }
</script>
