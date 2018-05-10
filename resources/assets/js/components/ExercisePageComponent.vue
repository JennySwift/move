<template>
    <f7-page>
        <f7-navbar title="Exercise" back-link="Back"></f7-navbar>
        <div id="exercise-page">

            <div class="container">
                <input type="text"
                       class="center invisible-input"
                       v-model="shared.exercise.name"
                       v-on:enter="updateExercise"
                />

                <label for="exercise-description">Description</label>
                <div      id="exercise-description"
                          contenteditable="true"
                          v-model="shared.exercise.description"
                          v-on:enter="updateExercise"
                >
                    {{shared.exercise.description}}
                </div>

                <div class="input-group-container">
                    <input-group
                        label="Priority:"
                        :model.sync="shared.exercise.priority"
                        :enter="updateExercise"
                        id="exercise-priority"
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

        </div>

    </f7-page>

</template>

<script>
    import ExercisesRepository from '../repositories/ExercisesRepository'
    export default {
        data: function () {
            return {
                shared: store.state,
                redirectTo: '/exercises',
                baseUrl: 'api/exercises'
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
            getExercise: function () {
                var id = helpers.getIdFromRouteParams(this);

                helpers.get({
                    url: this.baseUrl + '/' + id,
                    storeProperty: 'exercise',
                    loadedProperty: 'exerciseLoaded',
                });
            },

            /**
             *
             */
            updateExercise: function () {
                store.set($('#exercise-description').text(), 'exercise.description');
                var data = ExercisesRepository.setData(this.shared.exercise);

                helpers.put({
                    url: this.baseUrl + '/' + this.shared.exercise.id,
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
                    url: this.baseUrl + '/' + this.shared.exercise.id,
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
        mounted: function () {
            if (!this.shared.exercise.id) {
                this.getExercise();
            }
        }
    }
</script>

<style lang="scss" type="text/scss">
    @import '../../sass/shared/index';
    #exercise-page {
        #exercise-description {
            width: 100%;
        }
    }
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