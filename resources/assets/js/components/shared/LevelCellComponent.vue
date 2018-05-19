<template>
    <td class="numeric-cell sheet-open" :data-sheet="'#' + page + '-exercise-level-keypad-' + row.id">
        <span>{{row.level}}</span>
        <keypad :value.sync="row.level" :id="page + '-exercise-level-keypad-' + row.id"></keypad>
    </td>
</template>

<script>
    export default {
        props: [
            'page',
            'row'
        ]
    }
</script>

<style lang="scss" type="text/scss">

</style>