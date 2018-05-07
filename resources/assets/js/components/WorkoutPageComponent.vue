<template>
    <div id="workout-page">
        <router-link to="/workouts" tag="i" class="far fa-times-circle fa-2x"></router-link>
        {{shared.workout.name}}
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                shared: store.state,
                baseUrl: 'api/workouts'
            }
        },
        methods: {
            /**
             *
             */
            getWorkout: function () {
                var id = this.$route.params.id;

                helpers.get({
                    url: this.baseUrl + '/' + id,
                    storeProperty: 'workout'
                });
            },
        },
        mounted: function () {
            if (!this.shared.workout.id) {
                this.getWorkout();
            }
        }
    }
</script>

<style lang="scss" type="text/scss">

</style>