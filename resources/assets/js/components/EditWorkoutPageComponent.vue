<template>
    <div id="edit-workout-page">
        <router-link :to="'/workouts/' + shared.workout.id" tag="i" class="far fa-times-circle fa-2x"></router-link>

        <input
            class="center invisible-input"
            v-model="shared.workout.name"
        >
        </input>

        <div v-for="exercise in sortedExercises">
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

        <buttons
            :save="updateWorkout"
        >
        </buttons>

    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                shared: store.state,
                baseUrl: 'api/workouts',
            }
        },
        computed: {
            sortedExercises: function () {
              var sorted = _.groupBy(this.shared.workout.exercises.data, 'name');
              console.log(sorted);
              return sorted;
            },
            redirectTo: function () {
                return '/workouts/' + this.shared.workout.id;
            }
        },
        methods: {
            /**
            *
            */
            updateWorkout: function () {
                var data = {
                    name: this.shared.workout.name
                };

                helpers.put({
                    url: this.baseUrl + '/' + this.shared.workout.id + '?include=exercises',
                    data: data,
                    property: 'workouts',
                    message: 'Workout updated',
                    redirectTo: this.redirectTo,
                    callback: function (response) {

                    }.bind(this)
                });
            },

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