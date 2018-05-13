<template>
    <f7-page>
        <navbar :title="shared.exercise.name" popover-id="edit-exercise">
        </navbar>

        <f7-list no-hairlines-md contacts-list>
            <f7-list-item>
                <f7-label>Enter a name</f7-label>
                <f7-input type="text" :value="shared.exercise.name" @input="shared.exercise.name = $event.target.value" clear-button=""></f7-input>
            </f7-list-item>

            <f7-list-item>
                <f7-label>Enter a description (optional)</f7-label>
                <f7-input type="text" :value="shared.exercise.description" @input="shared.exercise.description = $event.target.value" clear-button=""></f7-input>

            </f7-list-item>

            <f7-list-item>
                <f7-label>Enter a priority (number)</f7-label>
                <f7-input type="text" :value="shared.exercise.priority" @input="shared.exercise.priority = $event.target.value" clear-button=""></f7-input>
            </f7-list-item>
        </f7-list>

       <f7-block>
           <buttons
               :save="updateExercise"
               :destroy="deleteExercise"
               :redirect-to="redirectTo"
           >
           </buttons>
       </f7-block>

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
            this.getExercise();
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