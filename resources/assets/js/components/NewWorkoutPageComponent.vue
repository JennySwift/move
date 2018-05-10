<template>
    <f7-page>
        <f7-navbar title="New Workout" back-link="Back"></f7-navbar>
        <div id="new-workout-page">

            <div class="container">
                <label for="new-workout-name">Enter a name for your new workout</label>
                <input v-model="newWorkout.name" id="new-workout-name" type="text" class=""/>

                <buttons
                    :save="insertWorkout"
                >
                </buttons>

            </div>

        </div>
    </f7-page>

</template>

<script>
    export default {
        data: function () {
            return {
                shared: store.state,
                baseUrl: '/api/workouts',
                newWorkout: {name: ''},
            }
        },
        methods: {
            /**
            *
            */
            insertWorkout: function () {
                var data = {
                    name: this.newWorkout.name
                };

                helpers.post({
                    url: this.baseUrl,
                    data: data,
                    array: 'workouts',
                    property: 'workout',
                    message: 'Workout created',
                    clearFields: this.clearFields,
                    callback: function (response) {
                        helpers.goToRoute('/workouts/' + response.id);
                    }.bind(this)
                });
            }
        },
    }
</script>

<style lang="scss" type="text/scss">
    #new-workout-page {
        padding-top: 15px;
        label {
            text-align: center;
            width: 100%;
        }
        input {
            margin-bottom: 15px;
        }
    }
</style>