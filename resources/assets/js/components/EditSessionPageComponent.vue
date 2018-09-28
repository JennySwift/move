<template>
    <f7-page id="session-page">
        <navbar :title="shared.session.name" popover-id="edit-session">
            <!--<f7-list-item v-on:click="showAddExercisePicker()" link="false" title="Add Exercise" popover-close></f7-list-item>-->
        </navbar>

        <f7-page-content>

            <f7-list inset>
                <f7-list-item>
                    <f7-label>Edit Session Name</f7-label>
                    <f7-input type="text" :value="shared.session.name" @input="shared.session.name = $event.target.value" placeholder="Name"></f7-input>
                </f7-list-item>
                <f7-list-item>
                    <f7-label>Edit Session Date</f7-label>
                    <f7-input type="text" :value="shared.session.created_at" @input="shared.session.created_at = $event.target.value" placeholder="Date"></f7-input>
                </f7-list-item>
            </f7-list>

            <history class="history-popup"></history>

            <!--<data-table v-for="(tableData, index) in clonedAndSortedExercises" v-bind:key="index">-->
                <!--<card-header :title="tableData[0].name">-->
                    <!--<actions :tableData="tableData" page="session" :addSet="addSet"></actions>-->
                <!--</card-header>-->
                <!--<template slot="table-content">-->
                    <!--<table-head :deletingRows="deletingRows" :tableData="tableData" page="session"></table-head>-->
                    <!--<tbody>-->
                    <!--<tr v-for="(row, index2) in tableData">-->
                        <!--<level-cell page="session" :row="row" :index="index2" :tableData="tableData"></level-cell>-->
                        <!--<quantity-cell page="session" :row="row" :index="index2" :tableData="tableData"></quantity-cell>-->
                        <!--<checkbox-cell :row="row"></checkbox-cell>-->
                        <!--<trash-cell :removeSet="removeSet" :row="row" :deletingRows="deletingRows"></trash-cell>-->
                    <!--</tr>-->
                    <!--</tbody>-->
                <!--</template>-->
            <!--</data-table>-->

            <f7-list v-for="(tableData, index) in clonedAndSortedExercises" v-bind:key="index" class="no-chevron">
                <f7-list-group>
                    <card-header :title="tableData[0].name">
                        <actions :tableData="tableData" page="session" :addSet="addSet"></actions>
                    </card-header>
                    <f7-list-item
                        swipeout
                        color="green"
                        v-for="(row, index2) in tableData"
                        :key="row.id"
                    >
                        <div slot="title">
                            <level-cell page="session" :row="row" :index="index2" :tableData="tableData"></level-cell>
                        </div>

                        <div slot="after">
                            <quantity-cell page="session" :row="row" :index="index2" :tableData="tableData"></quantity-cell>
                        </div>

                        <f7-swipeout-actions left>
                            <f7-swipeout-button close v-if="!row.complete" color="green" v-on:click="toggleComplete(row)" overswipe>Complete</f7-swipeout-button>
                            <f7-swipeout-button close v-if="row.complete" color="grey" v-on:click="toggleComplete(row)" overswipe>Incomplete</f7-swipeout-button>
                        </f7-swipeout-actions>

                        <f7-swipeout-actions right>
                            <f7-swipeout-button close color="red" v-on:click="removeSet(row)" overswipe>Delete</f7-swipeout-button>
                        </f7-swipeout-actions>

                    </f7-list-item>
                </f7-list-group>
            </f7-list>


            <f7-input id="session-page-picker-input" style="display:none"></f7-input>

        </f7-page-content>

        <f7-toolbar class="flex-container">
            <f7-button v-on:click="showAddExercisePicker()"><i class="fas fa-plus"></i></f7-button>
            <!--<f7-button v-on:click="deletingRows = !deletingRows"><i class="fas fa-pencil-alt"></i></f7-button>-->
            <f7-button v-on:click="updateSession()">Save</f7-button>
        </f7-toolbar>

    </f7-page>

</template>

