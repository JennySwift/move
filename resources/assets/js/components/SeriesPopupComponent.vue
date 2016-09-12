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
                var data = {
                    name: this.selectedSeries.name,
                    priority: this.selectedSeries.priority,
                    workout_ids: this.selectedSeries.workout_ids
                };

                helpers.put({
                    url: '/api/exerciseSeries/' + this.selectedSeries.id,
                    data: data,
                    property: 'exerciseSeries',
                    message: 'Series updated',
                    redirectTo: this.redirectTo,
                    callback: function (response) {
                        store.update(response, 'exerciseSeries');
                        router.go(this.redirectTo);
                    }.bind(this)
                });
            },

            /**
            *
            */
            deleteSeries: function () {
                helpers.delete({
                    url: '/api/exerciseSeries/' + this.selectedSeries.id,
                    array: 'exerciseSeries',
                    itemToDelete: this.exerciseSeries,
                    message: 'Series deleted',
                    redirectTo: this.redirectTo
                });
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

