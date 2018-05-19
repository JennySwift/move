<template>
    <tr>
        <th class="numeric-cell"><div>LEVEL</div></th>
        <th class="numeric-cell"><div>{{tableData[0].unit.data.name}}</div></th>
        <th v-if="page === 'session'" class="checkbox-cell"></th>
        <th class="actions-cell" v-show="deletingRows"></th>
    </tr>
</template>

<script>
    export default {
        props: [
            'deletingRows',
            'tableData',
            'page'
        ]
    }
</script>

<style lang="scss" type="text/scss">

</style>