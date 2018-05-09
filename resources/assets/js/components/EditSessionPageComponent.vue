<template>
    <div id="edit-session-page">
        <div class="top-bar">
            <input
                class="center invisible-input"
                v-model="shared.session.name"
            >
            </input>
            <i v-on:click="showTrashIcons = !showTrashIcons" class="edit fas fa-pencil-alt fa-2x"></i>
            <router-link :to="'/sessions/' + shared.session.id" tag="i" class="close far fa-times-circle fa-2x"></router-link>
        </div>

        <div class="container">

            <div v-for="exercise in clonedAndSortedExercises">
                {{exercise[0].name}}
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>LEVEL</th>
                        <th>{{exercise[0].unit.data.name}}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="row in exercise">
                        <td>
                            <input v-model="row.level" class="invisible-input" type="text"/>
                        </td>
                        <td>
                            <input v-model="row.quantity" class="invisible-input" type="text"/>
                        </td>
                        <td>
                            <i
                                v-if="showTrashIcons"
                                class="fas fa-trash-alt"
                                v-on:click="removeSet(row)"
                            >
                            </i>
                            <i
                                v-if="!showTrashIcons"
                                class="fas fa-check"
                            >
                            </i>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" v-on:click="addSet(exercise[0])" class="add-set-td">Add Set</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!--<autocomplete-->
            <!--input-id="add-exercise-to-session-input"-->
            <!--prop="name"-->
            <!--:unfiltered-options="shared.exercises"-->
            <!--input-placeholder="Add an exercise..."-->
            <!--&gt;-->
            <!--</autocomplete>-->

            <!--<autocomplete-->
            <!--input-id="choose-unit-for-exercise-input"-->
            <!--prop="name"-->
            <!--:unfiltered-options="shared.exerciseUnits"-->
            <!--input-placeholder="Choose a unit..."-->
            <!--&gt;-->
            <!--</autocomplete>-->

            <div class="btn-container">
                <button class="btn btn-default new-btn" v-on:click="addExerciseToSession()">Add Exercise</button>

                <buttons
                    :save="updateSession"
                >
                </buttons>
            </div>

        </div>

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
                baseUrl: 'api/sessions',
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
                showTrashIcons: false
            }
        },
        computed: {
            redirectTo: function () {
                return '/sessions/' + this.shared.session.id;
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
                    redirectTo: this.redirectTo,
                    callback: function (response) {

                    }.bind(this)
                });
            },

            /**
             *
             */
            getSession: function () {
                var id = this.$route.params.id;

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
                        input: 'radio',
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
//                        input: 'radio',
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