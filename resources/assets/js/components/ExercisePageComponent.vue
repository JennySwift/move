<template>
    <div>
        <h3 class="center">{{  shared.exercise.name }}</h3>

        <div class="input-group-container">
            <input-group
                label="Name:"
                :model.sync="shared.exercise.name"
                :enter="updateExercise"
                id="exercise-name"
            >
            </input-group>

            <input-group
                label="Description:"
                :model.sync="shared.exercise.description"
                :enter="updateExercise"
                id="exercise-description"
            >
            </input-group>

            <input-group
                label="Step Number:"
                :model.sync="shared.exercise.stepNumber"
                :enter="updateExercise"
                id="exercise-step-number"
            >
            </input-group>

            <input-group
                label="Priority:"
                :model.sync="shared.exercise.priority"
                :enter="updateExercise"
                id="exercise-priority"
            >
            </input-group>

            <input-group
                label="Target:"
                :model.sync="shared.exercise.target"
                :enter="updateExercise"
                id="exercise-target"
            >
            </input-group>

            <input-group
                label="Default Quantity:"
                :model.sync="shared.exercise.defaultQuantity"
                :enter="updateExercise"
                id="exercise-default-quantity"
            >
            </input-group>

            <input-group
                label="Frequency:"
                :model.sync="shared.exercise.frequency"
                :enter="updateExercise"
                id="exercise-frequency"
            >
            </input-group>

            <input-group
                label="Series:"
                :model.sync="shared.exercise.series"
                :enter="updateExercise"
                id="exercise-series"
                :options="shared.exerciseSeries"
                options-prop="name"
            >
            </input-group>

            <input-group
                label="Program:"
                :model.sync="shared.exercise.program"
                :enter="updateExercise"
                id="exercise-program"
                :options="shared.exercisePrograms"
                options-prop="name"
            >
            </input-group>

            <input-group
                label="Default Unit:"
                :model.sync="shared.exercise.defaultUnit"
                :enter="updateExercise"
                id="exercise-default-unit"
                :options="shared.exerciseUnits"
                options-prop="name"
            >
            </input-group>

            <checkbox-group
                label="Stretch:"
                :model.sync="shared.exercise.stretch"
                id="exercise-stretch"
            >
            </checkbox-group>

        </div>
        
        <buttons
            :save="updateExercise"
            :destroy="deleteExercise"
            :redirect-to="redirectTo"
        >
        </buttons>
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
                    property: 'exercises',
                    message: 'Exercise updated',
                    redirectTo: this.redirectTo
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