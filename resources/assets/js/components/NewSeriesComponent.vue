<template>
    <div>
        <div class="input-group-container">
            <input-group
                label="Name:"
                :model.sync="newSeries.name"
                :enter="insertSeries"
                id="new-series-name"
            >
            </input-group>

            <input-group
                label="Priority:"
                :model.sync="newSeries.priority"
                :enter="insertSeries"
                id="new-series-priority"
            >
            </input-group>
        </div>

        <buttons
            :save="insertSeries"
            :redirect-to="redirectTo"
        >
        </buttons>

    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                newSeries: {},
                redirectTo: '/exercises',
                baseUrl: 'api/series'
            };
        },
        components: {},
        methods: {

            /**
             *
             */
            insertSeries: function () {
                var data = {
                    name: this.newSeries.name,
                    priority: this.newSeries.priority
                };

                helpers.post({
                    url: this.baseUrl,
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
    }
</script>

<style lang="scss" type="text/scss">

</style>

