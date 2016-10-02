<template>
    <div>
        <div class="input-group-container">
            <input-group
                label="Exercise:"
                :model.sync="newEntry.exercise"
                :enter="insertEntry"
                id="new-entry-exercise"
                :options="shared.exercises"
                options-prop="name"
            >
            </input-group>

            <input-group
                label="Quantity:"
                :model.sync="newEntry.quantity"
                :enter="insertEntry"
                id="new-entry-quantity"
            >
            </input-group>

            <input-group
                label="Unit:"
                :model.sync="newEntry.unit"
                :enter="insertEntry"
                id="new-entry-unit"
                :options="shared.exerciseUnits"
                options-prop="name"
            >
            </input-group>
        </div>

        <buttons
            :save="insertEntry"
            :redirect-to="redirectTo"
        >
        </buttons>

    </div>
</template>

<script>
    module.exports = {
        template: '#new-exercise-entry-template',
        data: function () {
            return {
                newEntry: {},
                shared: store.state,
                redirectTo: '/exercises'
            };
        },
        components: {},
        methods: {

            /**
            *
            */
            insertEntry: function () {
                var data = {
                    date: this.shared.date.sql,
                    exercise_id: this.newEntry.exercise.id,
                    quantity: this.newEntry.quantity,
                    unit_id: this.newEntry.unit.id
                };

                helpers.post({
                    url: '/api/exerciseEntries',
                    data: data,
                    array: 'exerciseEntries',
                    message: 'Entry created',
                    redirectTo: this.redirectTo,
                    callback: function () {
                        store.getExerciseEntriesForTheDay();
                    }.bind(this)
                });
            }
        },
        events: {
            'option-chosen': function (option) {
                this.newEntry = option;
                this.newEntry.unit = option.defaultUnit.data;
                this.newEntry.quantity = option.defaultQuantity;
            }
        }
    };
</script>

