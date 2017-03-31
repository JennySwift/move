module.exports = {

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
     * @param number
     * @returns {*}
     */
    removeUnnecessaryZeros: function (number) {
        if (number.substr(number.length-3, 3) === ".00")  {
            //Remove the decimal and two zeros.
            return number.slice(0, number.length - 3);
        }
        else if (number[number.length-1] === "0" && number.indexOf(".") !== -1)  {
            return number.slice(0, number.length - 1);
        }
        return number;
    }
};