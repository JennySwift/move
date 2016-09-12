<template>
    <div>
        <div>
            <h3 class="center">{{  shared.exercise.name }}</h3>

            <div class="flex">

                <div>
                    <h5 class="center">name</h5>
                    <input
                        v-model="shared.exercise.name"
                        type="text"
                        placeholder="name"
                        class="form-control">
                </div>

                <div>
                    <h5 class="center">description</h5>
                    <input
                        v-model="shared.exercise.description"
                        type="text"
                        placeholder="description"
                        class="form-control">
                </div>

                <div class="step">
                    <h5 class="center">step</h5>
                    <input
                        v-model="shared.exercise.stepNumber"
                        type="text"
                        placeholder="step number"
                        class="form-control">
                </div>

                <div class="priority">
                    <h5 class="center">priority</h5>
                    <input
                        v-model=" shared.exercise.priority"
                        type="text"
                        placeholder="priority"
                        class="form-control">
                </div>

                <div>
                    <h5 class="center">target</h5>
                    <input
                        v-model="shared.exercise.target"
                        type="text"
                        placeholder="target"
                        class="form-control">
                </div>

                <div class="default-quantity">
                    <h5 class="center tooltipster" title="This figure will be used, along with the default unit, when using the feature to quickly log a set of your exercise">default quantity</h5>

                    <input
                        v-model=" shared.exercise.defaultQuantity"
                        type="text"
                        placeholder="enter quantity"
                        class="form-control">
                </div>

                <div>
                    <h5 class="center">Stretch</h5>
                    <input
                        v-model="shared.exercise.stretch"
                        type="checkbox"
                        class="form-control">
                </div>

            </div>

            <div class="form-group">
                <label for="selected-exercise-frequency">Frequency</label>
                <input
                    v-model="shared.exercise.frequency"
                    type="text"
                    id="selected-exercise-frequency"
                    name="selected-exercise-frequency"
                    placeholder="frequency"
                    class="form-control"
                >
            </div>

            <div class="flex">

                <div>
                    <h5 class="center">series</h5>

                    <li
                        v-for="series in exerciseSeries"
                        class="list-group-item hover pointer"
                        v-bind:class="{'selected': series.id ===  shared.exercise.series.id}"
                        v-on:click=" shared.exercise.series.id = series.id">
                        {{ series.name }}
                    </li>

                </div>

                <div>
                    <h5 class="center">program</h5>

                    <li
                        v-for="program in programs"
                        class="list-group-item hover pointer"
                        v-bind:class="{'selected': program.id ===  shared.exercise.program.id}"
                        v-on:click=" shared.exercise.program.id = program.id">
                        {{ program.name }}
                    </li>

                </div>

                <div>
                    <h5 class="center">default unit</h5>
                    <li
                        v-for="unit in units"
                        class="list-group-item hover pointer"
                        v-bind:class="{'selected': unit.id ===  shared.exercise.defaultUnit.data.id}"
                        v-on:click=" shared.exercise.defaultUnit.data.id = unit.id">
                        {{ unit.name }}
                    </li>
                </div>

            </div>
        </div>
    </div>

</template>

<script>
    var ExercisesRepository = require('../repositories/ExercisesRepository');
    var $ = require('jquery');

    module.exports = {
        template: '#exercise-popup-template',
        data: function () {
            return {
                shared: store.state,
                redirectTo: '/exercises'
            };
        },
        computed: {
            units: function () {
                return this.shared.exerciseUnits;
            },
            programs: function () {
                return this.shared.programs;
            },
            exerciseSeries: function () {
                return this.shared.exerciseSeries;
            }
        },
        components: {},
        methods: {

            /**
            *
            */
            getExercise: function () {
                var id = this.$route.params.id;

                helpers.get({
                    url: '/api/exercises/' + id,
                    storeProperty: 'exercise',
                    loadedProperty: 'exerciseLoaded',
                });
            },

            /**
            *
            */
            updateExercise: function () {
                var data = ExercisesRepository.setData(this.shared.exercise);

                helpers.put({
                    url: '/api/exercises/' + this.shared.exercise.id,
                    data: data,
//                    property: 'exercises',
                    message: 'Exercise updated',
                    redirectTo: this.redirectTo,
                    callback: function (response) {
                        this.shared.exercise = response.data.data;
                        store.update(response.data.data, 'exercises');
                        this.showPopup = false;
                        $("#exercise-step-number").val("");
                    }.bind(this)
                });
            },

            /**
            *
            */
            deleteExercise: function () {
                helpers.delete({
                    url: '/api/exercises/' + this.shared.exercise.id,
                    array: 'exercises',
                    itemToDelete: this.shared.exercise,
                    message: 'Exercise deleted',
                    redirectTo: this.redirectTo,
                    callback: function () {
                        this.showPopup = false;
                    }.bind(this)
                });
            }
        },
        ready: function () {
            if (!this.shared.exercise.id) {
                this.getExercise();
            }
        }
    };

</script>