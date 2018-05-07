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
                :model.sync="shared.exercise.series.data"
                :enter="updateExercise"
                id="exercise-series"
                :options="shared.exerciseSeries"
                options-prop="name"
            >
            </input-group>

            <input-group
                label="Default Unit:"
                :model.sync="shared.exercise.defaultUnit.data"
                :enter="updateExercise"
                id="exercise-default-unit"
                :options="shared.exerciseUnits"
                options-prop="name"
            >
            </input-group>

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
    import ExercisesRepository from '../repositories/ExercisesRepository'
    export default {
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
    }
</script>

<style lang="scss" type="text/scss">
    @import '../../sass/shared/index';
    #exercise-popup {
        h3 {
            &:first-child {
                margin-top: 0;
                margin-bottom: 25px;
            }
        }
        .flex {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        input {
            background: #FEE6BE;
        }
        .step, .priority, .default-quantity {
            input {
                width: 70px;
            }
        }
    }

    #exercise-series {
        @include newExercise;
        .top-buttons {
            display: flex;
            justify-content: space-between;
            > * {
                margin: 0 5px;
            }
        }
        .dropdown-menu {
            a {
                cursor: pointer;
            }
        }
        .series-name {
            background: black;
            color: white;
            font-weight: bold;
            td {
                text-align: center;
            }
        }
        td {
            text-align: left;
        }
        .stretch {
            background: lighten(coral, 20%);
        }
        .series-exercises-container {
            display: flex;
            justify-content: center;
            > * {
                margin: 0 50px;
            }
            #series-table {
                .name {
                    cursor: pointer;
                }
                .actions {
                    text-align: left;
                }
            }
            #exercises-table {
                td, th {
                    text-align: left;
                    padding: 10px 15px;
                }
                td {
                    border-top: none;
                    cursor: pointer;
                    //&:first-child {
                    //    border-left: 1px solid #ddd;
                    //}
                    //&:last-child {
                    //    border-right: 1px solid #ddd;
                    //}
                }
                tr {
                    border: 1px solid #ddd;
                    &:first-child {
                        border-top-left-radius: 4px;
                        border-top-right-radius: 4px;
                    }
                }
                //tr:last-child {
                //    border-bottom: 1px solid #ddd;
                //}
            }
        }

        @media (max-width: $breakpoint1) {
            .series-name {
                td {
                    padding: 3px 0;
                }
            }
            td {
                padding: 1px 0;
            }
            .big-screens {
                display: none;
            }
        }
    }
</style>