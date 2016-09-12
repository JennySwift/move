
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

var Vue = require('vue');
var $ = require('jquery');
global.jQuery = require('jquery');
global._ = require('underscore');
global.store = require('./repositories/Store');
var VueResource = require('vue-resource');
Vue.use(VueResource);
require('./config.js');
global.helpers = require('./repositories/Helpers');
Date.setLocale('en-AU');

//Shared components
Vue.component('navbar', require('./components/shared/NavbarComponent.vue'));
Vue.component('feedback', require('@jennyswift/feedback'));
Vue.component('loading', require('./components/shared/LoadingComponent.vue'));
Vue.component('popup', require('./components/shared/PopupComponent.vue'));
Vue.component('autocomplete', require('./components/shared/AutocompleteComponent.vue'));
// Vue.component('date-picker', require('./components/Shared/DatePickerComponent.vue'));
Vue.component('date-navigation', require('./components/shared/DateNavigationComponent.vue'));

//Components
Vue.component('new-exercise', require('./components/NewExerciseComponent.vue'));
Vue.component('new-exercise-entry', require('./components/NewExerciseEntryComponent.vue'));
Vue.component('exercise-entries', require('./components/ExerciseEntriesComponent.vue'));
Vue.component('entries-for-specific-exercise-and-date-and-unit-popup', require('./components/EntriesForSpecificExerciseAndDateAndUnitPopupComponent.vue'));
Vue.component('series-history-popup', require('./components/SeriesHistoryPopupComponent.vue'));
Vue.component('series-popup', require('./components/SeriesPopupComponent.vue'));
Vue.component('new-series', require('./components/NewSeriesComponent.vue'));
Vue.component('exercise-popup', require('./components/ExercisePopupComponent.vue'));

//Transitions
Vue.transition('fade', require('./transitions/FadeTransition'));

require('./routes');





