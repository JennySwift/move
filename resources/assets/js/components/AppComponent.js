module.exports = {
    ready: function () {
        store.getExercises();
        store.getExerciseUnits();
        store.getExercisePrograms();
        store.getExerciseSeries();
    }
};