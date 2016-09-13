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
    module.exports = {
        template: '#new-series-template',
        data: function () {
            return {
                newSeries: {},
                redirectTo: '/exercises'
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
        ready: function () {

        }
    };
</script>

