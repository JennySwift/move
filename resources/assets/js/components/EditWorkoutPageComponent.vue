<template>
    <div>
        <f7-page :page-content="false">
            <navbar :title="shared.workout.name" popover-id="edit-workout">
                <f7-list-item v-on:click="addExerciseToWorkout()" link="false" title="Add Exercise" popover-close></f7-list-item>
            </navbar>

            <f7-page-content>
                <f7-list inset>
                    <f7-list-item>
                        <f7-input type="text" :value="shared.workout.name" @input="shared.workout.name = $event.target.value" placeholder="Name"></f7-input>
                    </f7-list-item>
                </f7-list>

                <div class="data-table data-table-init card" v-if="!isEmpty(clonedAndSortedExercises)" v-for="exercise in clonedAndSortedExercises">
                    <!-- Card Header -->
                    <div class="card-header">
                        <!-- Table title -->
                        <div class="data-table-title">{{exercise[0].name}}</div>
                        <!-- Table actions -->
                        <div class="data-table-actions">
                            <f7-button :actions-open="'#' + exercise[0].exercise_id + '-workout-actions'">Actions</f7-button>
                            <actions :exercise="exercise" id="workout" :addSet="addSet"></actions>
                        </div>
                    </div>
                    <!-- Card Content -->
                    <div class="card-content">
                        <table>
                            <thead>
                            <tr>
                                <th class="numeric-cell">LEVEL</th>
                                <th class="numeric-cell">{{exercise[0].unit.data.name}}</th>
                                <th class="actions-cell"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="row in exercise">
                                <td class="numeric-cell">
                                    <span :data-sheet="'#workout-exercise-level-keypad-' + row.id" class="sheet-open">{{row.level}}</span>
                                    <keypad :value.sync="row.level" :id="'workout-exercise-level-keypad-' + row.id"></keypad>
                                </td>
                                <td class="numeric-cell">
                                    <span :data-sheet="'#workout-exercise-quantity-keypad-' + row.id" class="sheet-open">{{row.quantity}}</span>
                                    <keypad :value.sync="row.quantity" :id="'workout-exercise-quantity-keypad-' + row.id"></keypad>
                                </td>
                                <td class="actions-cell" v-on:click="removeSet(row)">
                                    <f7-icon f7="trash" size="22"></f7-icon>
                                </td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


            </f7-page-content>

            <f7-toolbar>
                <buttons
                    :save="updateWorkout"
                >
                </buttons>

            </f7-toolbar>

        </f7-page>
    </div>


</template>

<script>
    import Vue from 'vue'
    import swal from 'sweetalert2'
    var object = require('lodash/object');
    export default {
        data: function () {
            return {
                shared: store.state,
                baseUrl: 'api/workouts',
                clonedExercises: [
//                    {
//                        id: '',
//                        exercise_id: '',
//                        level: '',
//                        quantity: 0,
//                        unit: {
//                            data: {}
//                        }
//                    }

                ],
            }
        },
        computed: {
            redirectTo: function () {
                return '/workouts/' + this.shared.workout.id;
            },
            /**
             * Format is like this:
             "L-Sit": [
                 {
                     "id": 1,
                     "exercise_id": 1
                     "name": "L-Sit",
                     "level": 52,
                     "quantity": 60,
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
            isEmpty: function (obj) {
                return _.isEmpty(obj);
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
                this.clonedExercises = helpers.clone(this.shared.workout.exercises.data);

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
                            unit_id: value.unit.data.id
                        }
                    );
                });

                return data;
            },

            /**
            *
            */
            updateWorkout: function () {
                var data = {
                    name: this.shared.workout.name,
                    exercises: this.formatExerciseDataForSyncing()

                };

                helpers.put({
                    url: this.baseUrl + '/' + this.shared.workout.id + '?include=exercises',
                    data: data,
                    property: 'workouts',
                    message: 'Workout updated',
                    redirectTo: this.redirectTo,
                    callback: function (response) {

                    }.bind(this)
                });
            },

            /**
             *
             */
            getWorkout: function () {
                // console.log(this.$f7);
                // console.log(this.$route);
                // console.log(this.$f7router);
                // console.log(this.$f7.views.main.router);
                console.log(this.$f7route.params.id);

                var id = helpers.getIdFromRouteParams(this);

                helpers.get({
                    url: this.baseUrl + '/' + id + '?include=exercises',
                    storeProperty: 'workout',
                    callback: function (response) {
                        this.setClonedExercises();
                    }.bind(this)
                });
            },

            addExerciseToWorkout: function () {
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
                            quantity: 50,
                            unit: {
                                data: helpers.findById(that.shared.exerciseUnits, result.value[1])
                            }
                        };

                        that.addSet(row);
                    }
                    })
            },
        },
        mounted: function () {
            this.getWorkout();
        }
    }
</script>

<style lang="scss" type="text/scss">
    #edit-workout-page {
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
    }
</style>