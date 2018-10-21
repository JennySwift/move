<template>
    <div>
        <f7-page :page-content="false" id="edit-workout-page">
            <navbar :title="shared.workout.name" popover-id="edit-workout">
                <!--<f7-list-item v-on:click="showAddExercisePicker()" link="false" title="Add Exercise" popover-close></f7-list-item>-->
            </navbar>

            <f7-page-content>

                <f7-list inset>
                    <f7-list-item>
                        <f7-label>Edit Workout Name</f7-label>
                        <f7-input type="text" :value="shared.workout.name" @input="shared.workout.name = $event.target.value" placeholder="Name"></f7-input>
                    </f7-list-item>
                </f7-list>

                <history class="history-popup"></history>

                <!--<data-table v-for="(tableData, index) in clonedAndSortedExercises" v-if="!isEmpty(clonedAndSortedExercises)" v-bind:key="index">-->
                    <!--<card-header :title="tableData[0].name">-->
                        <!--<actions :tableData="tableData" page="workout" :addSet="addSet"></actions>-->
                    <!--</card-header>-->

                    <!--<template slot="table-content">-->
                        <!--<table-head :deletingRows="deletingRows" :tableData="tableData"></table-head>-->
                        <!--<tbody>-->
                        <!--<tr v-for="(row, index2) in tableData">-->
                            <!--<level-cell page="workout" :row="row" :index="index2" :tableData="tableData"></level-cell>-->
                            <!--<quantity-cell page="workout" :row="row" :index="index2" :tableData="tableData"></quantity-cell>-->
                            <!--<trash-cell :removeSet="removeSet" :row="row" :deletingRows="deletingRows"></trash-cell>-->
                        <!--</tr>-->
                        <!--</tbody>-->
                    <!--</template>-->
                <!--</data-table>-->

                <exercise-selector id="add-exercise-to-workout-selector" :on-select="showUnitPicker"></exercise-selector>

                <f7-list sortable contacts-list @sortable:sort="onSort" id="exercises-in-workout">
                    <f7-list-item v-for="(tableData, index) in clonedAndSortedExercises" v-if="!isEmpty(clonedAndSortedExercises)" v-bind:key="index" class="no-chevron sortable-item sets-for-exercise">
                        <f7-list-group>
                            <card-header :title="tableData[0].name">
                            <actions :tableData="tableData" page="workout" :addSet="addSet"></actions>
                            </card-header>
                            <f7-list-item
                                swipeout
                                v-for="(row, index2) in tableData"
                                :key="row.id"
                            >
                                <div slot="title">
                                    <level-cell page="workout" :row="row" :index="index2" :tableData="tableData"></level-cell>
                                </div>

                                <div slot="after">
                                    <quantity-cell page="workout" :row="row" :index="index2" :tableData="tableData"></quantity-cell>
                                </div>

                                <f7-swipeout-actions left>
                                    <f7-swipeout-button close color="yellow" v-on:click="addSet(row)">Duplicate</f7-swipeout-button>
                                </f7-swipeout-actions>

                                <f7-swipeout-actions right>
                                    <f7-swipeout-button close color="red" v-on:click="deleteSet(row)" overswipe>Delete</f7-swipeout-button>
                                </f7-swipeout-actions>

                            </f7-list-item>
                        </f7-list-group>
                    </f7-list-item>
                </f7-list>




            </f7-page-content>

            <f7-toolbar class="flex-container">
                <f7-button v-on:click="insertWorkoutGroup"><i class="fas fa-plus"></i></f7-button>
                <f7-button v-on:click="toggleSortable"><i class="fas fa-pencil-alt"></i></f7-button>
                <f7-button v-on:click="updateWorkout()">Save</f7-button>
            </f7-toolbar>

        </f7-page>
    </div>


</template>

<script>
    import Vue from 'vue'
    import TrashCell from './shared/TrashCellComponent'
    import LevelCell from './shared/LevelCellComponent'
    import QuantityCell from './shared/QuantityCellComponent'
    import TableHead from './shared/TableHeadComponent'
    import ExerciseSelector from './shared/ExerciseSelector.vue'
    import swal from 'sweetalert2'
    var object = require('lodash/object');
    export default {
        data: function () {
            return {
                shared: store.state,
                baseUrl: 'api/workouts',
                deletingRows: false,
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
        components: {
            'trash-cell': TrashCell,
            'level-cell': LevelCell,
            'quantity-cell': QuantityCell,
            'table-head': TableHead,
            'exercise-selector': ExerciseSelector
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
                return store.sortExercises(this.clonedExercises);
            }
        },
        methods: {
            onSort(e) {
                var data = {
                    //+ 1 is because of index 0, and I'm using 1 for the first position
                    old_position: e.detail.from + 1,
                    new_position: e.detail.to + 1,
                    workout_id: this.shared.workout.id
                };

                helpers.put({
                    url: '/api/workoutGroups/reorder',
                    data: data,
                    message: 'Reordered'
                });
            },
            toggleSortable: function () {
                app.$f7.sortable.toggle('.sortable-item');
            },
            isEmpty: function (obj) {
                return _.isEmpty(obj);
            },
            showAddExercisePicker: function () {
                store.openPopup('#add-exercise-to-workout-selector');
//                store.showAddExercisePicker(this);
            },
            showUnitPicker: function (selectedExercise) {
                store.set(selectedExercise, 'selectorValues.exercise');
                store.showUnitPicker(this);
            },
            showPrompt: function (row, field) {
                var prompt = app.$f7.dialog.prompt('Enter a ' + field, function (value) {
                    row[field] = value;
                });
            },
            // openActions: function (exerciseGroup) {
            //     var that = this;
            //     var ac1 = app.$f7.actions.create({
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
                this.clonedExercises = helpers.clone(this.shared.workout.exercises.data);

            },
            // removeSet: function (row) {
            //     this.clonedExercises = helpers.deleteFromArray(row, this.clonedExercises);
            // },
            /**
            *
            */
            deleteSet: function (row) {
                helpers.delete({
                    url: 'api/workouts/' + this.shared.workout.id + '/exercises/' + row.exercise_id + '?include=exercises&id=' + row.id,
                    // array: 'workout.exercises.data',
                    // itemToDelete: row,
                    message: 'Set deleted',
                    noConfirm: true,
                    callback: function () {
                        this.clonedExercises = helpers.deleteFromArray(row, this.clonedExercises);
                    }.bind(this)
                });
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
            insertWorkoutGroup: function () {
                var data = {
                    workout_id: this.shared.workout.id
                };

                helpers.post({
                    url: '/api/workoutGroups/',
                    data: data,
                    property: 'newWorkoutGroup',
                    message: 'New group created',
                    callback: function (response) {
                        // this.newWorkoutGroup = response;
                        this.showAddExercisePicker();
                    }.bind(this)
                });
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
        },
        mounted: function () {
            this.getWorkout();
        }
    }
</script>

<style lang="scss" type="text/scss">
    @import '../../sass/shared/index';
    @include exerciseRow;
    #edit-workout-page {
        #exercises-in-workout {
            .card-header {
                padding: 0;
            }
            ul {
                background: inherit;
            }
            .sets-for-exercise {
                background: white;
                margin-bottom: 20px;
                > .item-content {
                    > .item-inner {
                        padding-top: 0;
                        padding-bottom: 0;
                        padding: 0;
                    }
                }
                .list-group {
                    width: 100%;
                    ul {
                        padding-left: 0;
                    }
                    > .item-content {
                        padding-left: 0;
                        > .item-inner {
                            padding-right: 0;
                        }
                    }

                }
            }
        }
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