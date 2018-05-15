<template>
    <f7-page>
        <navbar title="Workouts" popover-id="workouts">
            <!--<f7-list-item link="/add-workout/" title="Add Workout" popover-close></f7-list-item>-->
        </navbar>

        <f7-fab color="green" position="right-bottom" href="/add-workout/">
            <f7-icon f7="add"></f7-icon>
        </f7-fab>

        <f7-list contacts-list>
            <f7-list-group>
                <f7-list-item
                    v-for="workout in shared.workouts"
                    :link="'/workouts/' + workout.id"
                    v-bind:title="workout.name"
                    v-on:click="setWorkout(workout)"
                    v-bind:key="workout.id"
                >
                </f7-list-item>
            </f7-list-group>
        </f7-list>

    </f7-page>

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