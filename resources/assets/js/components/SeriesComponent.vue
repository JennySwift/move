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

                <button v-bind:style="{background: series.color}" v-bind:class="{'has-color': series.color}" class="btn btn-default btn-sm" v-on:click="updateSeries(series)">Edit</button>
            </div>

        </div>

    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                shared: store.state,
                selectedSeries: {},
                baseurl: 'api/series'
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
                    url: this.baseUrl + '/' + this.selectedSeries.id,
                    data: data,
                    property: 'exerciseSeries',
                    message: 'Series updated',
                    redirectTo: this.redirectTo,
                    callback: function (response) {

                    }.bind(this)
                });
            }
        },
    }
</script>


<style lang="scss" type="text/scss">
    #series-page {
        .series.has-color {
            color: white;
        }
        .card {
            margin-bottom: 8px;
        }
    }
</style>

