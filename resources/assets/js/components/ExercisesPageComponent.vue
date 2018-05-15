<template>
    <f7-page :page-content="false">
        <navbar title="Exercises" popover-id="exercises">
            <!--<f7-list-item link="/add-exercise/" title="Add Exercise" popover-close></f7-list-item>-->
        </navbar>
        <f7-fab color="green" position="right-bottom" href="/add-exercise/">
            <f7-icon f7="add"></f7-icon>
        </f7-fab>

        <f7-page-content class="ptr-content">
            <div class="ptr-preloader">
                <div class="preloader"></div>
                <div class="ptr-arrow"></div>
            </div>

            <!--<f7-list>-->
                <!--<f7-list-item link="/add-exercise/" title="Add Exercise"></f7-list-item>-->
            <!--</f7-list>-->

            <f7-list contacts-list>
                <f7-list-group>
                    <f7-list-item
                        v-for="exercise in shared.exercises"
                        :link="'/exercises/' + exercise.id"
                        v-bind:title="exercise.name"
                        v-on:click="setExercise(exercise)"
                        v-bind:key="exercise.id"
                    >

                    </f7-list-item>
                </f7-list-group>
            </f7-list>
        </f7-page-content>


    </f7-page>
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
        // page events
        on: {
            pageInit(event, pageData) {
                // do something on page init
            },
            pageAfterOut(event, pageData) {
                // do something when page transitioned out of view
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
            },
            listen: function () {
                $('.ptr-content').on('ptr:refresh', function (e) {
                    store.getExercises({pullToRefresh: true});
                });
            }
        },
        mounted: function () {
            this.listen();
        }
    }
</script>

<style lang="scss" type="text/scss">
    @import '../../sass/shared/index';
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
            margin-top: 17px;
        }
    }
</style>

