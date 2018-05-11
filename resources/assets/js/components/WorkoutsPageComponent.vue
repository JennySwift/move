<template>
    <f7-page>
        <f7-navbar back-link="Back">
            <f7-nav-title>Workouts</f7-nav-title>
            <f7-nav-right>
                <f7-link href="/add-workout"><f7-icon f7="add"></f7-icon></f7-link>
                <f7-link icon-if-ios="f7:menu" icon-if-md="material:menu" panel-open="right"></f7-link>
            </f7-nav-right>
        </f7-navbar>

        <f7-list contacts-list>
            <f7-list-group>
                <f7-list-item
                    v-for="workout in shared.workouts"
                    :link="'/workouts/' + workout.id + '/edit'"
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