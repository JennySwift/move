<template>
    <div id="units-page">
        <div class="new-unit">

            <div class="input-group-container">
                <h2>Add a new unit</h2>
                <input-group
                    label="Name:"
                    :model.sync="newUnit.name"
                    :enter="insertUnit"
                    id="new-unit-name"
                >
                </input-group>
            </div>

            <buttons
                :save="insertUnit"
            >
            </buttons>

        </div>

        <div class="units">
            <h2>Units</h2>
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

