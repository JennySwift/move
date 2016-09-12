<template>
    <div id="exercise-units-page">
        <div class="new-exercise-unit-container">

            <div class="form-group">
                <label for="new-exercise-unit-name">Name</label>
                <input
                    v-model="newUnit.name"
                    v-on:keyup.13="insertUnit()"
                    type="text"
                    id="new-exercise-unit-name"
                    name="new-exercise-unit-name"
                    placeholder="name"
                    class="form-control"
                >
            </div>

            <div class="form-group">
                <button
                    v-on:click="insertUnit()"
                    class="btn btn-success"
                >
                    Add unit
                </button>
            </div>

        </div>

        <div class="exercise-units-container">
            <div class="exercise-units">
                <li
                    v-for="unit in units
                        | orderBy 'name'"
                    class="list-group-item"
                >
                    {{ unit.name }}
                    <i v-on:click="deleteUnit(unit)" class="delete-item fa fa-times"></i>
                </li>
            </div>
        </div>

    </div>
</template>

<script>
    module.exports = {
        template: '#exercise-units-page-template',
        data: function () {
            return {
                newUnit: {},
                shared: store.state
            };
        },
        computed: {
            units: function () {
                return this.shared.exerciseUnits;
            }
        },
        components: {},
        methods: {

            /**
            *
            */
            insertUnit: function () {
                var data = {
                    name: this.newUnit.name
                };

                helpers.post({
                    url: '/api/exerciseUnits',
                    data: data,
                    array: 'exerciseUnits',
                    message: 'Unit created',
                    clearFields: this.clearFields,
                    redirectTo: this.redirectTo
                });
            },

            /**
             *
             */
            clearFields: function () {
                this.newUnit.name = '';
            },

            /**
            *
            */
            deleteUnit: function (unit) {
                helpers.delete({
                    url: '/api/exerciseUnits/' + unit.id,
                    array: 'exerciseUnits',
                    itemToDelete: unit,
                    message: 'Unit deleted',
                    redirectTo: this.redirectTo
                });
            }
        },
        props: [
            //data to be received from parent
        ],
        ready: function () {

        }
    };
</script>

