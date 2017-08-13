<template>
    <div class="pagination">
        <md-button @click="first()" class="md-raised md-dense hide-xs">
            <md-icon>first_page</md-icon>
        </md-button>
        <md-button @click="prev()" class="md-raised md-dense">
            <md-icon>keyboard_arrow_left</md-icon>
        </md-button>

        <md-button>{{ data.current_page + ' / ' + data.last_page }}</md-button>

        <md-button @click="next()" class="md-raised md-dense">
            <md-icon>keyboard_arrow_right</md-icon>
        </md-button>
        <md-button @click="last()" class="md-raised md-dense hide-xs">
            <md-icon>last_page</md-icon>
        </md-button>
    </div>
</template>

<script>
    export default {
        props: [
            'data', 'func',
        ],

        methods: {
            first () {
                if (this.data.current_page > 1) {
                    this.func(1);
                }
            },
            prev() {
                if (this.data.prev_page_url) {
                    this.func(this.data.current_page - 1);
                }
            },
            next () {
                if (this.data.next_page_url) {
                    this.func(this.data.current_page + 1);
                }
            },
            last () {
                if (this.data.current_page < this.data.last_page) {
                    this.func(this.data.last_page);
                }
            },
        }
    }
</script>
