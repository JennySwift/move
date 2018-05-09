<template>
    <div id="workout-page">

        <div class="top-bar">
            <h1>{{shared.workout.name}}</h1>
            <router-link to="/workouts" tag="i" class="close far fa-times-circle fa-2x"></router-link>
        </div>

        <div class="container">

            <div v-if="sortedExercises.length > 0" v-for="exercise in sortedExercises">
                {{exercise[0].name}}
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>LEVEL</th>
                        <th>{{exercise[0].unit.data.name}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="row in exercise">
                        <td>{{row.level}}</td>
                        <td>{{row.quantity}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <router-link :to="'/workouts/' + shared.workout.id + '/edit'" tag="button" id="edit-workout-btn" class="new-btn btn btn-default">Edit Workout</router-link>
        </div>

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
        computed: {
            sortedExercises: function () {
                if (this.shared.workout.exercises) {
                    var sorted = _.groupBy(this.shared.workout.exercises.data, 'name');
                    console.log(sorted);
                    return sorted;
                }
            }
        },
        methods: {
            /**
             *
             */
            getWorkout: function () {
                var id = this.$route.params.id;

                helpers.get({
                    url: this.baseUrl + '/' + id + '?include=exercises',
                    storeProperty: 'workout'
                });
            },
        },
        mounted: function () {
            this.getWorkout();
//            if (!this.shared.workout.exercises) {
//                this.getWorkout();
//            }
        }
    }
</script>

<style lang="scss" type="text/scss">
    #workout-page {
        th {
            text-align:center;
        }
    }
</style>