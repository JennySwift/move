<template>
    <div>
        <f7-button :actions-open="'#' + id">Actions</f7-button>

        <f7-actions :id="id">
            <f7-actions-group>
                <f7-actions-button v-on:click="addSet(tableData[0])">Add Set</f7-actions-button>
                <f7-actions-button v-on:click="setExercise()">
                    <f7-link :href="'/exercises/' + tableData[0].exercise_id">View Exercise</f7-link>
                </f7-actions-button>
                <!--<f7-actions-button>-->
                <!--<f7-link :href="'/exercises/' + exercise[0].exercise_id + '/history'">View History</f7-link>-->
                <!--</f7-actions-button>-->

                <f7-actions-button v-on:click="openPopup()">
                    History
                </f7-actions-button>

                <f7-actions-button v-if="page === 'session'" v-on:click="updateSetsForOneExerciseInWorkout()">
                    Update Workout
                </f7-actions-button>
            </f7-actions-group>
        </f7-actions>
    </div>
</template>

<script>
    import ActionsRepository from "../../repositories/ActionsRepository";

    export default {
        data: function () {
          return {
              shared: store.state
          }
        },
        computed: {
            id: function () {
              return this.tableData[0].exercise_id + '-' + this.page + 'page-actions';
            }
        },
        props: [
            'tableData',
            'addSet',
            'page',
        ],
        methods: {
            openPopup: function () {
                this.setExercise();
                store.openHistoryPopup();
            },

            /**
             *
             */
            setExercise: function () {
                var exercise = helpers.findById(this.shared.exercises, this.tableData[0].exercise_id);
                store.set(exercise, 'exercise');
            },

            /**
             * For updating sets for just one exercise in a workout to match what I did in the session
             */
            updateSetsForOneExerciseInWorkout: function () {
                ActionsRepository.updateSetsForOneExerciseInWorkout(this.tableData);
            },
        }
    }
</script>

<style lang="scss" type="text/scss">

</style>