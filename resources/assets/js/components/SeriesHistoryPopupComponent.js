var $ = require('jquery');

module.exports = {
    template: '#series-history-popup-template',
    data: function () {
        return {
            showPopup: false,
            filterByExercise: ''
        };
    },
    components: {},
    filters: {
        exerciseFilter: function (entries) {
            var that = this;
            return entries.filter(function (entriesForDay) {
                return entriesForDay.exercise.data.name.indexOf(that.filterByExercise) !== -1;
            });
        }
    },
    methods: {

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
            $(document).on('show-series-history-popup', function (event, series) {
                that.selectedSeries = series;
                that.showPopup = true;
            });
        }
    },
    props: [
        'exerciseSeriesHistory',
        'selectedSeries'
    ],
    ready: function () {
        this.listen();
    }
};
