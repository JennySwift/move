<template>
    <div id="edit-workout-page">
        <router-link :to="'/workouts/' + shared.workout.id" tag="i" class="far fa-times-circle fa-2x"></router-link>

        <input
            class="center invisible-input"
            v-model="shared.workout.name"
        >
        </input>

        <!--{{clonedExercises}}-->

        <div v-for="exercise in clonedAndSortedExercises">
            {{exercise[0].name}}
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>LEVEL</th>
                    <th>{{exercise[0].unit.data.name}}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="row in exercise">
                    <td>
                        <input v-model="row.level" type="text"/>
                    </td>
                    <td>
                        <input v-model="row.quantity" type="text"/>
                        <i
                            class="fas fa-trash-alt"
                            v-on:click="removeSet(row)"
                        >
                        </i>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" v-on:click="addSet(exercise[0])">Add Set</td>
                </tr>
                </tbody>
            </table>
        </div>

        <autocomplete
            input-id="add-exercise-to-workout-input"
            prop="name"
            :unfiltered-options="shared.exercises"
            input-placeholder="Add an exercise..."
        >
        </autocomplete>

        <!--<autocomplete-->
            <!--input-id="choose-unit-for-exercise-input"-->
            <!--prop="name"-->
            <!--:unfiltered-options="shared.exerciseUnits"-->
            <!--input-placeholder="Choose a unit..."-->
        <!--&gt;-->
        <!--</autocomplete>-->



        <buttons
            :save="updateWorkout"
        >
        </buttons>

    </div>
</template>

<script>
    import Vue from 'vue'
    var object = require('lodash/object');
    export default {
        data: function () {
            return {
                shared: store.state,
                baseUrl: 'api/workouts',
                clonedExercises: [
                    {
                        id: '',
                        exercise_id: '',
                        level: '',
                        quantity: 0,
                        unit: {
                            data: {}
                        }
                    }

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
                console.log(sorted);
               return sorted;
            }
        },
        methods: {
            getUnitOptions: function () {
                var options = {};
                _.forEach(this.shared.exerciseUnits, function (value, index) {
                    options[value.id] = value.name;
                });
                return options;
            },
            setClonedExercises: function () {
                this.clonedExercises = helpers.clone(this.shared.workout.exercises.data);

            },
            removeSet: function (row) {
                console.log(row);
                this.clonedExercises = helpers.deleteFromArray(row, this.clonedExercises);
                console.log(this.clonedExercises);
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
                var id = this.$route.params.id;

                helpers.get({
                    url: this.baseUrl + '/' + id + '?include=exercises',
                    storeProperty: 'workout',
                    callback: function (response) {
                        this.setClonedExercises();
                    }.bind(this)
                });
            },

            optionChosen: function (option, inputId) {
                var that = this;
                if (inputId === 'add-exercise-to-workout-input') {
                    option.exercise_id = option.id;
                    option.level = 1;
                    option.quantity = '';
//                    option.unit = {
//                        data: this.shared.exerciseUnits[0]
//                    };
                    swal({
                        title: "Choose a Unit",
                        input: 'radio',
                        inputOptions: this.getUnitOptions(),
//                        text: options.confirmText,
//                        showCancelButton: true,
//                        confirmButtonText: 'Yes',
//                        cancelButtonText: 'Cancel',
//                        confirmButtonClass: 'btn btn-danger',
//                        cancelButtonClass: 'btn btn-default',
//                        buttonsStyling: false,
//                        reverseButtons: true,
                        showCloseButton: true
                    }).then(function (result) {
                        result = parseInt(result);
                        option.unit = {
                            data: helpers.findById(that.shared.exerciseUnits, result)
                        };
                        that.addSet(option);
                    });

//                    var unit = prompt("Enter 1 for reps or 2 for time");
//                    if (unit === '1') {
//                        option.unit = {
//                            data: this.shared.exerciseUnits[0]
//                        }
//                    }
//                    else {
//                        option.unit = {
//                            data: this.shared.exerciseUnits[1]
//                        }
//                    }
                }
//                else if (inputId === 'choose-unit-for-exercise-input') {
//
//                }
            },
        },
        created: function () {
            this.$bus.$on('autocomplete-option-chosen', this.optionChosen);
        },
        mounted: function () {
            this.getWorkout();
//            if (!this.shared.workout.exercises) {
//                this.getWorkout();
//            }
        }
    }
</script>

<style lang="scss" type="text/scss">
    #edit-workout-page {
        th {
            text-align:center;
        }
        input {
            width: 85%;
        }
    }
</style>