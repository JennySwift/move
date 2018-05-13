<template>
    <f7-page>
        <navbar :title="shared.session.name" popover-id="edit-session">
            <f7-list-item v-on:click="addExerciseToSession()" link="false" title="Add Exercise" popover-close></f7-list-item>
        </navbar>

        <f7-page-content>

            <div class="data-table data-table-init card" v-for="(exercise, index1) in clonedAndSortedExercises">
                <!-- Card Header -->
                <div class="card-header">
                    <!-- Table title -->
                    <div class="data-table-title">{{exercise[0].name}}</div>
                    <!-- Table actions -->
                    <div class="data-table-actions">
                        <f7-button :actions-open="'#' + exercise[0].exercise_id + '-session-actions'">Actions</f7-button>
                        <f7-actions :id="exercise[0].exercise_id + '-session-actions'">
                            <f7-actions-group>
                                <f7-actions-button v-on:click="addSet(exercise[0])">Add Set</f7-actions-button>
                                <f7-actions-button>
                                    <f7-link :href="'/exercises/' + exercise[0].exercise_id">View Exercise</f7-link>
                                </f7-actions-button>
                            </f7-actions-group>
                        </f7-actions>
                    </div>
                </div>
                <!-- Card Content -->
                <div class="card-content">
                    <table>
                        <thead>
                        <tr>
                            <th class="numeric-cell"><div>LEVEL</div></th>
                            <th class="numeric-cell"><div>{{exercise[0].unit.data.name}}</div></th>
                            <th class="checkbox-cell"></th>
                            <th class="actions-cell"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(row, index2) in exercise">
                            <td class="numeric-cell">
                                {{row.level}}
                                <!--<f7-input inputStyle="width: 30px" type="number" :value="row.level" @input="row.level = $event.target.value"></f7-input>-->
                            </td>
                            <td class="numeric-cell" v-on:click="showPicker(row)">
                                {{row.quantity}}
                                <!--<f7-input type="tel" inputStyle="width: 30px" :value="row.quantity" @input="row.quantity = $event.target.value"></f7-input>-->
                            </td>
                            <td class="checkbox-cell">
                                <f7-checkbox :checked="row.complete > 0" @change="row.complete = $event.target.checked"></f7-checkbox>
                            </td>

                            <td class="actions-cell" v-on:click="removeSet(row)">
                                <f7-icon f7="trash" size="22"></f7-icon>
                            </td>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <f7-input id="session-page-picker-input" style="display:none"></f7-input>

        </f7-page-content>

        <f7-toolbar>
            <buttons
                :save="updateSession"
            >
            </buttons>

        </f7-toolbar>

    </f7-page>

</template>

<script>
    import Vue from 'vue'
    import swal from 'sweetalert2'
    var object = require('lodash/object');
    export default {
        data: function () {
            return {
                shared: store.state,
                baseUrl: 'api/sessions',
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
            // openActions: function (exerciseGroup) {
            //     var that = this;
            //     var ac1 = app.f7.actions.create({
            //         buttons: [
            //             {
            //                 text: 'Add Set',
            //                 onClick: function () {
            //                     that.addSet(exerciseGroup[0])
            //                 }
            //             },
            //             {
            //                 text: 'View History'
            //             },
            //             {
            //                 text: 'View Exercise',
            //                 onClick: function () {
            //                     helpers.goToRoute('/exercises/' + exerciseGroup[0].exercise_id);
            //                 }
            //             },
            //             {
            //                 text: 'Cancel',
            //                 color: 'red'
            //             },
            //         ]
            //     }).open();
            // },
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
//            toggleCompletion: function (row) {
//                if (!row.complete)
//                row.complete = 1;
//                this.clonedExercises = helpers.updateItemInArray(row, this.clonedExercises);
//            },
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

            addExerciseToSession: function () {
                var that = this;
                swal.mixin({
                    showCloseButton: true,
                    animation: false,
//                    customClass: 'animated zoomIn',
                    progressSteps: ['1', '2'],
//                    grow: 'fullscreen'
                }).queue([
                    {
                        title: "Choose an Exercise",
                        input: 'select',
                        inputOptions: this.getExerciseOptions(),

                    },
                    {
                        title: "Choose a Unit",
                        input: 'select',
                        inputOptions: this.getUnitOptions(),
                    },
                ]).then(function (result) {
                    if (result.value) {
                        var exercise = helpers.findById(that.shared.exercises, result.value[0]);
                        var row = {
                            exercise_id: exercise.id,
                            name: exercise.name,
                            level: 1,
                            quantity: '',
                            complete: 0,
                            unit: {
                                data: helpers.findById(that.shared.exerciseUnits, result.value[1])
                            }
                        };

                        that.addSet(row);
                    }
                })
            },

//            optionChosen: function (option, inputId) {
//                var that = this;
//                if (inputId === 'add-exercise-to-session-input') {
//                    option.exercise_id = option.id;
//                    option.level = 1;
//                    option.quantity = '';
////                    option.unit = {
////                        data: this.shared.exerciseUnits[0]
////                    };
//                    swal({
//                        title: "Choose a Unit",
//                        input: 'select',
//                        inputOptions: this.getUnitOptions(),
//                        showCloseButton: true
//                    }).then(function (result) {
//                        result = parseInt(result);
//                        option.unit = {
//                            data: helpers.findById(that.shared.exerciseUnits, result)
//                        };
//                        that.addSet(option);
//                    });
//                }
//            },
        },
        created: function () {
//            this.$bus.$on('autocomplete-option-chosen', this.optionChosen);
        },
        mounted: function () {
            this.getSession();
        }
    }
</script>

<style lang="scss" type="text/scss">
    #edit-session-page {
        th {
            text-align:center;
        }
        td {
            padding: 0 8px;
            input {
                width: 85%;
                text-align: center;
            }
        }
        .add-set-td {
            padding: 4px;
        }
        .btn-container {
            :first-child {
                margin-bottom: 4px;
            }
        }
        th, td {
            &:last-child {
                width: 32px;
            }
        }
    }
</style>