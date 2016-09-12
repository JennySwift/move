var Vue = require('vue');
var VueResource = require('vue-resource');
Vue.use(VueResource);
var helpers = require('./Helpers');
var object = require('lodash/object');
require('sugar');

module.exports = {

    state: {
        me: {gravatar: ''},
        exercises: [],
        exercisesLoaded: false,
        exercise: {
            program: {},
            series: {},
            defaultUnit: {
                data: {}
            }
        },
        exerciseLoaded: false,
        exerciseSeries: [],
        exerciseSeriesLoaded: false,
        exerciseEntries: [],
        exerciseEntriesLoaded: false,
        date: {
            typed: Date.create('today').format('{dd}/{MM}/{yyyy}'),
            long: helpers.formatDateToLong('today'),
            sql: helpers.formatDateToSql('today')
        },
        exerciseUnits: [],
        exerciseUnitsLoaded: false,
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
        helpers.get({
            url: '/api/exercises',
            storeProperty: 'exercises',
            loadedProperty: 'exercisesLoaded'
        });
    },

    /**
     * Todo: all the entries I think are actually in the data (unnecessarily)
     */
    getExerciseEntriesForTheDay: function () {
        helpers.get({
            url: '/api/exerciseEntries/' + this.state.date.sql,
            storeProperty: 'exerciseEntries',
            loadedProperty: 'exerciseEntriesLoaded'
        });
    },

    /**
     *
     */
    getExerciseSeries: function () {
        helpers.get({
            url: '/api/exerciseSeries',
            storeProperty: 'exerciseSeries',
            loadedProperty: 'exerciseSeriesLoaded'
        });
    },

    /**
     *
     */
    getExerciseUnits: function () {
        helpers.get({
            url: '/api/exerciseUnits',
            storeProperty: 'exerciseUnits',
            loadedProperty: 'exerciseUnitsLoaded'
        });
    },

    /**
     *
     */
    getExercisePrograms: function () {
        helpers.get({
            url: '/api/exercisePrograms',
            storeProperty: 'exercisePrograms',
            loadedProperty: 'exerciseProgramsLoaded'
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

        helpers.post({
            url: '/api/exerciseEntries',
            data: data,
            array: 'exerciseEntries',
            message: 'Set added'
        });
    },

    /**
     * Add an item to an array
     * @param item
     * @param path
     */
    add: function (item, path) {
        object.get(this.state, path).push(item);
    },

    /**
     * Update an item that is in an array
     * @param item
     * @param path
     */
    update: function (item, path) {
        var index = helpers.findIndexById(object.get(this.state, path), item.id);

        object.get(this.state, path).$set(index, item);
    },

    /**
     * Set a property (can be nested)
     * @param data
     * @param path
     */
    set: function (data, path) {
        object.set(this.state, path, data);
    },

    /**
     * Delete an item from an array
     * To delete a nested property of store.state, for example a class in store.state.classes.data:
     * store.delete(itemToDelete, 'student.classes.data');
     * @param itemToDelete
     * @param path
     */
    delete: function (itemToDelete, path) {
        object.set(this.state, path, helpers.deleteById(object.get(this.state, path), itemToDelete.id));
    }
};