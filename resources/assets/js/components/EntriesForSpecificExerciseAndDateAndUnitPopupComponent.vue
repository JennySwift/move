<template>
    <div v-if="entries[0] && entries[0].exercise">
        <popup
            :show-popup.sync="showPopup"
            id="entries-for-specific-exercise-and-date-and-unit-popup"
            :redirect-to="redirectTo"
        >
            <div slot="content">
                <table class="table table-bordered">
                    <caption class="bg-blue">Entries for {{ entries[0].exercise.data.name }} with {{ entries[0].unit.name }} on {{ date.typed }}</caption>
                    <tr>
                        <th>exercise</th>
                        <th>quantity</th>
                        <th>created at</th>
                        <th>x</th>
                    </tr>

                    <tr
                        v-for="entry in entries"
                    >
                        <td>{{ entry.exercise.data.name }}</td>
                        <td>
                            <input
                                v-model="entry.quantity"
                                v-on:keyup.13="updateExerciseEntry(entry)"
                            >
                        </td>
                        <td>{{ entry.createdAt }}</td>
                        <td><i v-on:click="deleteExerciseEntry(entry)" class="delete-item fa fa-times"></i></td>
                    </tr>
                </table>
            </div>
        </popup>

    </div>
</template>

<script>
    var $ = require('jquery');

    module.exports = {
        template: '#entries-for-specific-exercise-and-date-and-unit-popup-template',
        data: function () {
            return {
                showPopup: false,
                entries: {}
            };
        },
        components: {},
        methods: {

            /**
            * Get all the the user's entries for a particular exercise
             * with a particular unit on a particular date.
            */
            getEntriesForSpecificExerciseAndDateAndUnit: function (entry) {
                var data = {
                    date: this.date.sql,
                    exercise_id: entry.exercise.data.id,
                    exercise_unit_id: entry.unit.id
                };


                helpers.get({
                    url: 'api/exerciseEntries/specificExerciseAndDateAndUnit',
                    storeProperty: 'entries',
                    loadedProperty: 'entriesLoaded',
                    callback: function (response) {
                        this.entries = response;
                    }
                }.bind(this));
            },

            /**
            *
            */
            updateExerciseEntry: function (entry) {
                var data = {
                    quantity: entry.quantity
                };

                helpers.put({
                    url: '/api/exerciseEntries/' + entry.id,
                    data: data,
                    message: 'Entry updated',
                    redirectTo: this.redirectTo
                });
            },

            /**
            *
            */
            deleteExerciseEntry: function (entry) {
                helpers.delete({
                    url: '/api/exerciseEntries/' + entry.id,
                    array: 'exerciseEntrys',
//                    itemToDelete: this.exerciseEntry,
                    message: 'Entry deleted',
                    redirectTo: this.redirectTo,
                    callback: function () {
                        this.entries = _.without(this.entries, entry);
                        //This might be unnecessary to do each time, and it fetches a lot
                        //of data for just deleting one entry.
                        //Perhaps do it when the popup closes instead?
                        store.getExerciseEntriesForTheDay();
                    }.bind(this)
                });
            },

            /**
             *
             */
            listen: function () {
                var that = this;
                $(document).on('show-entries-for-specific-exercise-and-date-and-unit-popup', function (event, entry) {
                    that.getEntriesForSpecificExerciseAndDateAndUnit(entry);
                });
            }
        },
        props: [
            'date'
        ],
        ready: function () {
            this.listen();
        }
    };
</script>
