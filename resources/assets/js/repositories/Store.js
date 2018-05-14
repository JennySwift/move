import helpers from './Helpers'
var object = require('lodash/object');
require('sugar');
Date.setLocale('en-AU');
import Vue from 'vue'

export default {

    state: {
        me: {gravatar: ''},
        exercises: [],
        workouts: [],
        sessions: {
            data: [],
            pagination: {}
        },
        routeHistory: [],
        previousRoute: '',
        dateFormat: 'daysAgo',
        history: {
            data: [

            ],
            pagination: {}
        },
        workout: {
            name: '',
            exercises: {
                data: [
                    {
                        id: '',
                        level: '',
                        quantity: 0,
                        unit: {
                            data: {}
                        }
                    }

                ]
            }
        },
        session: {
            id: '',
            name: '',
            created_at: '',
            exercises: {
                data: [
                    {
                        id: '',
                        exercise_id: '',
                        level: '',
                        quantity: 0,
                        complete: 0,
                        unit: {
                            data: {}
                        }
                    }

                ]
            }
        },
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
        exerciseProgramsLoaded: false,
        loading: false
    },

    /**
     * Set the exercise before calling this function
     */
    openHistoryPopup: function () {
        store.getHistory();
        var popup = app.f7.popup.create({
            el: '.history-popup'
        });
        popup.open();
    },

    toggleDateFormat: function () {
        if (this.state.dateFormat === 'daysAgo') {
            this.state.dateFormat = 'date';
        }
        else if (this.state.dateFormat === 'date') {
            this.state.dateFormat = 'daysAgo';
        }
    },

    dateFilter: function (date) {
        if (this.state.dateFormat === 'daysAgo') {
            return helpers.getDaysAgo(date);
        }
        return helpers.formatDateForUser(date);
    },

    goToPreviousRoute: function () {
        // console.log('before', this.state.routeHistory);
        // console.log('before', this.state.previousRoute);
        //So that pressing the back button twice or more doesn't mean going back and forth to the same pages
        this.state.routeHistory.pop();
        // console.log('after', this.state.routeHistory);
        // console.log('after', this.state.previousRoute);
    },

    updateRouteHistory: function (path) {
        // console.log('before', this.state.routeHistory);
        // console.log('before', this.state.previousRoute);
        var newRoute = app.f7.views.main.router.url;
        var previousRoute = this.state.routeHistory[this.state.routeHistory.length-1];

        if (newRoute !== previousRoute) {
            //Back button was not pressed
            this.add(newRoute, 'routeHistory');
        }


        this.set(this.state.routeHistory[this.state.routeHistory.length-2], 'previousRoute');
        // console.log('after', this.state.routeHistory);
        // console.log('after', this.state.previousRoute);
    },

    /**
     *
     */
    showLoading: function () {
        app.f7.preloader.show();
        // this.state.loading = true;
    },

    /**
     *
     */
    hideLoading: function () {
        app.f7.preloader.hide();
        // this.state.loading = false;
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
    getExercises: function (options) {
        var pullToRefresh = false;
        if (options) {
            pullToRefresh = options.pullToRefresh;
        }
        helpers.get({
            url: '/api/exercises',
            storeProperty: 'exercises',
            loadedProperty: 'exercisesLoaded',
            pullToRefresh: pullToRefresh
        });
    },

    /**
     *
     */
    getHistory: function (url) {
        // var id = helpers.getIdFromRouteParams(this);
        var id = this.state.exercise.id;

        if (!url) {
            url = 'api/exercises/' + id + '?include=sessions';
        }
        else {
            //This part isn't in the pagination url
            url += '&include=sessions';
        }

        helpers.get({
            url: url,
            storeProperty: 'history',
            pagination: true
        });
    },

    /**
     *
     */
    getWorkouts: function () {
        helpers.get({
            url: '/api/workouts',
            storeProperty: 'workouts',
            loadedProperty: 'workoutsLoaded'
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
     * url is for pagination
     */
    getSessions: function (url) {
        if (!url) {
            url = '/api/sessions';
        }
        helpers.get({
            url: url,
            storeProperty: 'sessions',
            pagination: true
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