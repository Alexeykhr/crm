<template>
    <div class="pagination" v-once>
        <md-button :href="data.prev_page_url ? data.path+'?page=1'+attr : ''" class="md-icon-button md-raised md-dense">
            <md-icon>first_page</md-icon>
        </md-button>
        <md-button :href="data.prev_page_url ? data.prev_page_url+attr : ''" class="md-icon-button md-raised md-dense">
            <md-icon>keyboard_arrow_left</md-icon>
        </md-button>

        <md-button class="md-dense">{{ data.current_page + ' / ' + data.last_page }}</md-button>

        <md-button :href="data.next_page_url ? data.next_page_url+attr : ''" class="md-icon-button md-raised md-dense">
            <md-icon>keyboard_arrow_right</md-icon>
        </md-button>
        <md-button :href="data.next_page_url ? data.path+'?page='+data.last_page+attr : ''" class="md-icon-button md-raised md-dense">
            <md-icon>last_page</md-icon>
        </md-button>
    </div>
</template>

<script>
    export default {
        props: [
            'data', 'attr',
        ],
    }
</script>
