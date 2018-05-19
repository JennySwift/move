<template>
    <f7-page id="session-page">
        <navbar :title="shared.session.name" popover-id="edit-session">
            <!--<f7-list-item v-on:click="showAddExercisePicker()" link="false" title="Add Exercise" popover-close></f7-list-item>-->
        </navbar>

        <f7-page-content>

            <history class="history-popup"></history>

            <div class="data-table data-table-init card" v-for="(exercise, index1) in clonedAndSortedExercises">
                <!-- Card Header -->
                <div class="card-header">
                    <!-- Table title -->
                    <div class="data-table-title">{{exercise[0].name}}</div>
                    <!-- Table actions -->
                    <div class="data-table-actions">
                        <f7-button :actions-open="'#' + exercise[0].exercise_id + '-session-actions'">Actions</f7-button>
                        <actions :exercise="exercise" id="session" :addSet="addSet"></actions>
                    </div>
                </div>
                <!-- Card Content -->
                <div class="card-content">
                    <table>
                        <thead>
                        <tr>
                            <th class="numeric-cell"><div>LEVEL</div></th>
                            <th class="numeric-cell"><div>{{exercise[0].unit.data.name}}</div></th>
                            <th class="checkbox-cell" v-show="!deletingRows"></th>
                            <th class="actions-cell" v-show="deletingRows"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(row, index2) in exercise">
                            <td class="numeric-cell sheet-open" :data-sheet="'#session-exercise-level-keypad-' + row.id">
                                <span>{{row.level}}</span>
                                <keypad :value.sync="row.level" :id="'session-exercise-level-keypad-' + row.id"></keypad>
                            </td>
                            <td class="numeric-cell sheet-open" :data-sheet="'#session-exercise-quantity-keypad-' + row.id">
                                <span v-if="exercise[0].unit.data.name !== 'TIME'">{{row.quantity}}</span>
                                <span v-if="exercise[0].unit.data.name === 'TIME'">{{row.quantity | timeFilter}}</span>
                                <keypad :value.sync="row.quantity" :id="'session-exercise-quantity-keypad-' + row.id"></keypad>
                            </td>
                            <td class="checkbox-cell" v-show="!deletingRows">
                                <f7-checkbox :checked="row.complete > 0" @change="row.complete = $event.target.checked"></f7-checkbox>
                            </td>

                            <trash-cell :removeSet="removeSet" :row="row" :deletingRows="deletingRows"></trash-cell>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <f7-input id="session-page-picker-input" style="display:none"></f7-input>

        </f7-page-content>

        <f7-toolbar class="flex-container">
            <f7-button v-on:click="showAddExercisePicker()"><i class="fas fa-plus"></i></f7-button>
            <f7-button v-on:click="deletingRows = !deletingRows"><i class="fas fa-pencil-alt"></i></f7-button>
            <f7-button v-on:click="updateSession()">Save</f7-button>
        </f7-toolbar>

    </f7-page>

</template>

<script>
    import Vue from 'vue'
    import TrashCell from './shared/TrashCellComponent'
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
            'trash-cell': TrashCell
        },
        filters: {
            timeFilter: function (time) {
                return helpers.filterTime(time);
            }
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
                var sorted = _.groupBy(this.clonedExercises, 'name');

                return sorted;
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
            addSet: function (row) {
                var newSet = {
                    exercise_id: row.exercise_id,
                    level: row.level,
                    name: row.name,
                    quantity: row.quantity,
                    complete: 0,
                    unit: row.unit
                };

                Vue.set(this.clonedExercises, this.clonedExercises.length, newSet);
            },
            formatExerciseDataForSyncing: function () {
                var data = [];

                _.forEach(this.clonedExercises, function (value, index) {
                    data.push(
                        {
                            exercise_id: value.exercise_id,
                            level: value.level,
                            quantity: value.quantity,
                            complete: value.complete,
                            unit_id: value.unit.data.id
                        }
                    );
                });

                return data;
            },

            /**
             *
             */
            updateSession: function () {
                var data = {
                    name: this.shared.session.name,
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

</style>