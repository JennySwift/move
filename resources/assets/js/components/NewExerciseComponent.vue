<template>
    <div>
        <div class="input-group-container">
            <input-group
                label="Name:"
                :model.sync="newExercise.name"
                :enter="insertExercise"
                id="new-exercise-name"
            >
            </input-group>

            <input-group
                label="Description:"
                :model.sync="newExercise.description"
                :enter="insertExercise"
                id="new-exercise-description"
            >
            </input-group>

            <input-group
                label="Step Number:"
                :model.sync="newExercise.stepNumber"
                :enter="insertExercise"
                id="new-exercise-step-number"
            >
            </input-group>

            <input-group
                label="Priority:"
                :model.sync="newExercise.priority"
                :enter="insertExercise"
                id="new-exercise-priority"
            >
            </input-group>

            <input-group
                label="Target:"
                :model.sync="newExercise.target"
                :enter="insertExercise"
                id="new-exercise-target"
            >
            </input-group>

            <input-group
                label="Program:"
                :model.sync="newExercise.program"
                :enter="insertExercise"
                id="new-exercise-program"
                :options="shared.exercisePrograms"
                options-prop="name"
            >
            </input-group>

            <input-group
                label="Series:"
                :model.sync="newExercise.series"
                :enter="insertExercise"
                id="new-exercise-series"
                :options="shared.exerciseSeries"
                options-prop="name"
            >
            </input-group>

            <input-group
                label="Default Unit:"
                :model.sync="newExercise.defaultUnit"
                :enter="insertExercise"
                id="new-exercise-default-unit"
                :options="shared.exerciseUnits"
                options-prop="name"
            >
            </input-group>

            <input-group
                label="Default Quantity:"
                :model.sync="newExercise.defaultQuantity"
                :enter="insertExercise"
                id="new-exercise-default-quantity"
            >
            </input-group>

            <input-group
                label="Frequency:"
                :model.sync="newExercise.frequency"
                :enter="insertExercise"
                id="new-exercise-frequency"
            >
            </input-group>
        </div>

        <buttons
            :save="insertExercise"
            :redirect-to="redirectTo"
        >
        </buttons>

    </div>
</template>

<script>
    import ExercisesRepository from '../repositories/ExercisesRepository'
    export default {
        data: function () {
            return {
                newExercise: {
                    series: {},
                    defaultUnit: {},
                    program: {}
                },
                shared: store.state,
                redirectTo: '/exercises'
            };
        },
        components: {},
        computed: {

        },
        methods: {

            /**
             *
             */
            insertExercise: function () {
                var data = ExercisesRepository.setData(this.newExercise);

                helpers.post({
                    url: '/api/exercises',
                    data: data,
                    array: 'exercises',
                    message: 'Exercise created',
//                    clearFields: this.clearFields,
                    redirectTo: this.redirectTo,
                    callback: function () {
                        this.showPopup = false;
                    }.bind(this)
                });
            },

            /**
             *
             */
            setDefaults: function () {
                var that = this;
                setTimeout(function () {
                    if (that.shared.exerciseSeriesLoaded && that.shared.exerciseUnitsLoaded && that.shared.exerciseProgramsLoaded) {
                        that.newExercise.series = that.shared.exerciseSeries[0];
                        that.newExercise.defaultUnit = that.shared.exerciseUnits[0];
                        that.newExercise.program = that.shared.exercisePrograms[0];
                    }
                    else {
                        //We still need to wait for things to load
                        that.setDefaults();
                    }
                }, 500);

            }
        },
        mounted: function () {
            this.setDefaults();
        }
    }
</script>

<style lang="scss" type="text/scss">

</style>
