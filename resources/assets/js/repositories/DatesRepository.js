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

    getDaysAgo: function (date) {
        // return Date.create(date).daysAgo();
        return Date.create(date).relative();
    }
}
