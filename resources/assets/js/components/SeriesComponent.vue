<template>
    <div id="series-page">

        <div
            v-for="series in shared.exerciseSeries"
            class="pointer exercise card"
        >
            <div v-bind:style="{background: series.color}" v-bind:class="{'has-color': series.color}" class="card-header">
                {{series.name}}
            </div>
            <div class="card-block">
                <!--<h4 class="card-title exercise-name">{{ exercise.name }}</h4>-->
                <!--<p class="card-text text-left"></p>-->

                <button class="btn btn-default" v-on:click="updateSeries(series)">Edit</button>
            </div>

        </div>

    </div>

</template>

<script>
    module.exports = {
        template: 'series-template',
        data: function () {
            return {
                shared: store.state,
                selectedSeries: {}
            };
        },
        components: {},
        methods: {


            /**
            *
            */
            updateSeries: function (series) {
                this.selectedSeries = series;
                var data = {
                    color: prompt("Enter a color.")
                };

                helpers.put({
                    url: '/api/exerciseSeries/' + this.selectedSeries.id,
                    data: data,
                    property: 'exerciseSeries',
                    message: 'Series updated',
                    redirectTo: this.redirectTo,
                    callback: function (response) {

                    }.bind(this)
                });
            }
        },
        ready: function () {

        }
    };
</script>

