import helpers from './Helpers'
var object = require('lodash/object');
require('sugar');
Date.setLocale('en-AU');
import Vue from 'vue'

export default {

    state: {
        me: {gravatar: ''},
        exercises: [],
        exercisesLoaded: false,
        showFilters: false,
        exerciseFilters: {
            showStretches: false,
            name: '',
            description: '',
            priority: 1,
            series: '',
        },
        showNewExerciseEntryFields: false,
        exercise: {
            name: '',
            description: '',
            stepNumber: '',
            priority: '',
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
            //Todo:
            // long: helpers.formatDateToLong('today'),
            // sql: helpers.formatDateToSql('today')
        },
        exerciseUnits: [],
        exerciseUnitsLoaded: false,
        exercisePrograms: [],
        exerciseProgramsLoaded: false,
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
            url: '/api/series',
            storeProperty: 'exerciseSeries',
            loadedProperty: 'exerciseSeriesLoaded'
        });
    },

    /**
     *
     */
    getExerciseUnits: function () {
        helpers.get({
            url: '/api/units',
            storeProperty: 'exerciseUnits',
            loadedProperty: 'exerciseUnitsLoaded'
        });
    },

    /**
    *
    */
    insertExerciseSet: function (exercise, useExerciseDefaults, quantity, unit) {
        var data = {
            date: this.state.date.sql,
            exercise_id: exercise.id,
            useExerciseDefaults: useExerciseDefaults
        };

        if (!useExerciseDefaults) {
            data.quantity = quantity;

            if (!unit) {
                data.unit_id = exercise.defaultUnit.data.id;
            }
            else {
                data.unit_id = unit.id;
            }
        }

        helpers.post({
            url: '/api/exerciseEntries',
            data: data,
            message: 'Set added',
            callback: function () {
                store.getExerciseEntriesForTheDay();
                store.getExercise(exercise);
            }
        });
    },

    /**
     * For keeping the exercises table up to date after an entry has been added,
     * for example, the lastDone and dueIn properties
     */
    getExercise: function (exercise) {
        helpers.get({
            url: '/api/exercises/' + exercise.id,
            storeProperty: 'exercises',
            updatingArray: true
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
     * Update an item that is in an array that is in the store
     * @param item
     * @param path
     */
    update: function (item, path) {
        var index = helpers.findIndexById(object.get(this.state, path), item.id);

        Vue.set(object.get(this.state, path), index, item);
    },

    /**
     * Set a property that is in the store (can be nested)
     * @param data
     * @param path
     */
    set: function (data, path) {
        object.set(this.state, path, data);
    },

    /**
     * Toggle a property that is in the store (can be nested)
     * @param path
     */
    toggle: function (path) {
        object.set(this.state, path, !object.get(this.state, path));
    },

    /**
     * Delete an item from an array in the store
     * To delete a nested property of store.state, for example a class in store.state.classes.data:
     * store.delete(itemToDelete, 'student.classes.data');
     * @param itemToDelete
     * @param path
     */
    delete: function (itemToDelete, path) {
        // console.log('\n\n get: ' + JSON.stringify(object.get(this.state, path), null, 4) + '\n\n');
        // console.log('\n\n item to delete: ' + JSON.stringify(itemToDelete, null, 4) + '\n\n');
        object.set(this.state, path, helpers.deleteById(object.get(this.state, path), itemToDelete.id));
    }
}