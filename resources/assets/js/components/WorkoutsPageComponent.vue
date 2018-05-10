<template>
    <div id="workouts-page">

        <div class="container">

            <ul class="list-group">
                <router-link
                    v-for="workout in shared.workouts"
                    :to="'/workouts/' + workout.id + '/edit'"
                    tag="li"
                    class="list-group-item pointer"
                    v-on:click="setWorkout(workout)"
                    v-bind:key="workout.id"
                >
                    {{workout.name}}
                </router-link>
            </ul>

            <router-link to="/add-workout" tag="button" id="add-workout-btn" class="new-btn btn btn-default">Add Workout</router-link>
        </div>


    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                shared: store.state
            }
        },
        methods: {
            /**
             *
             */
            setWorkout: function (workout) {
                store.set(workout, 'workout');
            },
        }
    }
</script>

<style lang="scss" type="text/scss">

</style>