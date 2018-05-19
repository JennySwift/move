<template>
    <f7-page id="exercise-page">
        <navbar :title="shared.exercise.name" popover-id="edit-exercise">
        </navbar>

        <history class="history-popup"></history>

        <f7-list contacts-list>
            <f7-list-item v-on:click="openHistoryPopup()" link="false">View History</f7-list-item>
        </f7-list>

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
        methods: {
            openHistoryPopup: function () {
                store.openHistoryPopup();
            },

            /**
             *
             */
            getExercise: function () {
                var id = helpers.getIdFromRouteParams(this);

                helpers.get({
                    url: this.baseUrl + '/' + id,
                    storeProperty: 'exercise',
                    loadedProperty: 'exerciseLoaded'
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
            if (!this.shared.exercise.id) {
                this.getExercise();
            }

        }
    }
</script>

<style lang="scss" type="text/scss">
    @import '../../sass/shared/index';
    #exercise-page {
        .contacts-list {
            margin: 5px 0;
            margin-bottom: 10px;
        }
    }
</style>