<template>
    <div id="exercise-entries-page">
        <!--<entries-for-specific-exercise-and-date-and-unit></entries-for-specific-exercise-and-date-and-unit>-->
        <div class="date">{{shared.date.long}}</div>
        <date-navigation v-bind:show-date=false></date-navigation>

        <div v-if="exerciseEntries.length > 0">
            <!--<h3>Entries:</h3>-->


            <div
                v-for="entry in exerciseEntries"
                class="pointer exercise card"
            >

                <div
                    v-bind:style="{background: entry.exercise.data.series.data.color}"
                    v-bind:class="{'has-color': entry.exercise.data.series.data.color}"
                    class="card-header"
                >
                    <router-link to="/exercises/' + entry.exercise.data.id + '/units/' + entry.unit.data.id + '/' + this.shared.date.sql"></router-link>
                    {{ entry.exercise.data.name }}
                    {{ entry.sets }}
                    <span v-if="entry.sets !== 1">sets,</span>
                    <span v-else>set,</span>
                    {{ entry.total }} {{ entry.unit.data.name }}
                </div>

                <div class="card-block">
                    <!--<h4 class="card-title exercise-name">{{ exercise.name }}</h4>-->
                    <!--<p class="card-text text-left"></p>-->
                    <div class="add">
                        <button
                            v-if="entry.exercise.data.defaultUnit && entry.unit.data.id === entry.exercise.data.defaultUnit.data.id"
                            v-on:click="insertDefaultExerciseSet(entry.exercise)"
                            v-bind:style="{background: entry.exercise.data.series.data.color}"
                            v-bind:class="{'has-color': entry.exercise.data.series.data.color}"
                            class="btn btn-default btn-sm"
                        >
                            <!--<i class="fa fa-plus"></i>-->
                            <span>
                                    Add
                                    {{ entry.exercise.data.defaultQuantity | removeUnnecessaryZeros }}
                                    {{ entry.exercise.data.defaultUnit.data.name }}
                                </span>

                        </button>

                        <button
                            v-on:click="insertExerciseSet(entry)"
                            v-bind:style="{background: entry.exercise.data.series.data.color}"
                            v-bind:class="{'has-color': entry.exercise.data.series.data.color}"
                            class="btn btn-default btn-sm"
                        >
                                <span>
                                    Add {{entry.unit.data.name}}
                                </span>

                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <h3>No entries here</h3>
        </div>
        <router-link to="/exercises"><span>Exercises</span> <i class="fa fa-arrow-circle-left"></i></router-link>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                showExerciseEntryInputs: false,
                selectedExercise: {
                    unit: {}
                },
                shared: store.state
            };
        },
        computed: {
            date: function () {
                return this.shared.date;
            },
            exerciseEntries: function () {
                return this.shared.exerciseEntries;
            }
        },
        components: {
//            'entries-for-specific-exercise-and-date-and-unit': require('./EntriesForSpecificExerciseAndDateAndUnitPopupComponent.vue')
        },
        filters: {
            removeUnnecessaryZeros: function (number) {
                return filters.removeUnnecessaryZeros(number);
            },
        },
        methods: {

            /**
             *
             * @param entry
             */
            showEntriesForSpecificExerciseAndDateAndUnitPopup: function (entry) {
                $.event.trigger('show-entries-for-specific-exercise-and-date-and-unit-popup', [entry]);
            },

            /**
             *
             * @param exercise
             */
            insertDefaultExerciseSet: function (exercise) {
                store.insertExerciseSet(exercise.data, true);
            },

            /**
             *
             */
            insertExerciseSet: function (entry) {
                var quantity = prompt("How many " + entry.unit.data.name + "?");
                if (quantity) {
                    store.insertExerciseSet(entry.exercise.data, false, quantity, entry.unit.data);
                }
            },

            /**
             *
             */
            listen: function () {
                $(document).on('date-changed', function (event) {
                    store.getExerciseEntriesForTheDay();
                });
            }
        },
        mounted: function () {
            this.listen();
            store.getExerciseEntriesForTheDay();
        }
    }
</script>

<style lang="scss" type="text/scss">

</style>