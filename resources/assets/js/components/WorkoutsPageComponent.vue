<template>
    <f7-view>
        <f7-page>
            <f7-navbar title="Workouts" back-link="Back" back-link-url="/"></f7-navbar>
            <div id="workouts-page">

                <div class="container">

                    <ul class="list-group">
                        <f7-list-item
                            v-for="workout in shared.workouts"
                            :link="'/workouts/' + workout.id + '/edit'"
                            v-bind:title="workout.name"
                            v-on:click="setWorkout(workout)"
                            v-bind:key="workout.id"
                        >
                        </f7-list-item>
                        <!--<router-link-->
                        <!--v-for="workout in shared.workouts"-->
                        <!--:to="'/workouts/' + workout.id + '/edit'"-->
                        <!--tag="li"-->
                        <!--class="list-group-item pointer"-->
                        <!--v-on:click="setWorkout(workout)"-->
                        <!--v-bind:key="workout.id"-->
                        <!--&gt;-->
                        <!--{{workout.name}}-->
                        <!--</router-link>-->
                    </ul>

                    <f7-list-item link="/add-workout" title="Add Workout"></f7-list-item>
                </div>


            </div>
        </f7-page>
    </f7-view>


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