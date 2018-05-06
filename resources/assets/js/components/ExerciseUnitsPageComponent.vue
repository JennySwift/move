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
                v-for="unit in units"
                class="list-group-item"
            >
                {{ unit.name }}
                <i v-on:click="deleteUnit(unit)" class="delete-item fa fa-times"></i>
            </li>
        </div>

    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                newUnit: {},
                shared: store.state,
                baseUrl: 'api/units'
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
                    url: this.baseUrl,
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
                    url: this.baseUrl + unit.id,
                    array: 'exerciseUnits',
                    itemToDelete: unit,
                    message: 'Unit deleted',
                    redirectTo: this.redirectTo
                });
            }
        },
        props: [
            //data to be received from parent
        ]
    }
</script>

<style lang="scss" type="text/scss">
    #units-page {
        .new-unit {
        button {
            width: 100%;
        }
        margin-bottom: 25px;
        }
    }
</style>