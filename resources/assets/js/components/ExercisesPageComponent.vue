
<template>
    <div id="exercises-page">
        <filters></filters>

        <div v-show="!shared.showFilters">

            <date-navigation></date-navigation>

            <series-history-popup
                :exercise-series-history="exerciseSeriesHistory"
                :selected-series="selectedSeries"
            >
            </series-history-popup>

            <series-popup
                :selected-series="selectedSeries"
                :exercise-series.sync="exerciseSeries"
            >
            </series-popup>

            <exercise-entries></exercise-entries>

            <div id="exercises">
                <h3>Exercises</h3>

                <div
                    v-for="exercise in shared.exercises | filterExercises"
                    v-bind:class="{'stretch': exercise.stretch}"
                    class="pointer exercise card"
                >

                    <div
                        v-bind:style="{background: exercise.series.data.color}"
                        v-bind:class="{'has-color': exercise.series.data.color}"
                        v-on:click="setExercise(exercise)"
                        v-link="{path: '/exercises/' + exercise.id}"
                        class="card-header"
                    >
                        {{exercise.name}}
                    </div>
                    <div class="card-block">
                        <!--<h4 class="card-title exercise-name">{{ exercise.name }}</h4>-->
                        <!--<p class="card-text text-left"></p>-->
                        <div class="add">
                            <button
                                v-on:click="insertDefaultExerciseSet(exercise)"
                                v-bind:style="{background: exercise.series.data.color}"
                                v-bind:class="{'has-color': exercise.series.data.color}"
                                class="btn btn-default btn-sm"
                            >
                                <!--<i class="fa fa-plus"></i>-->
                                <span>
                                    Add
                                    {{ exercise.defaultQuantity }}
                                    {{ exercise.defaultUnit.data.name }}
                                </span>

                            </button>

                            <button
                                v-on:click="insertExerciseSet(exercise)"
                                v-bind:style="{background: exercise.series.data.color}"
                                v-bind:class="{'has-color': exercise.series.data.color}"
                                class="btn btn-default btn-sm"
                            >
                                <span>
                                    Add...
                                </span>

                            </button>
                        </div>
                    </div>


                    <!--<div v-on:click="setExercise(exercise)" v-link="{path: '/exercises/' + exercise.id}" class="big-screens">{{ exercise.priority }}</div>-->
                    <!--<div v-on:click="setExercise(exercise)" v-link="{path: '/exercises/' + exercise.id}">{{ exercise.lastDone }}</div>-->
                    <!--<div v-on:click="setExercise(exercise)" v-link="{path: '/exercises/' + exercise.id}" class="big-screens">{{ exercise.frequency }}</div>-->
                    <!--<div v-on:click="setExercise(exercise)" v-link="{path: '/exercises/' + exercise.id}">{{ exercise.dueIn }}</div>-->

                </div>
            </div>



        </div>
    </div>
    </template>

