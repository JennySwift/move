<template>
    <div>
        <popup
            :show-popup.sync="showPopup"
            id="series-popup"
            :redirect-to="redirectTo"
            :update="updateSeries"
            :destroy="deleteSeries"
        >
            <div slot="content">
                <h3 class="popup-title">{{ selectedSeries.name }} series</h3>

                <label for="seriesName">Name your series</label>
                <input v-model="selectedSeries.name" type="text" name="seriesName" placeholder="name"/>

                <div>
                    <label for="exercise-series-priority"></label>
                    <input
                        v-model="selectedSeries.priority"
                        type="text"
                        id="exercise-series-priority"
                        name="exercise-series-priority"
                        placeholder="priority"
                        class="form-control"
                    />
                </div>
            </div>
        </popup>

    </div>
</template>

<script>
    var $ = require('jquery');

    module.exports = {
        template: '#series-popup-template',
        data: function () {
            return {
                showPopup: false,
                selectedSeries: {}
            };
        },
        components: {},
        methods: {

            /**
             *
             */
            updateSeries: function () {
                $.event.trigger('show-loading');

                var data = {
                    name: this.selectedSeries.name,
                    priority: this.selectedSeries.priority,
                    workout_ids: this.selectedSeries.workout_ids
                };

                helpers.put('/api/exerciseSeries/' + this.selectedSeries.id, data, 'Series updated', function (response) {
                    store.update(response.data, 'exerciseSeries');
                    this.showPopup = false;
                }.bind(this));
            },

            /**
             *
             */
            deleteSeries: function () {
                helpers.delete('/api/exerciseSeries/' + this.selectedSeries.id, 'Series deleted', function (response) {
                    store.delete(this.selectedSeries, 'exerciseSeries');
                }.bind(this));
            },

            /**
             *
             */
            closePopup: function ($event) {
                if ($event.target.className === 'popup-outer') {
                    this.showPopup = false;
                }
            },

            /**
             *
             */
            listen: function () {
                var that = this;
                $(document).on('show-series-popup', function (event, series) {
                    that.selectedSeries = series;
                    that.showPopup = true;
                });
            }
        },
        props: [

        ],
        ready: function () {
            this.listen();
        }
    };
</script>

