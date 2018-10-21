<template>
    <!--Unit is not TIME-->
    <div v-if="tableData[0].unit.data.name !== 'TIME'" class="numeric-cell sheet-open" :data-sheet="'#' + id">
        <!--<span>{{row.quantity}}</span>-->
        <f7-badge v-if="!row.complete" color="red">{{row.quantity}}</f7-badge>
        <f7-badge v-if="row.complete" color="green">{{row.quantity}}</f7-badge>
        <keypad :value.sync="row.quantity" :id="id"></keypad>
    </div>
    <!--Unit is TIME-->
    <div v-else class="numeric-cell" v-on:click="showTimePicker()">
        <!--<span>{{ row.quantity | timeFilter}}</span>-->
        <f7-badge v-if="!row.complete" color="red">{{row.quantity | timeFilter}}</f7-badge>
        <f7-badge v-if="row.complete" color="green">{{row.quantity | timeFilter}}</f7-badge>
    </div>
</template>

<script>
    export default {
        filters: {
            timeFilter: function (time) {
                return helpers.filterTime(time);
            }
        },
        computed: {
            id: function () {
                return this.page + 'page-exercise-' + this.tableData[0].exercise_id + 'quantity-keypad-index' + this.index;
            }
        },
        methods: {
            showTimePicker: function () {
                var that = this;
                var values = [];
                for (var i = 0; i<=60; i++) {
                    values.push(i);
                }

                var secondsValues = [];
                for (var i = 0; i<=60; i+=5) {
                    secondsValues.push(i);
                }

                var picker = app.$f7.picker.create({
                    inputEl: '#exercise-picker',
                    cols: [
                        {
                            textAlign: 'left',
                            values: values,
                            displayValues: _.map(values, function (value) {return value + 'h'}),
                        },
                        {
                            values: values,
                            displayValues: _.map(values, function (value) {return value + 'm'}),
                        },
                        {
                            values: secondsValues,
                            displayValues: _.map(secondsValues, function (value) {return value + 's'}),
                        },
                    ],
                    on: {
                        close: function (picker) {
                            that.row.quantity = helpers.convertDurationToSeconds(
                                {
                                    hours: picker.value[0],
                                    minutes: picker.value[1],
                                    seconds: picker.value[2],
                                }
                            );
                        }
                    }
                });
                picker.open();
            },
        },
        props: [
            'row',
            'tableData',
            'page',
            'index'
        ]
    }
</script>

<style lang="scss" type="text/scss">

</style>