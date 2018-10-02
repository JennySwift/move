<template>
    <div>
        <f7-button :actions-open="'#' + id">Actions</f7-button>

        <f7-actions :id="id">
            <f7-actions-group>
                <f7-actions-button v-on:click="addSet(tableData[0])">Add Set</f7-actions-button>
                <f7-actions-button v-on:click="setExercise()">
                    <f7-link :href="'/exercises/' + tableData[0].exercise_id">View Exercise</f7-link>
                </f7-actions-button>
                <!--<f7-actions-button>-->
                <!--<f7-link :href="'/exercises/' + exercise[0].exercise_id + '/history'">View History</f7-link>-->
                <!--</f7-actions-button>-->

                <f7-actions-button v-on:click="openPopup()">
                    History
                </f7-actions-button>

                <f7-actions-button v-if="page === 'session'" v-on:click="updateSetsForOneExerciseInWorkout()">
                    Update Workout
                </f7-actions-button>
            </f7-actions-group>
        </f7-actions>
    </div>
</template>

<script>
    export default {
        data: function () {
          return {
              shared: store.state
          }
        },
        computed: {
            id: function () {
              return this.tableData[0].exercise_id + '-' + this.page + 'page-actions';
            }
        },
        props: [
            'tableData',
            'addSet',
            'page',
        ],
        methods: {
            openPopup: function () {
                this.setExercise();
                store.openHistoryPopup();
            },

            /**
             *
             */
            setExercise: function () {
                var exercise = helpers.findById(this.shared.exercises, this.tableData[0].exercise_id);
                store.set(exercise, 'exercise');
            },

            /**
             * For updating sets for just one exercise in a workout to match what I did in the session
             */
            updateSetsForOneExerciseInWorkout: function () {
                var exerciseId = this.tableData[0].exercise_id;

                if (!this.tableData[0].workoutGroup) {
                    //The exercise has been added to the session but not to the workout yet
                    //First create a new workout group
                    this.insertWorkoutGroup();
                }
                else {
                    var data = {
                        exercise_id: exerciseId,
                        unit_id: this.tableData[0].unit.data.id,
                        exercises: store.formatExerciseDataForSyncing(this.tableData)
                    };

                    var workoutId = this.shared.workout.id ? this.shared.workout.id : this.shared.session.workout_id;

                    helpers.put({
                        url: 'api/workouts/' + workoutId + '/exercises/' + exerciseId + '?include=exercises',
                        data: data,
                        property: 'workouts',
                        message: 'Workout updated',
                        callback: function (response) {

                        }.bind(this)
                    });
                }
            },

            /**
             *
             */
            insertWorkoutGroup: function () {
                var exerciseId = this.tableData[0].exercise_id;
                var workoutId = this.shared.workout.id ? this.shared.workout.id : this.shared.session.workout_id;

                var data = {
                    workout_id: workoutId
                };

                helpers.post({
                    url: '/api/workoutGroups/',
                    data: data,
                    property: 'newWorkoutGroup',
                    message: 'New group created',
                    callback: function (response) {
                        //Now that the workout group has been created, add the workout group id
                        //to the exercises in the group before adding them to the workout
                        _.forEach(this.tableData, function (value, index) {
                            value.workoutGroup = {
                                data: response
                            }
                        });

                        var data = {
                            exercise_id: exerciseId,
                            unit_id: this.tableData[0].unit.data.id,
                            exercises: store.formatExerciseDataForSyncing(this.tableData)
                        };

                        helpers.put({
                            url: 'api/workouts/' + workoutId + '/exercises/' + exerciseId + '?include=exercises',
                            data: data,
                            property: 'workouts',
                            message: 'Workout updated',
                            callback: function (response) {

                            }.bind(this)
                        });
                    }.bind(this)
                });
            },


        }
    }
</script>

<style lang="scss" type="text/scss">

</style>