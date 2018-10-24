export default {

    updateSetsForOneExerciseInWorkout: function (tableData) {
        if (!this.hasWorkoutGroup(tableData[0])) {
            this.insertWorkoutGroup(tableData);
        }
        else {
            this.proceedWithUpdate(tableData);
        }
    },

    /**
     * For checking if a set in a session has a workout group
     * @param set
     */
    hasWorkoutGroup: function (set) {
        //It might have a workoutGroup property (from the JavaScript),
        // but not actually have a workout group
        if (set.workoutGroup) {
            return set.workoutGroup.data.id;
        }
        return false;
    },

    /**
     * A new exercise group has been added to the session, but not yet saved to a workout.
     * Create a new group for the workout.
     */
    insertWorkoutGroup: function (tableData) {
        var data = {
            workout_id: this.getWorkoutId()
        };

        helpers.post({
            url: '/api/workoutGroups/',
            data: data,
            property: 'newWorkoutGroup',
            message: 'New group created',
            callback: function (response) {
                tableData = this.addSetsForOneExerciseToWorkout(tableData, response);
                //Update the session, so that the workout group ids are added to
                //the new exercise group in the session
                store.updateSession();
            }.bind(this)
        });
    },

    /**
     * Now that the workout group has been created, add the workout group id
     * to the exercises in the group before adding them to the workout
     * @param tableData
     * @param workoutGroup
     */
    addSetsForOneExerciseToWorkout: function (tableData, workoutGroup) {
        _.forEach(tableData, function (value, index) {
            value.workoutGroup = {
                data: workoutGroup
            }
        });

        this.proceedWithUpdate(tableData);

        return tableData;
    },

    proceedWithUpdate: function (tableData) {
        var exerciseId = this.getExerciseId(tableData);

        var data = {
            exercise_id: exerciseId,
            unit_id: tableData[0].unit.data.id,
            exercises: store.formatExerciseDataForSyncing(tableData)
        };

        helpers.put({
            url: 'api/workouts/' + this.getWorkoutId() + '/exercises/' + exerciseId + '?include=exercises',
            data: data,
            property: 'workouts',
            message: 'Workout updated',
            callback: function (response) {

            }.bind(this)
        });
    },

    getExerciseId: function (tableData) {
        return tableData[0].exercise_id;
    },

    getWorkoutId: function () {
        return store.state.workout.id ? store.state.workout.id : store.state.session.workout_id
    },

}
