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

                helpers.post('/api/exerciseUnits', data, 'Unit added', function (response) {
                    store.add(response.data.data, 'exerciseUnits');
                    this.clearFields();
                }.bind(this));
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
                helpers.delete('/api/exerciseUnits/' + unit.id, 'Unit deleted', function (response) {
                    store.delete(unit, 'exerciseUnits');
                    this.showPopup = false;
                    router.go(this.redirectTo);
                }.bind(this));
            }
        },
        props: [
            //data to be received from parent
        ],
        ready: function () {

        }
    };
</script>

