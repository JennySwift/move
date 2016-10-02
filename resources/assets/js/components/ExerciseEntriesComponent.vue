<template>
    <div>
        <entries-for-specific-exercise-and-date-and-unit-popup></entries-for-specific-exercise-and-date-and-unit-popup>

        <div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Exercise</th>
                        <th>Sets</th>
                        <th>Total</th>
                        <th>Add</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="entry in exerciseEntries">
                        <td
                            v-on:click="showEntriesForSpecificExerciseAndDateAndUnitPopup(entry)"
                            class="pointer"
                        >
                            {{ entry.exercise.data.name }}
                        </td>
                        <td
                            v-on:click="showEntriesForSpecificExerciseAndDateAndUnitPopup(entry)"
                            class="pointer"
                        >
                            {{ entry.sets }}
                        </td>
                        <td
                            v-on:click="showEntriesForSpecificExerciseAndDateAndUnitPopup(entry)"
                            class="pointer"
                        >
                            {{ entry.total }} {{ entry.unit.name }}
                        </td>
                        <td>
                            <button
                                v-if="entry.exercise.data.defaultUnit && entry.unit.data.id === entry.exercise.data.defaultUnit.data.id"
                                v-on:click="insertExerciseSet(entry.exercise)"
                                class="btn-xs">
                                <i class="fa fa-plus"></i> {{ entry.exercise.data.defaultQuantity }} {{ entry.exercise.data.defaultUnit.data.name }}
                            </button>
                        </td>
                    </tr>
                </tbody>


            </table>
        </div>
    </div>
</template>

<script>
    var $ = require('jquery');

    module.exports = {
        template: '#exercise-entries-template',
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
        components: {},
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
            insertExerciseSet: function (exercise) {
                store.insertExerciseSet(exercise.data);
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
        props: [

        ],
        ready: function () {
            this.listen();
            store.getExerciseEntriesForTheDay();
        }
    };
</script>