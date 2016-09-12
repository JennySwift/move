var Vue = require('vue');
var helpers = require('./Helpers');
require('sugar');

module.exports = {

    state: {
        me: {gravatar: ''},
        exercises: [],
        exerciseSeries: [],
        exerciseEntries: [],
        date: {
            typed: Date.create('today').format('{dd}/{MM}/{yyyy}'),
            long: helpers.formatDateToLong('today'),
            sql: helpers.formatDateToSql('today')
        },
        exerciseUnits: [],
        programs: [],
        loading: false
    },

    /**
     *
     */
    showLoading: function () {
        this.state.loading = true;
    },

    /**
     *
     */
    hideLoading: function () {
        this.state.loading = false;
    },

    /**
     *
     * @param date
     */
    setDate: function (date) {
        this.state.date.typed = Date.create(date).format('{dd}/{MM}/{yyyy}');
        this.state.date.long = helpers.formatDateToLong(date);
        this.state.date.sql = helpers.formatDateToSql(date);
    },

    /**
     *
     */
    getExercises: function () {
        helpers.get('/api/exercises', false, 'exercises');
    },

    /**
     * Todo: all the entries I think are actually in the data (unnecessarily)
     */
    getExerciseEntriesForTheDay: function () {
        helpers.get('/api/exerciseEntries/' + this.state.date.sql, false, 'exerciseEntries');
    },

    /**
     *
     */
    getSeries: function () {
        helpers.get('/api/exerciseSeries', false, 'exerciseSeries');
    },

    /**
     *
     * @param data
     * @param propertyName
     */
    set: function (data, propertyName) {
        store.state[propertyName] = data;
    },

    /**
     *
     */
    getExerciseUnits: function () {
        helpers.get('/api/exerciseUnits', false, 'exerciseUnits');
    },

    /**
     *
     */
    getExercisePrograms: function () {
        helpers.get('/api/exercisePrograms', function (response) {
            store.state.programs = response.data;
        });
    },

    /**
     *
     */
    insertExerciseSet: function (exercise) {
        var data = {
            date: this.state.date.sql,
            exercise_id: exercise.id,
            exerciseSet: true
        };

        helpers.post('/api/exerciseEntries', data, 'Set added', function (response) {
            store.state.exerciseEntries = response.data;
            exercise.lastDone = 0;
        }.bind(this));
    },

    /**
     *
     * @param item
     * @param propertyName
     */
    add: function (item, propertyName) {
        store.state[propertyName].push(item);
    },

    /**
     *
     * @param item
     * @param propertyName
     */
    update: function (item, propertyName) {
        var index = helpers.findIndexById(this.state[propertyName], item.id);
        this.state[propertyName].$set(index, item);
    },

    /**
     *
     * @param item
     * @param propertyName
     */
    delete: function (item, propertyName) {
        this.state[propertyName] = helpers.deleteById(this.state[propertyName], item.id);
    }
};