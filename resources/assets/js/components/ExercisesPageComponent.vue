
<template>
    <div id="exercises-page">
        <filters></filters>

        <div v-show="!shared.showFilters">

            <router-link to="/add-exercise" tag="button" id="add-exercise-btn" class="btn btn-default">Add Exercise</router-link>

            <div id="exercises">
                <div
                    v-for="exercise in shared.exercises"
                    class="pointer exercise card"
                >

                    <div
                        v-bind:style="{background: exercise.series.data.color}"
                        v-bind:class="{'has-color': exercise.series.data.color}"
                        v-on:click="setExercise(exercise)"
                        class="card-header"
                    >
                        <router-link :to="'/exercises/' + exercise.id">{{exercise.name}}</router-link>
                    </div>
                    <div class="card-block">
                        <div class="add">
                            <button
                                v-on:click="insertDefaultExerciseSet(exercise)"
                                v-bind:style="{background: exercise.series.data.color}"
                                v-bind:class="{'has-color': exercise.series.data.color}"
                                class="btn btn-default btn-sm"
                            >
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

                </div>
            </div>



        </div>
    </div>
    </template>

<script>

    import ExerciseFiltersComponent from './ExerciseFiltersComponent.vue'
    import ExerciseRepository from '../repositories/ExercisesRepository'

    export default {
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
                shared: store.state,
                baseUrl: 'api/series'
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
            'filters': ExerciseFiltersComponent
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
                    url: this.baseUrl + '/' + series.id,
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
                    url: this.baseUrl + '/' + series.id,
                    storeProperty: 'series',
                    loadedProperty: 'seriesLoaded',
                    callback: function (response) {
                        this.selectedSeries = response;
                        $.event.trigger('show-series-popup');
                    }
                }.bind(this));
            }
        }
    }
</script>

<style lang="scss" type="text/scss">
    #exercises-page {
        td {
            text-align: left;
        }
        @media (max-width: 320px) {
            .big-screens {
                display: none;
            }
        }
        table {
            width: auto;
            margin: auto;
        }
        .btn-container {
            margin-bottom: 5px;
            display: flex;
            justify-content: flex-end;
        }
        #add-exercise-btn {
            width: 100%;
        }
    }
</style>

