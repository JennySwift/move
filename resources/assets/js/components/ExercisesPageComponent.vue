<template>
    <f7-page :page-content="false">
        <navbar title="Exercises" popover-id="exercises">
            <!--<f7-list-item link="/add-exercise/" title="Add Exercise" popover-close></f7-list-item>-->
        </navbar>
        <f7-fab color="green" position="right-bottom" href="/add-exercise/">
            <f7-icon f7="add"></f7-icon>
        </f7-fab>

        <f7-page-content class="ptr-content">
            <div class="ptr-preloader">
                <div class="preloader"></div>
                <div class="ptr-arrow"></div>
            </div>

            <!--<f7-list>-->
                <!--<f7-list-item link="/add-exercise/" title="Add Exercise"></f7-list-item>-->
            <!--</f7-list>-->

            <f7-list contacts-list>
                <f7-list-group>
                    <f7-list-item
                        v-for="exercise in shared.exercises"
                        :link="'/exercises/' + exercise.id"
                        v-bind:title="exercise.name"
                        v-on:click="setExercise(exercise)"
                        v-bind:key="exercise.id"
                    >

                    </f7-list-item>
                </f7-list-group>
            </f7-list>
        </f7-page-content>


    </f7-page>
</template>

<script>

    import ExerciseFiltersComponent from './ExerciseFiltersComponent.vue'
    import ExerciseRepository from '../repositories/ExercisesRepository'

    export default {
        template: '#exercises-page-template',
        data: function () {
            return {
                shared: store.state,
                baseUrl: 'api/series'
            };
        },
        components: {
            'filters': ExerciseFiltersComponent
        },
        methods: {
            /**
             *
             */
            setExercise: function (exercise) {
                store.set(exercise, 'exercise');
            },

            listen: function () {
                $('.ptr-content').on('ptr:refresh', function (e) {
                    store.getExercises({pullToRefresh: true});
                });
            }
        },
        mounted: function () {
            this.listen();
        }
    }
</script>

<style lang="scss" type="text/scss">
    @import '../../sass/shared/index';
    #exercises-page {
        td {
            text-align: left;
        }
        @media (max-width: 320px) {
            .big-screens {
                display: none;
            }
        }
        table {
            width: auto;
            margin: auto;
        }
        .btn-container {
            margin-bottom: 5px;
            display: flex;
            justify-content: flex-end;
        }
        #add-exercise-btn {
            margin-top: 17px;
        }
    }
</style>

