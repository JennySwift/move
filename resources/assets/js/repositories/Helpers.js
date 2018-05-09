require('sugar');
var moment = require('moment');
require('sweetalert2');
var object = require('lodash/object');

require('jquery');
require('tooltipster');
import requests from './Requests'
import arrays from './Arrays'
import store from './Store'
import Vue from 'vue'
import dates from './DatesRepository'

export default {

    //Request methods
    requests: requests,
    get: requests.get,
    post: requests.post,
    put: requests.put,
    delete: requests.delete,

    //Array methods
    findById: arrays.findById,
    findIndexById: arrays.findIndexById,
    deleteById: arrays.deleteById,

    //Date methods
    formatDateForUser: dates.formatForUser,
    getDaysAgo: dates.getDaysAgo,

    deleteFromArray: function (itemToDelete, array) {
        var updatedArray = helpers.deleteById(array, itemToDelete.id);
        // object.set(array, '', updatedArray);
        // return object;
        return updatedArray;
    },

    /**
     *
     * @param item
     * @param array
     * @returns {*}
     */
    updateItemInArray: function (item, array) {
        var index = helpers.findIndexById(array, item.id);
        array[index] = item;
        //array should now be updated
        return array;
    },

    /**
     *
     * @param data
     * @param status
     * @param response
     */
    handleResponseError: function (response) {
        store.hideLoading();
        app.__vue__.$bus.$emit('response-error', response);
        // $.event.trigger('response-error', [data, status, response]);
        $.event.trigger('hide-loading');
    },

    getRouter: function () {
        return app.__vue__.$router;
    },

    goToRoute (path) {
        this.getRouter().push(path);
    },

    /**
     * for vue-js-modal
     * @param popupName
     */
    // hidePopup: function (popupName) {
    //     app.__vue__.$modal.hide(popupName);
    // },

    // openPopup: function (popupName) {
    //     app.__vue__.$modal.show(popupName);
    // },

    showPopup: function (popupName) {
        store.set(true, 'showPopup')
    },

    hidePopup: function () {
        store.set(false, 'showPopup');
    },

    /**
     *
     */
    closePopup: function ($event, that, routeToGoTo) {
        if ($($event.target).hasClass('popup-outer')) {
            // that.$emit('update:showPopup', false);
            // that.$router.push(routeToGoTo);
            store.set(false, 'showPopup');
        }
    },

    /**
     *
     * @param object
     */
    clone: function (object) {
        return JSON.parse(JSON.stringify(object));
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
    addZeros: function (number) {
        if (number < 10) {
            return '0' + number;
        }

        return number;
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
     * commenting out for now because it was erroring saying .tooltipster is not a function
     */
    tooltips: function () {
        var width = $(window).width();
        // Trigger on click rather than hover for small screens
        var trigger = width < 800 ? 'click' : 'hover';

        $('.tooltipster').tooltipster({
            theme: 'tooltipster-punk',
            //Animation duration for in and out
            animationDuration: [1000, 500],
            trigger: trigger,
            side: 'right',
            functionInit: function(instance, helper){

                var $origin = $(helper.origin),
                    dataOptions = $origin.attr('data-tooltipster');

                if(dataOptions){

                    dataOptions = JSON.parse(dataOptions);

                    $.each(dataOptions, function(name, option){
                        instance.option(name, option);
                    });
                }
            }
        });
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

    getCurrentPath () {
        return this.getRouter().currentRoute.path;
    },

    /**
     * If url is /items/:2, return 2
     * @param that
     * @returns {*}
     */
    getIdFromUrl: function () {
        var idWithColon =  this.getRouter().currentRoute.params.id;
        var id;

        if (!idWithColon) return false;

        var index = idWithColon.indexOf(':');

        if (index != -1) {
            id = idWithColon.slice(index+1);
        }

        return id;
    }
}