<script>
    import Vue from 'vue'
    import TrashCell from './shared/TrashCellComponent'
    import LevelCell from './shared/LevelCellComponent'
    import QuantityCell from './shared/QuantityCellComponent'
    import CheckboxCell from './shared/CheckBoxCellComponent'
    import TableHead from './shared/TableHeadComponent'

    import swal from 'sweetalert2'
    var object = require('lodash/object');
    export default {
        data: function () {
            return {
                shared: store.state,
                baseUrl: 'api/sessions',
                deletingRows: false,
                clonedExercises: [
//                    {
//                        id: '',
//                        exercise_id: '',
//                        level: '',
//                        quantity: 0,
//                        complete: 0,
//                        unit: {
//                            data: {}
//                        }
//                    }

                ],
                showTrashIcons: false
            }
        },
        components: {
            'trash-cell': TrashCell,
            'level-cell': LevelCell,
            'quantity-cell': QuantityCell,
            'checkbox-cell': CheckboxCell,
            'table-head': TableHead
        },
        computed: {
            /**
             * Format is like this:
             "L-Sit": [
             {
                 "id": 1,
                 "exercise_id": 1
                 "name": "L-Sit",
                 "level": 52,
                 "quantity": 60,
                 "complete": 0,
                 "unit": {
                     "data": { "id": 1, "name": "REPS" }
                 }
             }
             ]
             */
            clonedAndSortedExercises: function () {
                return _.groupBy(this.clonedExercises, 'name');
                // return store.sortExercises(this.clonedExercises);
            }
        },
        methods: {
            showPrompt: function (row, field) {
                var prompt = app.f7.dialog.prompt('Enter a ' + field, function (value) {
                   row[field] = value;
                });
            },
            showPicker: function (row) {
                var values = [];
                for (var i = 0; i<= 1000; i++) {
                    values.push(i);
                }
                var picker = app.f7.picker.create({
                    inputEl: '#session-page-picker-input',
                    cols: [
                        {
                            values: values
                        }
                    ],
                    on: {
                        change: function (picker, values, displayValues) {
                            row.quantity = values[0];
                        }
                    }
                });
                picker.setValue([row.quantity]);
                picker.open();

            },
            getUnitOptions: function () {
                var options = {};
                _.forEach(this.shared.exerciseUnits, function (value, index) {
                    options[value.id] = value.name;
                });
                return options;
            },
            getExerciseOptions: function () {
                var options = {};
                _.forEach(this.shared.exercises, function (value, index) {
                    options[value.id] = value.name;
                });
                return options;
            },
            setClonedExercises: function () {
                this.clonedExercises = helpers.clone(this.shared.session.exercises.data);

            },
            removeSet: function (row) {
                this.clonedExercises = helpers.deleteFromArray(row, this.clonedExercises);
            },
            toggleComplete: function (row) {
                row.complete = !row.complete;
            },
            addSet: function (row) {
                var newSet = store.formatDataForAddingSet(row);

                Vue.set(this.clonedExercises, this.clonedExercises.length, newSet);
            },
            formatExerciseDataForSyncing: function () {
                return store.formatExerciseDataForSyncing(this.clonedExercises);
            },

            /**
             *
             */
            updateSession: function () {
                var data = {
                    name: this.shared.session.name,
                    created_at: this.shared.session.created_at,
                    exercises: this.formatExerciseDataForSyncing()
                };

                helpers.put({
                    url: this.baseUrl + '/' + this.shared.session.id + '?include=exercises',
                    data: data,
                    property: 'sessions',
                    message: 'Session updated',
                    callback: function (response) {

                    }.bind(this)
                });
            },

            /**
             *
             */
            getSession: function () {
                var id = helpers.getIdFromRouteParams(this);

                helpers.get({
                    url: this.baseUrl + '/' + id + '?include=exercises',
                    storeProperty: 'session',
                    callback: function (response) {
                        this.setClonedExercises();
                    }.bind(this)
                });
            },

            showAddExercisePicker: function () {
                store.showAddExercisePicker(this);
            },

        },
        mounted: function () {
            this.getSession();
        }
    }
</script>

<style lang="scss" type="text/scss">
    @import '../../sass/shared/index';
    @include exerciseRow;
</style>