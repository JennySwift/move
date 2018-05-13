require('./bootstrap');

import Vue from 'vue'

import Framework7 from 'framework7/dist/framework7.esm.bundle.js';
import Framework7Vue from 'framework7-vue/dist/framework7-vue.esm.bundle.js';
// import Framework7Styles from 'framework7/dist/css/framework7.css';


import 'framework7-icons';
Vue.use(Framework7Vue, Framework7)

import store from './repositories/Store'
import helpers from './repositories/Helpers'
import filters from './repositories/FiltersRepository'
import routes from './routes'


// // import VueRouter from 'vue-router'
// // Vue.use(VueRouter);


window.Event = new Vue();

require('./components');
require('./config.js');

global.store = store;
global.helpers = helpers;
global.filters = filters;


const bus = new Vue()
Vue.prototype.$bus = bus




// // const router = new VueRouter({
// //     routes: routes
// // })

const app = new Vue({
    el: '#app',
    // router: router,
    mounted: function () {
        store.getExercises();
        store.getExerciseUnits();
        store.getWorkouts();
        store.getSessions();
    },
    framework7: {
        root: '#app',
        id: 'move-app',
        name: 'Move',
        theme: 'ios',
        routes: routes,
        view: {
            pushState: true,
        },
        panel: {
            swipe: 'right'
        }
    }
});


