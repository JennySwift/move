<template>
    <f7-actions :id="exercise[0].exercise_id + '-' + id + '-actions'">
        <f7-actions-group>
            <f7-actions-button v-on:click="addSet(exercise[0])">Add Set</f7-actions-button>
            <f7-actions-button v-on:click="setExercise()">
                <f7-link :href="'/exercises/' + exercise[0].exercise_id">View Exercise</f7-link>
            </f7-actions-button>
            <!--<f7-actions-button>-->
                <!--<f7-link :href="'/exercises/' + exercise[0].exercise_id + '/history'">View History</f7-link>-->
            <!--</f7-actions-button>-->

            <f7-actions-button v-on:click="openPopup()">
                History
            </f7-actions-button>
        </f7-actions-group>
    </f7-actions>
</template>

<script>
    export default {
        data: function () {
          return {
              shared: store.state
          }
        },
        props: [
            'exercise',
            'addSet',
            'id'
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
                var exercise = helpers.findById(this.shared.exercises, this.exercise[0].exercise_id);
                store.set(exercise, 'exercise');
            },
        }
    }
</script>

<style lang="scss" type="text/scss">

</style>