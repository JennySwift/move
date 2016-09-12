<template>
    <div class="margin-bottom">

        <autocomplete
            :insert-item-function="insertEntry"
            url="/api/exercises"
            autocomplete-field="exercise"
            id-to-focus-after-autocomplete="new-exercise-entry-quantity"
            autocomplete-field-id="new-exercise-entry-exercise"
        >
        </autocomplete>

        <div class="form-group">
            <label for="new-ingredient-quantity">Quantity</label>
            <input
                v-model="newEntry.quantity"
                v-on:keyup.13="insertEntry()"
                type="text"
                id="new-exercise-entry-quantity"
                name="new-exercise-entry-quantity"
                placeholder="quantity"
                class="form-control"
            >
        </div>

        <div class="form-group">
            <label for="new-ingredient-unit-name">Unit</label>

            <select
                v-model="newEntry.unit"
                v-on:keyup.13="insertEntry()"
                id="new-exercise-entry-unit"
                class="form-control"
            >
                <option
                    v-for="unit in units"
                    v-bind:value="unit"
                >
                    {{ unit.name }}
                </option>
            </select>
        </div>

    </div>
</template>

<script>
    module.exports = {
        template: '#new-exercise-entry-template',
        data: function () {
            return {
                newEntry: {},
                shared: store.state,
                date: store.state.date
            };
        },
        components: {},
        computed: {
            units: function () {
                return this.shared.exerciseUnits;
            }
        },
        methods: {

            /**
             *
             */
            insertEntry: function () {
                var data = {
                    date: this.date.sql,
                    exercise_id: this.newEntry.id,
                    quantity: this.newEntry.quantity,
                    unit_id: this.newEntry.unit.id
                };

                helpers.post('/api/exerciseEntries', data, 'Entry created', function (response) {
                    store.getExerciseEntriesForTheDay();
                }.bind(this));
            }
        },
        props: [

        ],
        events: {
            'option-chosen': function (option) {
                this.newEntry = option;
                this.newEntry.unit = option.defaultUnit.data;
                this.newEntry.quantity = option.defaultQuantity;
            }
        },
        ready: function () {

        }
    };
</script>

