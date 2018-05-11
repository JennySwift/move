export default {

    /**
     *
     * @param minutes
     * @returns {*}
     */
    formatDuration: function (minutes) {
        if (!minutes && minutes != 0) {
            return '-';
        }

        var hours = Math.floor(minutes / 60);
        if (hours < 10) {
            hours = '0' + hours;
        }

        minutes = minutes % 60;
        if (minutes < 10) {
            minutes = '0' + minutes;
        }

        return hours + ':' + minutes;
    },

    /**
     *
     * @param number
     * @param howManyDecimals
     * @returns {number}
     */
    roundNumber: function (number, howManyDecimals) {
        if (!howManyDecimals) {
            return Math.round(number);
        }

        var multiplyAndDivideBy = Math.pow(10, howManyDecimals);
        return Math.round(number * multiplyAndDivideBy) / multiplyAndDivideBy;
    },

    /**
     *
     * @param boolean
     * @returns {number}
     */
    convertBooleanToInteger: function (boolean) {
        if (boolean) {
            return 1;
        }
        return 0;
    },

    /**
     *
     * @param number
     * @returns {*}
     */
    // removeUnnecessaryZeros: function (number) {
    //     if (number && number.substr(number.length-3, 3) === ".00")  {
    //         //Remove the decimal and two zeros.
    //         return number.slice(0, number.length - 3);
    //     }
    //     else if (number[number.length-1] === "0" && number.indexOf(".") !== -1)  {
    //         return number.slice(0, number.length - 1);
    //     }
    //     return number;
    // },
    //
    // removeUnnecessaryZeros: function (number) {
    //     if (number) {
    //         return filters.removeUnnecessaryZeros(number);
    //     }
    //     return false;
    // },

    /**
     *
     * @param number
     * @returns {*}
     */
    addZeros: function (number) {
        if (number < 10) {
            return '0' + number;
        }

        return number;
    },

    filterExercises: function (exercises) {
        var that = this;

        //Sort
        exercises = _.chain(exercises)
            .sortBy(function (exercise) {return exercise.stepNumber})
            .sortBy(function (exercise) {
                return exercise.series.data.id;
            })
            .sortBy('priority')
            .sortBy(function (exercise) {
                return exercise.lastDone * -1;
            })
            .partition(function (exercise) {
                return exercise.lastDone === null;
            })
            .flatten()
            .sortBy('dueIn')
            .value();

        //Filter
        return exercises.filter(function (exercise) {
            var filteredIn = true;

            //Priority filter
            if (that.filters.priority && exercise.priority != that.filters.priority) {
                filteredIn = false;
            }

            //Name filter
            if (that.filters.name && exercise.name.indexOf(that.filters.name) === -1) {
                filteredIn = false;
            }

            //Description filter
            if (exercise.description && exercise.description.indexOf(that.filters.description) === -1) {
                filteredIn = false;
            }

            else if (!exercise.description && that.filters.description !== '') {
                filteredIn = false;
            }

            //Stretches files
            if (!that.filters.showStretches && exercise.stretch) {
                filteredIn = false;
            }

            //Series filter
            if (that.filters.series && exercise.series.data.name != that.filters.series.name && that.filters.series.value !== 'all') {
                filteredIn = false;
            }

            return filteredIn;
        });
    },

    filterSeries: function (series) {
        var that = this;

        //Sort
        series = _.chain(series)
            .sortBy('priority')
            .sortBy('lastDone')
            .value();

        /**
         * @VP:
         * This method feels like a lot of code for just
         * a simple thing-ordering series by their lastDone value,
         * putting those with a null lastDone value on the end.
         * I tried underscore.js _.partition with _.flatten,
         * but it put 0 values on the end,
         * (I had trouble getting the predicate parameter of the _.partition method to work.)
         */
        series = this.moveLastDoneNullToEnd(series);

        //Filter
        return series.filter(function (thisSeries) {
            var filteredIn = true;

            //Priority filter
            if (that.priorityFilter && thisSeries.priority != that.priorityFilter) {
                filteredIn = false;
            }

            return filteredIn;
        });
    },
}