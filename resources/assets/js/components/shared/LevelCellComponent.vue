<template>
    <td class="numeric-cell sheet-open" :data-sheet="'#' + id">
        <span>{{row.level}}</span>
        <keypad :value.sync="row.level" :id="id"></keypad>
    </td>
</template>

<script>
    export default {
        computed: {
            id: function () {
              return this.page + 'page-exercise-' + this.exerciseRows[0].exercise_id + 'level-keypad-index' + this.index;
            }
        },
        props: [
            'page',
            'row',
            'index',
            'exerciseRows'
        ]
    }
</script>

<style lang="scss" type="text/scss">

</style>