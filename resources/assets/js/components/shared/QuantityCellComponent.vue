<template>
    <td class="numeric-cell sheet-open" :data-sheet="'#' + page + '-exercise-quantity-keypad-' + row.id">
        <span v-if="exercise[0].unit.data.name !== 'TIME'">{{row.quantity}}</span>
        <span v-if="exercise[0].unit.data.name === 'TIME'">{{row.quantity | timeFilter}}</span>
        <keypad :value.sync="row.quantity" :id="page + '-exercise-quantity-keypad-' + row.id"></keypad>
    </td>
</template>

<script>
    export default {
        filters: {
            timeFilter: function (time) {
                return helpers.filterTime(time);
            }
        },
        props: [
            'row',
            'exercise',
            'page'
        ]
    }
</script>

<style lang="scss" type="text/scss">

</style>