<script>

    var ExercisesRepository = require('../repositories/ExercisesRepository');
    var $ = require('jquery');

    module.exports = {
        template: '#exercises-page-template',
        data: function () {
            return {
                date: store.state.date,
                exerciseSeriesHistory: [],
                showNewSeriesFields: false,
                showNewExerciseFields: false,
                selectedSeries: {
                    exercises: {
                        data: []
                    }
                },
                showExerciseEntryInputs: false,
                shared: store.state
            };
        },
        computed: {
            selectedExercise: function () {
                return this.shared.selectedExercise;
            },
            units: function () {
                return this.shared.exerciseUnits;
            },
            programs: function () {
                return this.shared.programs;
            },
            exerciseSeries: function () {
                return this.shared.exerciseSeries;
            },
            filters: function () {
                return this.shared.exerciseFilters;
            }
        },
        components: {
            'filters': require('./ExerciseFiltersComponent.vue')
        },
        filters: {
            filterExercises: function (exercises) {
                var that = this;

                //Sort
                exercises = _.chain(exercises)
                    .sortBy(function (exercise) {return exercise.stepNumber})
                    .sortBy(function (exercise) {
                        return exercise.series.data.id;
                    })
                    .sortBy('priority')
                    .sortBy(function (exercise) {
                        return exercise.lastDone * -1;
                    })
                    .partition(function (exercise) {
                        return exercise.lastDone === null;
                    })
                    .flatten()
                    .sortBy('dueIn')
                    .value();

                //Filter
                return exercises.filter(function (exercise) {
                    var filteredIn = true;

                    //Priority filter
                    if (that.filters.priority && exercise.priority != that.filters.priority) {
                        filteredIn = false;
                    }

                    //Name filter
                    if (that.filters.name && exercise.name.indexOf(that.filters.name) === -1) {
                        filteredIn = false;
                    }

                    //Description filter
                    if (exercise.description && exercise.description.indexOf(that.filters.description) === -1) {
                        filteredIn = false;
                    }

                    else if (!exercise.description && that.filters.description !== '') {
                        filteredIn = false;
                    }

                    //Stretches files
                    if (!that.filters.showStretches && exercise.stretch) {
                        filteredIn = false;
                    }

                    //Series filter
                    if (that.filters.series && exercise.series.data.name != that.filters.series.name && that.filters.series.value !== 'all') {
                        filteredIn = false;
                    }

                    return filteredIn;
                });
            },

            filterSeries: function (series) {
                var that = this;

                //Sort
                series = _.chain(series)
                    .sortBy('priority')
                    .sortBy('lastDone')
                    .value();

                /**
                 * @VP:
                 * This method feels like a lot of code for just
                 * a simple thing-ordering series by their lastDone value,
                 * putting those with a null lastDone value on the end.
                 * I tried underscore.js _.partition with _.flatten,
                 * but it put 0 values on the end,
                 * (I had trouble getting the predicate parameter of the _.partition method to work.)
                 */
                series = this.moveLastDoneNullToEnd(series);

                //Filter
                return series.filter(function (thisSeries) {
                    var filteredIn = true;

                    //Priority filter
                    if (that.priorityFilter && thisSeries.priority != that.priorityFilter) {
                        filteredIn = false;
                    }

                    return filteredIn;
                });
            },
        },
        methods: {

            /**
             *
             */
            setExercise: function (exercise) {
                store.set(exercise, 'exercise');
            },

            /**
             *
             */
            insertDefaultExerciseSet: function (exercise) {
                store.insertExerciseSet(exercise, true);
            },

            /**
             *
             */
            insertExerciseSet: function (exercise) {
                var quantity = prompt("How many " + exercise.defaultUnit.data.name + "?");
                if (quantity) {
                    store.insertExerciseSet(exercise, false, quantity);
                }
            },

            /**
             * For the series filter
             * @param series
             * @returns {*}
             */
            moveLastDoneNullToEnd: function (series) {
                //Get the series that have lastDone null values
                var seriesWithNullLastDone = _.filter(series, function (oneSeries) {
                    return oneSeries.lastDone == null;
                });

                //Remove the series that have lastDone null values
                for (var i = 0; i < seriesWithNullLastDone.length; i++) {
                    var index = _.indexOf(series, _.findWhere(series, {id: seriesWithNullLastDone[i].id}));
                    series = _.without(series, series[index]);
                }

                //Add the series that have lastDone null values back on the
                //end of the series array
                for (var i = 0; i < seriesWithNullLastDone.length; i++) {
                    series.push(seriesWithNullLastDone[i]);
                }

                return series;
            },

            /**
            *
            */
            getExerciseSeriesHistory: function (key) {
                //Find the series. The exercises were grouped according to series, so all we have is the series name (key).
                var series = _.find(this.exerciseSeries, function (series) {
                    return series.name === key;
                });

                helpers.get({
                    url: 'api/seriesEntries/' + series.id,
//                    storeProperty: 'exercises',
//                    loadedProperty: 'exercisesLoaded',
                    callback: function (response) {
                        //For displaying the name of the series in the popup
                        this.selectedSeries = series;
                        this.exerciseSeriesHistory = response.data;
                        $.event.trigger('show-series-history-popup');
                    }
                }.bind(this));
            },

            /**
            *
            */
            getExercisesInSeries: function (series) {
                helpers.get({
                    url: '/api/exerciseSeries/' + series.id,
                    storeProperty: 'series',
                    loadedProperty: 'seriesLoaded',
                    callback: function (response) {
                        this.selectedSeries = response;
                    }
                }.bind(this));
            },

            /**
             *
             */
            showExerciseSeriesPopup: function (key) {
                //Find the series. The exercises were grouped according to series, so all we have is the series name (key).
                var series = _.find(this.exerciseSeries, function (series) {
                    return series.name === key;
                });

                helpers.get({
                    url: '/api/exerciseSeries/' + series.id,
                    storeProperty: 'series',
                    loadedProperty: 'seriesLoaded',
                    callback: function (response) {
                        this.selectedSeries = response;
                        $.event.trigger('show-series-popup');
                    }
                }.bind(this));
            }
        },
        props: [
            //data to be received from parent
        ],
        ready: function () {

        }
    };
</script>

