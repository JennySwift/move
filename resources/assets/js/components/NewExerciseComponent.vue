<template>
    <f7-page>
        <f7-navbar title="New Exercise" back-link="Back"></f7-navbar>

        <f7-list no-hairlines-md contacts-list>
            <f7-list-item>
                <f7-label>Enter a name</f7-label>
                <f7-input type="text" :value="newExercise.name" @input="newExercise.name = $event.target.value" clear-button=""></f7-input>
            </f7-list-item>

            <f7-list-item>
                <f7-label>Enter a description (optional)</f7-label>
                <f7-input type="text" :value="newExercise.description" @input="newExercise.description = $event.target.value" clear-button=""></f7-input>

            </f7-list-item>

            <f7-list-item>
                <f7-label>Enter a priority (number)</f7-label>
                <f7-input type="text" :value="newExercise.priority" @input="newExercise.priority = $event.target.value" clear-button=""></f7-input>
            </f7-list-item>
        </f7-list>

        <f7-block>
            <buttons
            :save="insertExercise"
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
                newExercise: {},
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
        }
    }
</script>

<style lang="scss" type="text/scss">
    #new-exercise-page {
        padding-top: 15px;
    }
</style>
