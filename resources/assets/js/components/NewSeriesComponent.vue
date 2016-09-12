<template>
    <div v-show="showNewSeriesFields" class="margin-bottom">
        <div>
            <input
                v-model="newSeries.name"
                v-on:keyup.13="insertSeries()"
                type="text"
                placeholder="Add a new series"
                id="exercise-series"
                class="form-control"
            >
        </div>

        <div>
            <button
                v-on:click="showNewSeriesFields = false"
                class="btn btn-default"
            >
                Close
            </button>
            <button
                v-on:click="insertSeries()"
                class="btn btn-success"
            >
                Add series
            </button>
        </div>

    </div>

</template>

<script>
    module.exports = {
        template: '#new-series-template',
        data: function () {
            return {
                newSeries: {}
            };
        },
        components: {},
        methods: {

            /**
            *
            */
            insertSeries: function () {
                var data = {
                    name: this.newSeries.name
                };

                helpers.post({
                    url: '/api/exerciseSeries',
                    data: data,
                    array: 'exerciseSeries',
                    message: 'Series created',
                    clearFields: this.clearFields,
                    redirectTo: this.redirectTo
                });
            },

            /**
             *
             */
            clearFields: function () {
                this.newSeries.name = '';
            }
        },
        props: [
            'showNewSeriesFields',
        ],
        ready: function () {

        }
    };
</script>

