<template>
    <div id="new-exercise-page">
        <div class="container">
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
                    label="Priority:"
                    :model.sync="newExercise.priority"
                    :enter="insertExercise"
                    id="new-exercise-priority"
                >
                </input-group>
            </div>

            <buttons
                :save="insertExercise"
                :redirect-to="redirectTo"
            >
            </buttons>
        </div>


    </div>
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
