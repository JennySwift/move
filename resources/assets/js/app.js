
require('./bootstrap');

// window.Vue = require('vue');
import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter);
import store from './repositories/Store'
import helpers from './repositories/Helpers'
import filters from './repositories/FiltersRepository'
import routes from './routes'

window.Event = new Vue();

require('./components');
require('./config.js');
// require('./transitions');

global.store = store;
global.helpers = helpers;
global.filters = filters;

const router = new VueRouter({
    routes: routes
})

const bus = new Vue()
Vue.prototype.$bus = bus

const app = new Vue({
    el: '#app',
    router: router,
    mounted: function () {
        store.getExercises();
        store.getExerciseUnits();
        store.getExerciseSeries();
    }
});




