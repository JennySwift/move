require('sugar');

export default {
    changeDate: function (date) {
        var date = Date.create(date).format('{yyyy}-{MM}-{dd}');
        store.setDate(date);
    },
    today: function () {
        var date = Date.create('today').format('{yyyy}-{MM}-{dd}');
        store.setDate(date);
    },
    goToDate: function (number) {
        var date = Date.create(store.state.date.typed).addDays(number).format('{yyyy}-{MM}-{dd}');
        store.setDate(date);
    },

    formatForUser: function (date) {
        //For 'Wed' do {Dow}
        return Date.create(date).format('{d} {Mon}');
    },

    convertDurationToSeconds: function (duration) {
        var seconds = 0;
        seconds+= (duration.hours * 3600);
        seconds+= (duration.minutes * 60);
        seconds+= (duration.seconds * 1);

        return seconds;
    },

    /**
     * Convert seconds to a more readable time format
     * @param seconds
     * @returns {string}
     */
    filterTime: function (seconds) {
        var hours = Math.floor(seconds / 3600);
        seconds %= 3600;
        var minutes = Math.floor(seconds / 60);
        seconds %= 60;

        var string = '';

        if (hours) {
            string += hours + 'h';
        }
        if (minutes) {
            string+= minutes + 'm';
        }
        if (seconds) {
            string+= seconds + 's';
        }

        return string;
    },

    getDaysAgo: function (dateTime) {
        //Converting to date from dateTime because otherwise, daysAgo is 0 if it's yesterday but less than 24 hours ago.
        var date = Date.create(Date.create(dateTime).format('{yyyy}-{MM}-{dd}'));
        var daysAgo = date.daysAgo();
        if (daysAgo === 0){
            return 'Today';
        }
        if (daysAgo === 1) {
            return 'Yesterday';
        }
        return daysAgo + ' days ago';
        // return Date.create(date).relative();
    },
    /**
     *
     * @param dateAndTime
     * @returns {*}
     */
    convertToDateTime: function (dateAndTime) {
        if (!dateAndTime || dateAndTime === '') {
            return null;
        }

        var dateTime = Date.create(dateAndTime).format('{yyyy}-{MM}-{dd} {HH}:{mm}:{ss}');

        if (dateTime == 'Invalid Date') {
            //Only add my shortcuts if the date is invalid for Sugar
            if (dateAndTime == 't') {
                dateAndTime = 'today';
            }
            else if (dateAndTime == 'to') {
                dateAndTime = 'tomorrow';
            }
            else if (dateAndTime == 'y') {
                dateAndTime = 'yesterday';
            }

            dateTime = Date.create(dateAndTime).format('{yyyy}-{MM}-{dd} {HH}:{mm}:{ss}');
        }

        return dateTime;
    },

    /**
     *
     * @param dateTime
     * @param format - format to convert to
     * @returns {*}
     */
    convertFromDateTime: function (dateTime, format) {
        format = format || 'ddd DD MMM YYYY';
        return moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format(format);
    },

}
